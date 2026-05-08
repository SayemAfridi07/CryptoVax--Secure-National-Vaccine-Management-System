<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $center = auth()?->user()?->center ?? [];

        return view('operator.dashboard.index', ['center' => $center]);
    }
}
