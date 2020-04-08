<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exports\UsersExport;


class HomeController extends Controller
{


    public function index()
    {

    	return view('Home');

    	//return (new UsersExport())->download('test.xlsx');
    }
}
