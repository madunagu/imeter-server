<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServerRequest extends Model
{
    #protocol for most communication
    public static $requestTypeToggleConnection = '1';
    public static $requestKeyToggleConnection = 'CD';    
}
