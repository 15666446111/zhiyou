<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

use Encore\Admin\Widgets\Box;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content 

            ->title('欢迎回家👏')

            ->description('欢迎登陆管理系统')

            // ->row(function (Row $row) {

            //     $row->column(4, function (Column $column) {
            //         $UserBox = new Box('会员统计', $this->users()); 
            //         $UserBox->removable();
            //         $UserBox->collapsable();
            //         $UserBox->style('info');
            //         $UserBox->solid();
            //         $UserBox->scrollable();
            //         $column->append($UserBox);
            //     });

            //     $row->column(4, function (Column $column) {
            //         $column->append(Dashboard::extensions());
            //     });

            //     $row->column(4, function (Column $column) {
            //         $column->append(Dashboard::dependencies());
            //     });

            // })
            
            // ->row(function (Row $row) {

            //     $row->column(4, function (Column $column) {
            //         $column->append(Dashboard::environment());
            //     });

            //     $row->column(4, function (Column $column) {
            //         $column->append(Dashboard::extensions());
            //     });

            //     $row->column(4, function (Column $column) {
            //         $column->append(Dashboard::dependencies());
            //     });

            // })
            ;
    }

    /** 统计会员信息 **/
    public function users()
    {
        return view('admin.chartjs');
    }
}
