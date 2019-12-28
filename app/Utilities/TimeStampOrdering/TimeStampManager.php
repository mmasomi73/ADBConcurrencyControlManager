<?php


namespace App\Utilities\TimeStampOrdering;


use App\Utilities\Operation;

class TimeStampManager
{

    private $readTS = [];
    private $writeTS = [];
    private $timestamps = [];
    private $timestampNumber;


    public function __construct()
    {
        $this->timestampNumber = 0;
    }

    public function isCompatible(Operation $operation)
    {
        $item = $operation->getItem();
        $transaction = $operation->getTransaction();
        $operation = $operation->getOperation();
        if ($operation == "w"){
            if ($this->getReadTS($item) > $this->generateTS($transaction)) return false;
            if ($this->getWriteTS($item) > $this->generateTS($transaction)) return false;

            $this->updateWriteTS($item,$transaction);
        }elseif ($operation == "r"){
            if ($this->getWriteTS($item) > $this->generateTS($transaction)) return false;
            $this->updateReadTS($item,$transaction);
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

    private function updateWriteTS($item,$transaction)
    {
        if ((key_exists($item, $this->writeTS) && $this->writeTS[$item] < $this->generateTS($transaction))
            || !key_exists($item, $this->writeTS)){
            $this->writeTS[$item] = $this->generateTS($transaction);
        }
    }

    private function updateReadTS($item,$transaction)
    {
        if ((key_exists($item, $this->readTS) && $this->readTS[$item] < $this->generateTS($transaction))
            || !key_exists($item, $this->readTS)){
            $this->readTS[$item] = $this->generateTS($transaction);
        }
    }

    private function generateTS($transaction)
    {
        return key_exists($transaction,$this->timestamps) ? $this->timestamps[$transaction] : $this->timestamps[$transaction] = ++$this->timestampNumber;
    }

}
