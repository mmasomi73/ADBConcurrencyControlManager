<?php

namespace App\Utilities;

class Operation
{
    private $operation;
    private $transaction;
    private $item;

    private $w_delay = 10;
    private $r_delay = 3;
    private $a_delay = 5;
    private $c_delay = 7;

    public function __construct($operation, $transaction, $item)
    {
        $this->operation = $operation;
        $this->transaction = $transaction;
        $this->item = $item;
    }

    /**
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @return string
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    private function write()
    {
        return usleep($this->w_delay * 1000);
    }

    private function read()
    {
        return usleep($this->r_delay * 1000);
    }

    private function commit()
    {
        return usleep($this->c_delay * 1000);
    }

    private function abort()
    {
        return usleep($this->a_delay * 1000);
    }

    public function execute()
    {
        if ($this->operation == "w") return $this->write();
        if ($this->operation == "r") return $this->read();
        if ($this->operation == "c") return $this->commit();
        if ($this->operation == "a") return $this->abort();
    }

    public function toString()
    {
        if ($this->operation == "c" || $this->operation == "a") {
            return $this->operation . "(" . $this->transaction . ")";
        } else {
            return $this->operation . "(" . $this->transaction . "," . $this->item . ")";
        }
    }

}
