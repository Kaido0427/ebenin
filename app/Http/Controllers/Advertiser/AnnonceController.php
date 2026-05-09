<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AnnonceController extends Controller
{
    public function create()
    {
        return view('advertiser.annonces.create', ['categories' => Annonce::CATEGORIES]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'category'      => 'required|in:' . implode(',', array_keys(Annonce::CATEGORIES)),
            'price'         => 'nullable|numeric|min:0',
            'location'      => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $advertiser = Auth::guard('advertiser')->user();
        $images = [];

        if ($request->hasFile('images')) {
            $dest = public_path('uploads/advertisers/annonces');
            File::ensureDirectoryExists($dest);
            foreach ($request->file('images') as $img) {
                $filename = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move($dest, $filename);
                $images[] = 'uploads/advertisers/annonces/' . $filename;
            }
        }

        $isAdmin = $advertiser->is_admin;

        $annonce = Annonce::create([
            'advertiser_id'  => $advertiser->id,
            'title'          => $request->input('title'),
            'description'    => $request->input('description'),
            'category'       => $request->input('category'),
            'price'          => $request->input('price'),
            'location'       => $request->input('location'),
            'contact_phone'  => $request->input('contact_phone'),
            'contact_email'  => $request->input('contact_email'),
            'images'         => $images ?: null,
            'status'         => $isAdmin ? 'active' : 'pending',
            'payment_status' => $isAdmin ? 'paid'   : 'pending',
        ]);

        if ($isAdmin) {
            return redirect()->route('advertiser.dashboard')
                ->with('success', 'Annonce publiée avec succès.');
        }

        return redirect()->route('advertiser.annonces.pay', $annonce)
            ->with('info', 'Annonce enregistrée. Finalisez le paiement pour la publier.');
    }

    public function pay(Annonce $annonce)
    {
        abort_if($annonce->advertiser_id !== Auth::guard('advertiser')->id(), 403);
        abort_if($annonce->payment_status === 'paid', 302, route('advertiser.dashboard'));

        return view('advertiser.annonces.pay', compact('annonce'));
    }

    public function paymentCallback(Request $request, Annonce $annonce)
    {
        abort_if($annonce->advertiser_id !== Auth::guard('advertiser')->id(), 403);

        $transactionId = $request->input('transactionId');

        if ($transactionId) {
            $annonce->update([
                'status'         => 'active',
                'payment_status' => 'paid',
                'payment_ref'    => $transactionId,
            ]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 422);
    }

    public function edit(Annonce $annonce)
    {
        abort_if($annonce->advertiser_id !== Auth::guard('advertiser')->id(), 403);
        return view('advertiser.annonces.edit', ['annonce' => $annonce, 'categories' => Annonce::CATEGORIES]);
    }

    public function update(Request $request, Annonce $annonce)
    {
        abort_if($annonce->advertiser_id !== Auth::guard('advertiser')->id(), 403);

        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'category'      => 'required|in:' . implode(',', array_keys(Annonce::CATEGORIES)),
            'price'         => 'nullable|numeric|min:0',
            'location'      => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $images = $annonce->images ?? [];

        if ($request->hasFile('images')) {
            $dest = public_path('uploads/advertisers/annonces');
            File::ensureDirectoryExists($dest);
            foreach ($request->file('images') as $img) {
                $filename = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move($dest, $filename);
                $images[] = 'uploads/advertisers/annonces/' . $filename;
            }
        }

        $annonce->update([
            'title'         => $request->input('title'),
            'description'   => $request->input('description'),
            'category'      => $request->input('category'),
            'price'         => $request->input('price'),
            'location'      => $request->input('location'),
            'contact_phone' => $request->input('contact_phone'),
            'contact_email' => $request->input('contact_email'),
            'images'        => $images ?: null,
        ]);

        return redirect()->route('advertiser.dashboard')
            ->with('success', 'Annonce mise à jour.');
    }

    public function destroy(Annonce $annonce)
    {
        abort_if($annonce->advertiser_id !== Auth::guard('advertiser')->id(), 403);

        if ($annonce->images) {
            foreach ($annonce->images as $img) {
                $path = public_path($img);
                if (File::exists($path)) File::delete($path);
            }
        }

        $annonce->delete();

        return redirect()->route('advertiser.dashboard')
            ->with('success', 'Annonce supprimée.');
    }
}
