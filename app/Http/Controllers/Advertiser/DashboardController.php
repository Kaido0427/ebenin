<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $advertiser = Auth::guard('advertiser')->user();
        $annonces   = $advertiser->annonces()->latest()->get();
        $necrologies = $advertiser->necrologies()->latest()->get();

        return view('advertiser.dashboard.index', compact('advertiser', 'annonces', 'necrologies'));
    }
}
