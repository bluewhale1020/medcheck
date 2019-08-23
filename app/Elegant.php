<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Elegant extends Model
{
    protected $rules = [];
    protected $updateRules = [];

    protected $errors;

    public function validate($data,$mode = 'create')
    {
        // make a new validator object
        if($mode == 'create'){
            $v = Validator::make($data, $this->rules);
            
        }else{
            $v = Validator::make($data, $this->updateRules);
            
        }

        // check for failure
        if ($v->fails())
        {
            // set errors and return false
            $this->errors = $v->errors;
            return false;
        }

        // validation pass
        return true;
    }

    public function errors()
    {
        return $this->errors;
    }
}
