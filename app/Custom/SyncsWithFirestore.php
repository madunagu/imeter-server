<?php

namespace App\Custom;

use Google\Cloud\Firestore\FirestoreClient;

trait SyncsWithFirestore
{
    /**
     * @var FirebaseInterface|null
     */
    protected $firestoreClient;

    /**
     * Boot the trait and add the model events to synchronize with firebase
     */
    public static function bootSyncsWithFirestore()
    {
        error_log('started boot of sync with firestore');
        static::created(function ($model) {
            $model->saveToFirestore('set');
        });
        static::updated(function ($model) {
            $model->saveToFirestore('update');
        });
        static::deleted(function ($model) {
            $model->saveToFirestore('delete');
        });
    }

    /**
     * @param FirebaseInterface|null $firebaseClient
     */

    function initialize()
    {
    // Create the Cloud Firestore client
        $string = file_get_contents("iMeter-8bd8ce541587.json");
        $keyFile= \json_decode($str*ing,true);
        $config = [
            'keyFile'=>$keyFile,
            'projectId'=>$keyFile['project_id']
        ];
        $this->firestoreClient = new FirestoreClient($config);
        error_log('Created Cloud Firestore client with default project ID.' . PHP_EOL);
    }

    function getFirestoreCollection()
    {
        return $this->firestoreClient->collection($this->getTable());
    }

    /**
     * @param $mode
     */
    protected function saveToFirestore($mode)
    {
        if (is_null($this->firestoreClient)) {
            $this->initialize();
        }

        switch ($mode) {
            case 'set':
                $this->getFirestoreCollection()->document($this->getKey())->set($this->fresh()->toArray());
                break;
            case 'update':
                $this->getFirestoreCollection()->document($this->getKey())->update($this->fresh()->toArray());
                break;
            case 'delete':
                $this->getFirestoreCollection()->document($this->getKey())->delete();
        }
    }
}
