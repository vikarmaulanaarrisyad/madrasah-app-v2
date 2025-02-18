<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AplikasiController extends Controller
{
    public function index()
    {
        $aplikasi = Aplikasi::first();

        return view('pengaturan.aplikasi.index', compact('aplikasi'));
    }

    public function update(Request $request, $id)
    {
        $aplikasi = Aplikasi::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            if (Storage::disk('public')->exists($aplikasi->logo_login)) {
                Storage::disk('public')->delete($aplikasi->logo_login);
            }

            // Store the file with a unique name and store the original name if necessary
            $data['logo_login'] = upload('aplikasi', $request->file('logo'), 'aplikasi');
        }

        // Update the other fields with the validated data
        $aplikasi->update($data);

        return response()->json(['success' => true]);
    }
}
