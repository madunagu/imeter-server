<?php

namespace App\Http\Controllers;

use Google\Cloud\Firestore\FirestoreClient;
use App\Post;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request){
    	echo 'here';
    	phpinfo();
    	print_r(xdebug_get_code_coverage());

    	//$this->initialize();

    }

    function initialize()
    {
        // Create the Cloud Firestore client
        $db = new FirestoreClient();
        printf('Created Cloud Firestore client with default project ID.' . PHP_EOL);
        print_r($db);
    }
}
