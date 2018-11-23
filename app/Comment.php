<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Custom\SyncsWithFirestore;

class Comment extends Model
{
    use SyncsWithFirestore;

    protected $table = 'comments';

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
