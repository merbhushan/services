<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class setting extends Model
{
    public function getSetting($strName){
        return $this->select(DB::raw('id, IF(display_name is null OR display_name = "", name, display_name) as name, value, description'))
                    ->where([
                        ['name',$strName],
                        ['status', 'Active']
                    ])
                    ->get()[0];
    }
}
