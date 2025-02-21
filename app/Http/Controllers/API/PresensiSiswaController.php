<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiSiswaController extends Controller
{
    public function index()
    {
        return response()->json(AbsensiSiswa::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'required|string|in:hadir,izin,sakit,alfa',
        ]);

        $presensi = AbsensiSiswa::create([
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Presensi berhasil dicatat', 'presensi' => $presensi], 201);
    }

    public function update(Request $request, $id)
    {
        $presensi = AbsensiSiswa::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'required|string|in:hadir,izin,sakit,alfa',
        ]);

        $presensi->update($request->all());

        return response()->json(['message' => 'Presensi berhasil diperbarui', 'presensi' => $presensi]);
    }

    public function destroy($id)
    {
        $presensi = AbsensiSiswa::findOrFail($id);
        $presensi->delete();

        return response()->json(['message' => 'Presensi berhasil dihapus']);
    }
}
