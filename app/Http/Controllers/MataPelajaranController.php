<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kurikulums = Kurikulum::all();
        return view('matapelajaran.index', compact('kurikulums'));
    }

    public function data(Request $request)
    {
        $query = MataPelajaran::when($request->has('filterkurikulum') && $request->filterkurikulum != "", function ($q) use ($request) {
            $q->where('kurikulum_id', $request->filterkurikulum);
        })->with('kurikulum')->orderBy('nama', 'asc');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('matapelajaran.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
            ';
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
        $rules = [
            'kode' => 'required|min:1',
            'nama' => 'required|min:1',
            'kurikulum_id' => 'required',
            'kelompok' => 'required',
        ];

        $messages = [
            'nama.required' => 'Mata pelajaran tidak boleh kosong.',
            'nama.min' => 'Mata pelajaran harus memiliki minimal 1 karakter.',
            'semester_id.required' => 'Semester wajib dipilih.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = [
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kelompok' => $request->kelompok,
            'kurikulum_id' => $request->kurikulum_id,
        ];

        MataPelajaran::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = MataPelajaran::findOrfail($id);

        return response()->json(['data' => $data]);
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
        $rules = [
            'kode' => 'required|min:1',
            'nama' => 'required|min:1',
            'kurikulum_id' => 'required',
            'kelompok' => 'required',
        ];

        $messages = [
            'nama.required' => 'Mata pelajaran tidak boleh kosong.',
            'nama.min' => 'Mata pelajaran harus memiliki minimal 1 karakter.',
            'semester_id.required' => 'Semester wajib dipilih.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = [
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kelompok' => $request->kelompok,
            'kurikulum_id' => $request->kurikulum_id,
        ];

        $query = MataPelajaran::findOrfail($id);
        $query->update($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
