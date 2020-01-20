<?php

namespace App\Http\Controllers;
use App\Http\Controllers\_account\PlannerTrait;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    // Users planner methods
    use PlannerTrait;

    public function index (Request $request) {
        return view('account.index');
    }

}
