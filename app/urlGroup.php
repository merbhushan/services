<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class urlGroup extends Model
{
    public function urls(){
        return $this->belongsToMany('App\url');
    }
}
