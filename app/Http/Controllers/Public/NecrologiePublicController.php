<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Necrologie;

class NecrologiePublicController extends Controller
{
    public function index()
    {
        $necrologies = Necrologie::with('advertiser')
            ->where('status', 'active')
            ->latest()
            ->paginate(20);

        return view('public.necrologies.index', compact('necrologies'));
    }

    public function show(Necrologie $necrologie)
    {
        abort_if($necrologie->status !== 'active', 404);
        return view('public.necrologies.show', compact('necrologie'));
    }
}
