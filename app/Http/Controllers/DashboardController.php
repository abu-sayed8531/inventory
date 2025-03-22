<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashBoardController extends Controller
{
     public function dashboardPage()
     {
          return view('pages.dashboard.dashboard-page');
     }
}
