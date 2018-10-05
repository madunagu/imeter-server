<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Mpociot\Firebase\SyncsWithFirebase;

class Post extends Model
{
    use SyncsWithFirebase;

    protected $table = 'posts';

    protected $fillable = [
        'title', 'body', 'password',
    ];


    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }
}
