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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
