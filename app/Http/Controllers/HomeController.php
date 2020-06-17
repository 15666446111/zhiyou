<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        
        $trade = \App\Trade::orderBy('id', 'desc')->first();

        /**
         * @version [< 给当前交易进行分润发放 >]
         */
        //$cash = new \App\Http\Controllers\StandardMerchantController($trade);

        //$result = $cash->standard();

        //dd($result);
        
        return view('home');
    }


}
