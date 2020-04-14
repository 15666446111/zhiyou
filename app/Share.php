<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{


    public function types()
    {
    	return $this->belongsTo('\App\ShareType', 'type', 'id');
    }
}
