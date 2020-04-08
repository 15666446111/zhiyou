<?php
 
namespace App\Exports;
 
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
 
class UsersExport implements FromArray
{
    use Exportable;


    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return \App\UserGroup::all()->toArray();
    }
}