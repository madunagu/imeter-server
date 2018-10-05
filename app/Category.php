<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Mpociot\Firebase\SyncsWithFirebase;

class Category extends Model
{
    use SyncsWithFirebase;

    public function posts()
    {
        return $this->belongsToMany('App\Post');
    }
}
