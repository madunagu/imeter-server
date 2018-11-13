<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EnergyBudget;

class EnergyBalanceController extends Controller
{
    // public function getEnergyBudget($meter_id): EnergyBudget
    // {
    //     return EnergyBudget::where('meter_id', $meter_id)->orderBy('id', 'desc')->first();

    // }

    // public function compareUsageWithEnergyBudget(Request $request)
    // {
    //     $meter_id = $request['meter_id'];
    //     $energyBudget = $this->getEnergyBudget($meter_id);
    //     #here use a simple algorithm initially
    //     // TODO: here relate the usage to the energy balance and then notify based on the time limit
    //     $usage = $energyBudget->getEnergyUsage();
    //     # here complete the comparison of the energy budget


    // }
}
