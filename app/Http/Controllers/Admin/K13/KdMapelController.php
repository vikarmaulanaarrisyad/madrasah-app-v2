<?php

namespace App\Http\Controllers\Admin\K13;

use App\Http\Controllers\Controller;
use App\Models\K13KdMapel;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KdMapelController extends Controller
{
    public function index()
    {
        return view('admin.k13.kd.index');
    }

    public function data()
    {
        $query = K13KdMapel::with('matapelajaran', 'semester', 'kelas');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('mapel', function ($q) {
                return optional($q->matapelajaran)->nama ?? '-';
            })
            ->addColumn('tingkatan_kelas', function ($q) {
                return optional($q->kelas)->nama ?? '-';
            })
            ->addColumn('semester', function ($q) {
                return $q->semesterText();
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('k13kd.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                <button onclick="deleteData(`' . route('k13kd.destroy', $q->id) . '`, `' . $q->judul . '`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
            ';
            })
            ->rawColumns(['aksi']) // Pastikan tombol HTML tidak di-escape
            ->make(true);
    }

    public function create()
    {
        return view('admin.k13.kd.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mapel_id' => 'required',
            'tingkatan_kelas' => 'required',
            'semester' => 'required',
            'jenis_kompetensi' => 'required|array',
            'kode_kd' => 'required|array',
            'kompetensi_dasar' => 'required|array',
            'ringkasan_kompetensi' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = [];
        for ($count = 0; $count < count($request->jenis_kompetensi); $count++) {
            $data[] = [
                'mata_pelajaran_id' => $request->mapel_id,
                'tingkatan_kelas' => $request->tingkatan_kelas, // diperbaiki dari 'tingkat_kelas'
                'semester' => $request->semester,
                'jenis_kompetensi' => $request->jenis_kompetensi[$count],
                'kode_kd' => $request->kode_kd[$count],
                'kompetensi_dasar' => $request->kompetensi_dasar[$count],
                'ringkasan_kompetensi' => $request->ringkasan_kompetensi[$count],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Gunakan insert untuk menyimpan banyak data sekaligus
        K13KdMapel::insert($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function show($id)
    {
        $data = K13KdMapel::with('matapelajaran')->findOrfail($id);
        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kompetensi_dasar' => 'required|min:10|max:255',
            'ringkasan_kompetensi' => 'required|min:10|max:150',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $kd = K13KdMapel::findorfail($id);
        $data_kd = [
            'kompetensi_dasar' => $request->kompetensi_dasar,
            'ringkasan_kompetensi' => $request->ringkasan_kompetensi,
        ];

        $kd->update($data_kd);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function destroy($id)
    {
        $kd = K13KdMapel::findorfail($id);
        try {
            $kd->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something wrent wrong'
            ], 500);
        }
    }
}
