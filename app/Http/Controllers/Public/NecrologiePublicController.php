<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Necrologie;
use App\Models\rubrique;

class NecrologiePublicController extends Controller
{
    private function sharedViewData(): array
    {
        return [
            'navItems'      => rubrique::all(),
            'tickerPosts'   => collect([]),
            'showAuthModal' => false,
        ];
    }

    public function index()
    {
        $necrologies = Necrologie::with('advertiser')
            ->where('status', 'active')
            ->latest()
            ->paginate(20);

        return view('public.necrologies.index', array_merge(
            compact('necrologies'),
            $this->sharedViewData()
        ));
    }

    public function show(Necrologie $necrologie)
    {
        abort_if($necrologie->status !== 'active', 404);
        return view('public.necrologies.show', array_merge(
            compact('necrologie'),
            $this->sharedViewData()
        ));
    }
}
