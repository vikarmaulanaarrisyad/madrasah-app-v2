<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\MataPelajaran;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;

class JadwalPelajaranController extends Controller
{
    public function index(Request $request)
    {
        // Ambil ID tahun pelajaran yang aktif
        $tapelId = TahunPelajaran::aktif()->pluck('id')->first();

        // Ambil daftar rombel berdasarkan tahun pelajaran yang aktif
        $rombels = Rombel::where('tahun_pelajaran_id', $tapelId)->get();

        // Ambil rombel berdasarkan request, atau gunakan rombel pertama jika tidak ada
        $rombelId = $request->rombel_id ?? $rombels->first()->id;
        $rombel = Rombel::find($rombelId);

        // Daftar hari dalam seminggu
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // Ambil semua jam pelajaran
        $jamPelajarans = JamPelajaran::all();

        // Ambil mata pelajaran berdasarkan kurikulum dari rombel yang dipilih
        $mataPelajarans = MataPelajaran::where('kurikulum_id', optional($rombel)->kurikulum_id)->orderBy('nama', 'ASC')->get();

        // Ambil jadwal pelajaran berdasarkan rombel yang dipilih dan dikelompokkan berdasarkan jam pelajaran
        $jadwalPelajaran = JadwalPelajaran::where('rombel_id', $rombelId)
            ->orderBy('jam_pelajaran_id')
            ->get()
            ->groupBy('jam_pelajaran_id');

        // Return view dengan data yang telah dikumpulkan
        return view('admin.jadwal_pelajaran.index', compact(
            'jadwalPelajaran',
            'days',
            'rombels',
            'rombelId',
            'mataPelajarans',
            'jamPelajarans'
        ));
    }

    public function data()
    {
        $query = JadwalPelajaran::all();

        return datatables($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rombel_id' => 'required',
            'jam_pelajaran_id' => 'required|integer',
            'mata_pelajaran_id' => 'required|integer',
            'day' => 'required|string',
        ]);

        JadwalPelajaran::updateOrCreate(
            [
                'rombel_id' => $request->rombel_id,
                'jam_pelajaran_id' => $request->jam_pelajaran_id,
                'hari' => $request->day,
            ],
            ['mata_pelajaran_id' => $request->mata_pelajaran_id]
        );

        return response()->json(['success' => 'Jadwal berhasil diperbarui!']);
    }
}
