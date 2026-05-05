<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use App\Models\Necrologie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class NecrologieController extends Controller
{
    public function create()
    {
        return view('advertiser.necrologies.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_defunt'    => 'required|string|max:255',
            'date_naissance'=> 'nullable|date|before:date_deces',
            'date_deces'    => 'required|date',
            'message'       => 'nullable|string',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'video'         => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime|max:51200',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $advertiser = Auth::guard('advertiser')->user();
        $photoPath = null;
        $videoPath = null;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $dest = public_path('uploads/advertisers/necrologies');
            File::ensureDirectoryExists($dest);
            $file->move($dest, $filename);
            $photoPath = 'uploads/advertisers/necrologies/' . $filename;
        }

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $dest = public_path('uploads/advertisers/necrologies/videos');
            File::ensureDirectoryExists($dest);
            $file->move($dest, $filename);
            $videoPath = 'uploads/advertisers/necrologies/videos/' . $filename;
        }

        Necrologie::create([
            'advertiser_id'  => $advertiser->id,
            'nom_defunt'     => $request->input('nom_defunt'),
            'date_naissance' => $request->input('date_naissance'),
            'date_deces'     => $request->input('date_deces'),
            'message'        => $request->input('message'),
            'photo'          => $photoPath,
            'video'          => $videoPath,
            'status'         => 'active',
        ]);

        return redirect()->route('advertiser.dashboard')
            ->with('success', 'Notice de décès publiée avec succès.');
    }

    public function edit(Necrologie $necrologie)
    {
        abort_if($necrologie->advertiser_id !== Auth::guard('advertiser')->id(), 403);
        return view('advertiser.necrologies.edit', compact('necrologie'));
    }

    public function update(Request $request, Necrologie $necrologie)
    {
        abort_if($necrologie->advertiser_id !== Auth::guard('advertiser')->id(), 403);

        $validator = Validator::make($request->all(), [
            'nom_defunt'    => 'required|string|max:255',
            'date_naissance'=> 'nullable|date',
            'date_deces'    => 'required|date',
            'message'       => 'nullable|string',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'video'         => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime|max:51200',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('photo')) {
            if ($necrologie->photo && File::exists(public_path($necrologie->photo))) {
                File::delete(public_path($necrologie->photo));
            }
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $dest = public_path('uploads/advertisers/necrologies');
            File::ensureDirectoryExists($dest);
            $file->move($dest, $filename);
            $necrologie->photo = 'uploads/advertisers/necrologies/' . $filename;
        }

        if ($request->hasFile('video')) {
            if ($necrologie->video && File::exists(public_path($necrologie->video))) {
                File::delete(public_path($necrologie->video));
            }
            $file = $request->file('video');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $dest = public_path('uploads/advertisers/necrologies/videos');
            File::ensureDirectoryExists($dest);
            $file->move($dest, $filename);
            $necrologie->video = 'uploads/advertisers/necrologies/videos/' . $filename;
        }

        $necrologie->update([
            'nom_defunt'     => $request->input('nom_defunt'),
            'date_naissance' => $request->input('date_naissance'),
            'date_deces'     => $request->input('date_deces'),
            'message'        => $request->input('message'),
            'photo'          => $necrologie->photo,
            'video'          => $necrologie->video,
        ]);

        return redirect()->route('advertiser.dashboard')
            ->with('success', 'Notice mise à jour.');
    }

    public function destroy(Necrologie $necrologie)
    {
        abort_if($necrologie->advertiser_id !== Auth::guard('advertiser')->id(), 403);

        if ($necrologie->photo && File::exists(public_path($necrologie->photo))) {
            File::delete(public_path($necrologie->photo));
        }
        if ($necrologie->video && File::exists(public_path($necrologie->video))) {
            File::delete(public_path($necrologie->video));
        }

        $necrologie->delete();

        return redirect()->route('advertiser.dashboard')
            ->with('success', 'Notice supprimée.');
    }
}
