<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepostoryInterface {

    /**
     * Get all customers
     *
     * @return void
     */
    public function all() {}

    /**
     * Get a customer by phone number
     *
     * @return void
     */
    public function getCustomerByPhoneNumber() {}

    /**
     * Get a customer by meter number
     *
     * @return void
     */
    public function getCustomerByMeterNumber() {}
}

