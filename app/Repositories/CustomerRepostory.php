<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\CustomerRepostoryInterface;

class CustomerRepostory {

    private $model;

    /**
     * __construct
     *
     * @param  mixed $customer
     * @return void
     */
    public function __construct(Customer $customer) {
        $this->model = $customer;
    }

    /**
     * Get all customers
     *
     * @return void
     */
    public function all() {

        return $this->model->all();

    }

    /**
     * Get a customer by phone number
     *
     * @return void
     */
    public function getCustomerByPhoneNumber($phoneNumber) {

        return $this->model->where('phone_number', $phoneNumber)->first();

    }

    /**
     * Get a customer by meter number
     *
     * @return void
     */
    public function getCustomerByMeterNumber($meterNumber) {

        return $this->model->where('meter_number', $meterNumber)->first();

    }
}

