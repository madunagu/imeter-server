<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Custom\SyncsWithFirestore;

class Post extends Model
{
    use SyncsWithFirestore;

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
