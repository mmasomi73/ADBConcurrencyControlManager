<?php


namespace App\Utilities\TwoPhaseLocking;


use App\Utilities\Operation;

class LockManager
{
    private $readLockQueue = [[]];
    private $writeLockQueue = [];
    private $writeDenyList = [];
    private $readDenyList = [];

    public function __construct()
    {
        for ($i = 1;$i<8;$i++){
            $this->readDenyList[$i.""] = 0;
            $this->writeDenyList[$i.""] = 0;

        }
    }

    public function sharedLock(Operation $operation)
    {

        if ($this->checkLock('s',$operation->getTransaction(),$operation->getItem())){
            return 'w';
        }
        return 'w';

    }

    public function exclusiveLock($transaction,$item)
    {

    }

    public function unLock($transaction,$item)
    {

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
        if ($type == "s"){
            // if item write_lock by another transaction can't give them
            if ($this->checkWriteLockQueue($transaction,$item)) return true;

            // if this transaction lock this item previous in shared mode, add to DENY_LIST and can give lock
            elseif ($this->isLocked("s",$transaction,$item)){
                $this->readenyList[$transaction] = $this->readenyList[$transaction] + 1;
                return false;
            }
            // if this transaction lock this item previous in exclusive mode, can give lock
            elseif ($this->isLocked("w",$transaction,$item)){
                // Downgrade Lock => in practice remove from Write Lock Queue
                $this->removeFromWriteLockQueue($transaction,$item);
                return false;
            }
        }

        /*
         * Check for Exclusive mode:
         *      1. if item lock in Exclusive mode by Another transaction    :Can't Give
         *      2. if item shared lock again Add to Deny list               : Can Give
         *      3. if item has exclusive lock and want to give shared lock  : Can Give
         *      NOTE: in State 3: we propose not exist any Transaction in Read lock Queue of that item
         */
        if ($type == "w"){
            // if item write_lock by another transaction can't give them
            if ($this->checkWriteLockQueue($transaction,$item)) return true;

            // if this transaction lock this item previous in shared mode, add to DENY_LIST and can give lock
            elseif ($this->isLocked("s",$transaction,$item)){
                $this->denyList[$transaction] = $this->denyList[$transaction] + 1;
                return false;
            }

            // if this transaction lock this item previous in exclusive mode, can give lock
            elseif ($this->isLocked("w",$transaction,$item)){
                // Downgrade Lock => in practice remove from Write Lock Queue
                $this->removeFromWriteLockQueue($transaction,$item);
                return false;
            }
        }
        return true;
    }
    private function isLocked($type,$transaction,$item)
    {
        if ($type == "s"){
            return in_array($transaction, $this->readLockQueue[$item]);
        }elseif ($type == "w"){
            return key_exists($item,$this->readLockQueue[$item]) && $this->readLockQueue[$item] == $transaction;
        }
        return false;
    }

    private function checkUnlock($type,$transaction,$item)
    {
        $queue = $type == "s" ? $this->readLockQueue[$item] : $this->writeLockQueue[$item];
        $number =  array_count_values($queue);
    }

    private function checkWriteLockQueue($transaction,$item){
        if(array_key_exists($item,$this->writeLockQueue)){
            return $this->writeLockQueue[$item] != $transaction;
        }
        return false;
    }

    private function removeFromWriteLockQueue($transaction,$item)
    {
        if (key_exists($item,$this->writeLockQueue) && $this->writeLockQueue[$item] == $transaction )
        $this->writeLockQueue[$item] = "";
    }


}
