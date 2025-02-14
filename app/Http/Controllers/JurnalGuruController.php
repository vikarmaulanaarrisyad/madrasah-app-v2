<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JurnalGuru;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JurnalGuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::with('rombel')->get();
        $guru = Guru::all();
        return view('jurnal.index', compact('kelas', 'guru'));
    }

    public function data(Request $request)
    {
        $query = JurnalGuru::with('guru', 'kelas', 'mata_pelajaran')
            ->when($request->has('guru') && $request->guru != "", function ($query) use ($request) {
                return $query->where('guru_id', $request->guru);
            })
            ->when(
                $request->has('startDate') && $request->startDate != "" && $request->has('endDate') && $request->endDate != "",
                function ($query) use ($request) {
                    return $query->whereBetween('tanggal', [$request->startDate, $request->endDate]);
                }
            )
            ->when($request->has('kelas') && $request->kelas != "", function ($query) use ($request) {
                return $query->whereHas('kelas.rombel', function ($q) use ($request) {
                    $q->where('kelas_id', $request->kelas);
                });
            })
            ->orderBy('tanggal', 'DESC');

        return datatables()->eloquent($query)
            ->addIndexColumn()
            ->editColumn('guru', function ($q) {
                return $q->guru->nama_lengkap ?? '-';
            })
            ->escapeColumns([])
            ->make(true);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
