<?php

namespace App\Payments;

use Exception;

abstract class Payment
{
    protected $config;
    public function __construct(object $config)
    {
        $this->config = $config;
        $this->prepare();
    }


    protected abstract function prepare() : void;

    public abstract function refund(object $argument) : void;


    public abstract function createOrder(object $order) : object;


    public abstract function transactionStatus(object $argument) : object;


}
