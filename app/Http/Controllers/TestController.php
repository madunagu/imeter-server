<?php

namespace App\Http\Controllers;

use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request){
        echo 'here';

        $this->initialize();

    }

    public function initialize()
    {
        // Create the Cloud Firestore client
        //set GOOGLE_APPLICATION_CREDENTIALS=C:\Users\FEDIRONICS\iMeter-8bd8ce541587.json

        $string = file_get_contents("../iMeter-8bd8ce541587.json");
        $keyFile= \json_decode($string,true);
        $config = [
            'keyFile'=>$keyFile,
        ];

        $db = new FirestoreClient($config);
        $usersRef = $db->collection('users');
        $snapshot = $usersRef->documents();
        foreach ($snapshot as $user) {
            printf('User: %s' . PHP_EOL, $user->id());
            printf('First: %s' . PHP_EOL, $user['first']);
            if (!empty($user['middle'])) {
                printf('Middle: %s' . PHP_EOL, $user['middle']);
            }
            printf('Last: %s' . PHP_EOL, $user['last']);
            printf('Born: %d' . PHP_EOL, $user['born']);
            printf(PHP_EOL);
        }
        printf('Retrieved and printed out all documents from the users collection.' . PHP_EOL);

    }
}
