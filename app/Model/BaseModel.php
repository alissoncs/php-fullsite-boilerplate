<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model {

    public function __isset($name){
        if (in_array($name, $this->fillable)) {
            return true;
        } else {
            return parent::__isset($name);
        }
    }

}
