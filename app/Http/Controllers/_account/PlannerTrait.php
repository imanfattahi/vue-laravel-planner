<?php

namespace App\Http\Controllers\_account;

use Illuminate\Http\Request;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait PlannerTrait {

    # Single page - planner
    public function planner () {
        return view('account.planner');
    }

    # Get authenticated user plans
    public function getPlans () {
        $user = User::find(Auth::id());
        $json = $user->plans;
        return response()->json($json);
    }

    # save & update plans
    public function setPlan (Request $request) {
        try {
            $user = User::find(Auth::id());
            var_dump($request->plans);
            $user->plans = $request->plans;
            $user->save();
            return response()->json($request->all());
        } catch (Exception $e) {
            Log::error(print_r($e));
        }
    }

}
