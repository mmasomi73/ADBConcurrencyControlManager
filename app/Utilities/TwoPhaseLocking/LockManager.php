<?php

namespace App\Utilities\TwoPhaseLocking;


use App\Utilities\Operation;

class LockManager
{
    private $downUpgrading = false;
    private $readLockQueue = [[]];
    private $writeLockQueue = [];
    private $writeDenyList = [];
    private $readDenyList = [];

    public function __construct($downUpgrading = false)
    {
        $this->downUpgrading = $downUpgrading;
        for ($i = 1; $i < 8; $i++) {
            $this->readDenyList[$i . ""] = 0;
            $this->writeDenyList[$i . ""] = 0;

        }
    }

    public function lock(Operation $operation)
    {
        if ($operation->getOperation() == "r") return $this->sharedLock($operation);
        if ($operation->getOperation() == "w") return $this->exclusiveLock($operation);
        if ($operation->getOperation() == "c" || $operation->getOperation() == "a")
            return "finish";
    }

    public function sharedLock(Operation $operation)
    {
        $result = $this->checkLock("r", $operation->getTransaction(), $operation->getItem());
        if ($result == 'not') {
            return 'wait';
        }
        $this->AddToReadLockQueue($operation->getTransaction(), $operation->getItem());
        return $result == 'ok' ? 'locked' : 'deny';

    }

    public function exclusiveLock(Operation $operation)
    {
        $result = $this->checkLock('w', $operation->getTransaction(), $operation->getItem());
        if ($result == 'not') {
            return 'wait';
        }
        $this->AddToWriteLockQueue($operation->getTransaction(), $operation->getItem());
        return $result == 'ok' ? 'locked' : 'deny';
    }

    public function unLock(Operation $operation)
    {
        if ($operation->getOperation() == "w") {
            if ($this->writeDenyList[$operation->getTransaction()] > 0) {
                $this->writeDenyList[$operation->getTransaction()] = $this->writeDenyList[$operation->getTransaction()] - 1;
                return 'deny';
            } else {
                $this->removeFromWriteLockQueue($operation->getTransaction(), $operation->getItem());
                return 'ok';
            }
        }
        if ($operation->getOperation() == "r") {
            if ($this->readDenyList[$operation->getTransaction()] > 0) {
                $this->readDenyList[$operation->getTransaction()] = $this->readDenyList[$operation->getTransaction()] - 1;
                return 'deny';
            } else {
                $this->removeFromReadLockQueue($operation->getTransaction(), $operation->getItem());
                return 'ok';
            }
        }
        if ($operation->getOperation() == "c" || $operation->getOperation() == "a") {
            if ($this->writeDenyList[$operation->getTransaction()] > 0) {
                $this->writeDenyList[$operation->getTransaction()] = 0;
            }
            if ($this->readDenyList[$operation->getTransaction()] > 0) {
                $this->readDenyList[$operation->getTransaction()] = 0;
            }
            foreach ($this->writeLockQueue as $key => $item) {
                if ($item == $operation->getTransaction()) $this->removeFromWriteLockQueue($operation->getTransaction(), $key);
            }
            foreach ($this->readLockQueue as $key => $item) {
                foreach ($item as $tr) {
                    if ($operation->getTransaction() == $tr) {
                        $this->removeFromReadLockQueue($operation->getTransaction(), $key);
                    }
                }
            }
        }
        return 'none';
    }

    /**
     * Check item is locked by another transaction: TRUE: not Can Lock, FALSE: Can lock
     * @param $type
     * @param $transaction
     * @param $item
     * @return bool
     */
    private function checkLock($type, $transaction, $item)
    {
        /*
         * Check for Shared mode:
         *      1. if item lock in Exclusive mode by Another transaction    :Can't Give
         *      2. if item shared lock again Add to Deny list               : Can Give
         *      3. if item has exclusive lock and want to give shared lock  : Can Give
         *      NOTE: in State 3: we propose not exist any Transaction in Read lock Queue of that item
         */
        if ($type == "r") {
            // if item write_lock by another transaction can't give them
            if ($this->checkWriteLockQueue($transaction, $item)) return 'not';

            // if this transaction lock this item previous in shared mode, add to DENY_LIST and can give lock
            elseif ($this->isLocked("r", $transaction, $item)) {
                $this->readDenyList[$transaction] = $this->readDenyList[$transaction] + 1;
                return 'deny';
            } // if this transaction lock this item previous in exclusive mode, can give lock
            elseif ($this->isLocked("w", $transaction, $item)) {
                // Downgrade Lock => in practice remove from Write Lock Queue
                $this->downUpgrading ? $this->removeFromWriteLockQueue($transaction, $item) : null;
                return 'deny';
            }
        }

        /*
         * Check for Exclusive mode:
         *      1. if item lock in Exclusive mode by Another transaction    :Can't Give
         *      2. if item shared lock again Add to Deny list               : Can Give
         *      3. if item has exclusive lock and want to give shared lock  : Can Give
         *      NOTE: in State 3: we propose not exist any Transaction in Read lock Queue of that item
         */
        if ($type == "w") {
            // if item write_lock by another transaction can't give them
            if ($this->checkWriteLockQueue($transaction, $item)) return 'not';
            if ($this->checkReadLockQueue($transaction, $item)) return 'not';

            // if this transaction lock this item previous in shared mode, add to DENY_LIST and can give lock
            elseif ($this->isLocked("r", $transaction, $item)) {
                // Upgrading Lock
                $this->downUpgrading ? $this->removeFromReadLockQueue($transaction, $item) : null;
                return 'deny';
            } // if this transaction lock this item previous in exclusive mode, can give lock
            elseif ($this->isLocked("w", $transaction, $item)) {
                $this->writeDenyList[$transaction] = $this->writeDenyList[$transaction] + 1;
                return 'deny';
            }
        }
        return 'ok';
    }

    private function isLocked($type, $transaction, $item)
    {
        if ($type == "r" && key_exists($item, $this->readLockQueue)) {
            try {
                return in_array($transaction, $this->readLockQueue[$item]);
            } catch (\Exception $exception) {
//                dd($exception);
            }
        } elseif ($type == "w") {
            return key_exists($item, $this->writeLockQueue) && $this->writeLockQueue[$item] == $transaction;
        }
        return false;
    }

    private function checkWriteLockQueue($transaction, $item)
    {
        if (array_key_exists($item, $this->writeLockQueue)) {
            return $this->writeLockQueue[$item] != $transaction;
        }
        return false;
    }

    private function checkReadLockQueue($transaction, $item)
    {
        if (array_key_exists($item, $this->readLockQueue)) {
            return in_array($transaction, $this->readLockQueue[$item]);
        }
        return false;
    }

    private function removeFromWriteLockQueue($transaction, $item)
    {
        if (key_exists($item, $this->writeLockQueue) && $this->writeLockQueue[$item] == $transaction)
            unset($this->writeLockQueue[$item]);
    }

    private function removeFromReadLockQueue($transaction, $item)
    {

        if (key_exists($item, $this->readLockQueue)) {
            $index = array_search($transaction, $this->readLockQueue[$item]);
            if ($index >= 0) {
                unset($this->readLockQueue[$item][$index]);
                if (count($this->readLockQueue[$item]) == 0) unset($this->readLockQueue[$item]);
            }
        }

    }

    private function AddToReadLockQueue($transaction, $item)
    {
        $this->readLockQueue[$item][] = $transaction;
    }

    private function AddToWriteLockQueue($transaction, $item)
    {
        $this->writeLockQueue[$item] = $transaction;
    }

    public function unlockAll($transaction)
    {
        $list = [];
        if ($this->writeDenyList[$transaction] > 0) {
            $this->writeDenyList[$transaction] = 0;
        }
        if ($this->readDenyList[$transaction] > 0) {
            $this->readDenyList[$transaction] = 0;
        }
        foreach ($this->writeLockQueue as $key => $item) {
            if ($item == $transaction) {
                $list[] = $key;
                $this->removeFromWriteLockQueue($transaction, $key);
            }
        }
        foreach ($this->readLockQueue as $key => $item) {
            foreach ($item as $tr) {
                if ($transaction == $tr) {
                    $list[] = $key;
                    $this->removeFromReadLockQueue($transaction, $key);
                }
            }
        }

        return $list;
    }

    public function unlockAllString($transaction)
    {
        $list = [];
        if ($this->writeDenyList[$transaction] > 0) {
            $this->writeDenyList[$transaction] = 0;
        }
        if ($this->readDenyList[$transaction] > 0) {
            $this->readDenyList[$transaction] = 0;
        }
        foreach ($this->writeLockQueue as $key => $item) {
            if ($item == $transaction) {
                $list[] = [$key,'w'];
                $this->removeFromWriteLockQueue($transaction, $key);
            }
        }
        foreach ($this->readLockQueue as $key => $item) {
            foreach ($item as $tr) {
                if ($transaction == $tr) {
                    $list[] = [$key,'r'];
                    $this->removeFromReadLockQueue($transaction, $key);
                }
            }
        }

        return $list;
    }

    public function hasLocked(Operation $operation)
    {
        return $this->isLocked($operation->getOperation(), $operation->getTransaction(), $operation->getItem());
    }

    public function isCompatible(Operation $operation)
    {
        if ($operation->getOperation() == "r") {
            return !(key_exists($operation->getItem(), $this->writeLockQueue) && $this->writeLockQueue[$operation->getItem()] != $operation->getTransaction());
        } elseif ($operation->getOperation() == "w") {
            try {
                return !(in_array($operation->getTransaction(), $this->readLockQueue[$operation->getItem()]) ||
                    (key_exists($operation->getItem(), $this->writeLockQueue) &&
                        $this->writeLockQueue[$operation->getItem()] != $operation->getTransaction()));
            } catch (\Exception $exception) {
                return false;
            }
        }
        return false;
    }
}
