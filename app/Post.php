<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
