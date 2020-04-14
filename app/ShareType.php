<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShareType extends Model
{
    public function shares()
    {
    	return $this->hasMany('\App\Share', 'type', 'id');
    }
}
