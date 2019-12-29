<?php


namespace App\Utilities\TimeStampOrdering;


use App\Utilities\Operation;

class TimeStampManager
{

    private $readTS = [];
    private $writeTS = [];
    private $timestamps = [];
    private $timestampNumber;
    private $histories = [[]];


    public function __construct()
    {
        $this->timestampNumber = 0;
    }

    public function isCompatible(Operation $operation)
    {
        $item = $operation->getItem();
        $transaction = $operation->getTransaction();
        $operation = $operation->getOperation();
        if ($operation == "w") {
            if ($this->getReadTS($item) > $this->generateTS($transaction)) return false;
            if ($this->getWriteTS($item) > $this->generateTS($transaction)) return false;

            $this->updateWriteTS($item, $transaction);
        } elseif ($operation == "r") {
            if ($this->getWriteTS($item) > $this->generateTS($transaction)) return false;

            $this->updateReadTS($item, $transaction);
            $this->addToHistory($item, $transaction); //This line help to can detect cascading transactions rollback
        }
        return true;
    }

    public function getWriteTS($item)
    {
        return key_exists($item, $this->writeTS) ? $this->writeTS[$item] : 0;
    }

    public function getReadTS($item)
    {
        return key_exists($item, $this->readTS) ? $this->readTS[$item] : 0;
    }

    public function getCascadingRollback(Operation $operation)
    {
        return $this->findCascadingRollback($operation->getTransaction());
    }

    private function updateWriteTS($item, $transaction)
    {
        if ((key_exists($item, $this->writeTS) && $this->writeTS[$item] < $this->generateTS($transaction))
            || !key_exists($item, $this->writeTS)) {
            $this->writeTS[$item] = $this->generateTS($transaction);
        }
    }

    private function updateReadTS($item, $transaction)
    {
        if ((key_exists($item, $this->readTS) && $this->readTS[$item] < $this->generateTS($transaction))
            || !key_exists($item, $this->readTS)) {
            $this->readTS[$item] = $this->generateTS($transaction);
        }
    }

    private function generateTS($transaction)
    {
        return key_exists($transaction, $this->timestamps) ? $this->timestamps[$transaction] : $this->timestamps[$transaction] = ++$this->timestampNumber;
    }

    private function addToHistory($item, $transaction)
    {
        /*
         * For Detect Cascade rollback need to save reads for each version of writes
         *  [
         *      x : [
         *            timestamp_Y : [transaction_X] =>  transaction X Read the write of Transaction With timestamp Y
         *          ]
         *  ]
         *  NOTE: 0 Means read from source
         */
        $timestamp = $this->getWriteTS($item);
        if ($timestamp == $this->generateTS($transaction)) return;
        $this->histories[$item][$timestamp][] = $transaction;
    }

    private function findCascadingRollback($transaction)
    {
        $list = [];
        $timestamp = $this->generateTS($transaction);

        foreach ($this->histories as $history) {
            foreach ($history as $key => $transactions) {
                if ($key == $timestamp){
                    $list = array_merge($list,$transactions);
                }
            }
        }
        $list = array_unique($list);
        return $list;
    }

}
