<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\PowerToggle;
use App\MeterRequest;
use App\MeterStatistics;
use App\MeterRecharge;

class Meter extends Model
{
    protected $fillable = [
        'address', 'username', 'number', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'number',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function meterStatistics()
    {
        return $this->hasMany('App\MeterStatistics');
    }

    public function serverRequests()
    {
        return $this->hasMany('App\ServerRequest');
    }

    public function getLastStatistics(): MeterStatistics
    {
        $stat = MeterStatistics::where('meter_id', $this->id)->orderBy('collected_date', 'desc')->first();
        return $stat;
    }


    public function toggleOn()
    {
        $status = $this->getLastStatistics()->connect_status;
        $pending_power_on = PowerToggle::where('meter_id', $this->id)->where('code',PowerToggle::$connected)->where('done', '0')->orderBy('created_at', 'desc')->first();

        if ($status==PowerToggle::$connected) {
            return response()->json(['success'=>true,['already connected']], 200);
        }

        if (!empty($pending_power_on)) {
            return response()->json(['success'=>true,[
                'there is a pending power on already',
                'please wait for the meter to recieve the request']
                ]);
        }

        if ($status!=PowerToggle::$disconnected) {
            return response()->json(['success'=>false,[
                'You are not authorised to perform this action',
                'please contact you meter administrator or disco for rectification']
                ]);
        }

        $power_toggle = new PowerToggle();
        $power_toggle->meter_id = $this->id;
        $power_toggle->code = PowerToggle::$connected;
        $power_toggle->origin = PowerToggle::$web_originated;
        $power_toggle->save();

        $server_request = new ServerRequest();
        $server_request->meter_id = $this->id;
        $server_request->request_type = ServerRequest::$requestTypeToggleConnection;
        $server_request->request_key =  ServerRequest::$requestKeyToggleConnection;
        $server_request->request_value = PowerToggle::$connected;
        $server_request->save();

        return response()->json(['success'=>true,['succesfully disconnected']],200);
    }

    public function toggleOff()
    {
        $status = $this->getLastStatistics()->connect_status;
        $pending_power_off = PowerToggle::where('meter_id', $this->id)->where('code',PowerToggle::$disconnected)->where('done', '0')->orderBy('created_at', 'desc')->first();

        if ($status==PowerToggle::$disconnected) {
            return response()->json(['success'=>true,['already disconnected']], 200);
        }

        if (!empty($pending_power_off)) {
            return response()->json(['success'=>true,[
                'there is a pending power off already',
                'please wait for the meter to recieve the request']
                ]);
        }

        if ($status!=PowerToggle::$connected) {
            return response()->json(['success'=>false,[
                'You are not authorised to perform this action',
                'please contact you meter administrator or disco for rectification']
                ]);
        }

        $power_toggle = new PowerToggle();
        $power_toggle->meter_id = $this->id;
        $power_toggle->code = PowerToggle::$disconnected;;
        $power_toggle->origin = PowerToggle::$web_originated;
        $power_toggle->save();

        $server_request = new ServerRequest();
        $server_request->meter_id = $this->id;
        $server_request->request_type = ServerRequest::$requestTypeToggleConnection;
        $server_request->request_key = ServerRequest::$requestKeyToggleConnection;
        $server_request->request_value = PowerToggle::$disconnected;;
        $server_request->save();

        return response()->json(['success'=>true,['succesfully disconnected']],200);
    }

    public function recharge($amount)
    {
        $stat = $this->getLastStatistics();
        $balance_amount = $stat->naira_balance;

        $meter_recharge = new MeterRecharge();
        $meter_recharge->meter_id = $this->id;
        $meter_recharge->amount = SwissKnife::ensurePositive($amount);
        $meter_recharge->method = MeterRecharge::$paymentMethodWeb;
        #token should be the payment token recieved from the api
        $meter_recharge->token = 'replace with real payment api token';
        $meter_recharge->save();

        $server_request = new ServerRequest();
        $server_request->meter_id = $this->id;
        $server_request->request_type = ServerRequest::$requestTypePowerTime;
        $server_request->request_key = ServerRequest::$requestKeyPowerTime;
        $server_request->request_value = $meter_recharge->amount;
        $server_request->save();

        return response()->json(['success'=>true,['succesfully disconnected']],200);
    }

    public function setEnergyBalance($balance){

    }
}
