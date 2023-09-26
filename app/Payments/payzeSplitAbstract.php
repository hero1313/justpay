<?php

namespace App\Payments;

use App\Events\OrderReceived;
use App\Events\OrderRejected;

use App\Models\Order;
use Exception;

abstract class payzeSplitAbstract {
    protected $config;
    protected Int $payment_type;

    public function __construct(Object $config) {
        
        
        $this->validateConfig($config);
        $this->config = $config;
        $this->prepare();
    }

    protected abstract function validateConfig(Object $config) : void;
    protected abstract function prepare() : void;

    public abstract function paySplit(Object $args) : Object;


}
