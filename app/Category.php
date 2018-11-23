<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Custom\SyncsWithFirestore;

class Category extends Model
{
    use SyncsWithFirestore;

    public function posts()
    {
        return $this->belongsToMany('App\Post');
    }
}
