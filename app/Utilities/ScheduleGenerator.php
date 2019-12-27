<?php
/**
 * Created by PhpStorm.
 * User: meisam
 * Date: 12/7/2019
 * Time: 20:49
 */

namespace App\Utilities;


class ScheduleGenerator
{

    private $opr = "";
    private $items = ['x', 'y', 'z', 'w', 'v'];
    private $transactions = ['1', '2', '3', '4', '5', '6', '7'];
    private $main_ops = ['r', 'w',];
    private $finish_ops = ['c', 'c', 'c', 'c', 'a'];
    private $result = [];
    private $finished = [];
    private $started = [];
    private $scheduleNumber;
    private $operationNumber;

    private $scheduleCounter = 0;
    private $scheduleObjects = [[]];

    public function __construct($scheduleNumber = 10, $operationNumber = 20)
    {
        $this->scheduleNumber = $scheduleNumber;
        $this->operationNumber = $operationNumber;
    }

    public function getScheduleObjects()
    {
        return $this->scheduleObjects;
    }

    private function getTransaction()
    {
        return $this->transactions[rand(0, count($this->transactions) - 1)];
    }

    private function getItem()
    {
        return $this->items[rand(0, count($this->items)- 1)];
    }

    private function getMainOperation()
    {
        return $this->main_ops[rand(0, count($this->main_ops)- 1)];
    }

    private function getFinishOperation()
    {
        return $this->finish_ops[rand(0, count($this->finish_ops)- 1)];
    }

    private function isFinished($transaction)
    {
        return in_array($transaction, $this->finished);
    }

    private function isStarted($transaction)
    {
        return in_array($transaction, $this->started);
    }

    private function getUnfinishedTransactions()
    {
        $last_T = $this->getTransaction();

        $i = 0;
        while ($this->isFinished($last_T)&& $i++ < 100) $last_T = $this->getTransaction();

        if ($i>= 100) return null;
        return $last_T;
    }

    private function getUnStartedTransactions()
    {
        $last_T = $this->getTransaction();
        $i = 0;
        while ($this->isStarted($last_T) && $i++ < 100) $last_T = $this->getTransaction();

        if ($i>= 100) dd('L76 Err');
        return $last_T;
    }

    private function getStartedTransactions()
    {
        return array_rand($this->started);
    }

    private function finishTransaction($transaction)
    {
        $this->finished[] = $transaction;
        $this->finished = array_unique($this->finished);
    }

    private function startTransaction($transaction)
    {
        $this->started[] = $transaction;
        $this->started = array_unique($this->started);
    }

    private function getOperation($transaction)
    {
        if ($this->isStarted($transaction) && !$this->isFinished($transaction)) {
            $p = rand(1, 14);
            if ($p < 11) {
                $this->startTransaction($transaction);
                $opr = $this->getMainOperation();
            } else {
                $this->finishTransaction($transaction);
                $opr = $this->getFinishOperation();
            }
            return $opr;

        } elseif (!$this->isStarted($transaction)) {
            $this->startTransaction($transaction);
            return $this->getMainOperation();
        }
        return "";
    }

    private function completeSchedule()
    {
        $starts = $this->started;
        $schedule = "";
        foreach ($starts as $st) {
            if (!$this->isFinished($st)) {
                $opr = $this->getFinishOperation();
                $this->finishTransaction($st);
                $schedule .= $this->makeOperation($st,$opr,"");
            }
        }
        return $schedule;
    }

    private function makeOperation($transaction, $opr, $item)
    {
        if ($opr == "c" || $opr == "a") {
            $operation = new Operation($opr,$transaction,null);
            $this->scheduleObjects[$this->scheduleCounter][] = $operation;
            return $opr . "(" . $transaction . ")";
        } else {
            $operation = new Operation($opr,$transaction,$item);
            $this->scheduleObjects[$this->scheduleCounter][] = $operation;
            return $opr . "(" . $transaction . "," . $item . ")";
        }
    }

    private function resetFinished()
    {
        $this->finished = [];
    }

    private function resetStarted()
    {
        $this->started = [];
    }

    public function generate()
    {
        $s_counter = $this->scheduleNumber;
        $o_counter = $this->operationNumber;

        $result = [];
        for ($i = 0; $i < $s_counter; $i++) {
            $this->resetFinished();
            $this->resetStarted();
            $schedule = "";
            for ($j = 0; $j < $o_counter; $j++) {
                $transaction = $this->getUnfinishedTransactions();
                if ($transaction == null) dd($this->result);
                $opr = $this->getOperation($transaction);
                $item = $this->getItem();
                $schedule .= $this->makeOperation($transaction,$opr,$item);
            }
            $schedule .= $this->completeSchedule();
            $this->result[] = $schedule.";";
            $this->scheduleCounter++;
        }

        return $this->result;
    }
}
