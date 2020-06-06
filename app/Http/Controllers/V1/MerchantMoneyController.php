<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerchantMoneyController
{
    /**
     * 终端号
     */
    protected $merchant;

    public function __construct($merchant){
        
    }
}
