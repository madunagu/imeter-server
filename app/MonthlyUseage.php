<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthlyUseage extends Model
{
    protected $table = 'monthly_useage';

    public function yearly_useage()
    {
        return $this->hasMany('App\MonthlyUseage');
    }
}
