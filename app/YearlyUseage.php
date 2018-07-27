<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YearlyUseage extends Model
{
    protected $table = 'yearly_useage';

    public function monthly_useage()
    {
        return $this->hasMany('App\MonthlyUseage');
    }
}
