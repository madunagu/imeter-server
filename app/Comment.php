<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Mpociot\Firebase\SyncsWithFirebase;

class Comment extends Model
{
    use SyncsWithFirebase;
    
    protected $table = 'comments';
    
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
