<?php

namespace App\Http\Controllers\Admin\K13;

use App\Http\Controllers\Controller;
use App\Models\K13KkmMapel;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Kkm13MapelController extends Controller
{
    public function index()
    {
        return view('admin.k13.kkm.index');
    }

    public function data()
    {
        $tapel = TahunPelajaran::aktif()->first();

        $query = K13KkmMapel::with('kelas.rombel.tahun_pelajaran', 'matapelajaran')
            ->where('tahun_pelajaran_id', $tapel->id)
            ->orderBy('id', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('mapel', function ($q) {
                return $q->matapelajaran->nama ?? '-';
            })
            ->addColumn('semester', function ($q) {
                // Ambil rombel pertama (jika ada) untuk mendapatkan tahun pelajaran
                $rombel = $q->kelas->rombel->first();
                return optional($rombel->tahun_pelajaran)->nama . ' ' . optional($rombel->tahun_pelajaran->semester)->nama ?? '-';
            })
            ->addColumn('kelas', function ($q) {
                return $q->kelas->nama ?? '-';
            })
            ->addColumn('kkm', function ($q) {
                return '<input type="number" class="form-control form-control-sm update-kkm"
                data-id="' . $q->id . '" value="' . $q->kkm . '">';
            })

            ->addColumn('aksi', function ($q) {
                $name = $q->matapelajaran->nama . ' ' . $q->kelas->nama;

                return '
                <button onclick="deleteData(`' . route('k13kkm.destroy', $q->id) . '`, `' . $name . '`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
            ';
            })
            ->escapeColumns([])
            ->make(true);
    }


    public function store(Request $request)
    {
        $tapel = TahunPelajaran::aktif()->first();

        $rules = [
            'kelas_id' => 'required',
            'mata_pelajaran_id' => 'required',
            'kkm' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Cek apakah data dengan kelas_id & mata_pelajaran_id sudah ada
        $exists = K13KkmMapel::where('kelas_id', $request->kelas_id)
            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
            ->where('tahun_pelajaran_id', $tapel->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data dengan Kelas dan Mata Pelajaran ini sudah ada.',
            ], 422); // HTTP 409 Conflict
        }

        // Simpan data jika belum ada
        K13KkmMapel::create([
            'tahun_pelajaran_id' => $tapel->id,
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kkm' => $request->kkm
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function show($id)
    {
        $data = K13KkmMapel::with('kelas.rombel.tahun_pelajaran', 'matapelajaran')
            ->find($id);

        if (!$data) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $data
        ]);
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'kelas_id' => 'required',
            'mata_pelajaran_id' => 'required',
            'kkm' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $kkm = K13KkmMapel::find($id);

        if (!$kkm) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        // Cek apakah data dengan kelas_id & mata_pelajaran_id sudah ada (kecuali data yang sedang diedit)
        $exists = K13KkmMapel::where('kelas_id', $request->kelas_id)
            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
            ->where('id', '!=', $id) // Pastikan tidak mengecek data yang sedang diedit
            ->exists();

        if ($exists) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data dengan Kelas dan Mata Pelajaran ini sudah ada.',
            ], 422);
        }

        // Update data
        $kkm->update([
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kkm' => $request->kkm
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui'
        ], 200);
    }

    public function destroy($id)
    {
        $kkm = K13KkmMapel::find($id);

        if (!$kkm) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        $kkm->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ], 200);
    }

    public function updateKkm(Request $request, $id)
    {
        $request->validate([
            'kkm' => 'required|numeric|min:0',
        ]);

        $kkm = K13KkmMapel::findOrFail($id);
        $kkm->update(['kkm' => $request->kkm]);

        return response()->json(['message' => 'Nilai KKM berhasil diperbarui']);
    }
}
