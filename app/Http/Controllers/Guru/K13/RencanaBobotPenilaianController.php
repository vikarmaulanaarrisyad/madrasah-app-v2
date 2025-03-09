<?php

namespace App\Http\Controllers\Guru\K13;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\K13RencanaBobotPenilaian;
use App\Models\Pembelajaran;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RencanaBobotPenilaianController extends Controller
{
    public function index()
    {
        return view('guru.k13.bobotnilai.index');
    }

    public function data()
    {
        // Ambil tahun pelajaran aktif
        $tapelId = TahunPelajaran::aktif()->pluck('id')->first();
        if (!$tapelId) {
            return response()->json(['error' => 'Tahun Pelajaran Aktif tidak ditemukan.'], 400);
        }

        // Ambil data guru berdasarkan user yang login
        $guru = Guru::where('user_id', Auth::id())->first();
        if (!$guru) {
            return response()->json(['error' => 'Data Guru tidak ditemukan.'], 400);
        }

        // Ambil daftar rombel pada tahun pelajaran aktif
        $rombelId = Rombel::where('tahun_pelajaran_id', $tapelId)->pluck('id');

        // Ambil data rencana bobot nilai untuk guru tertentu
        $dataRencanaBobotNilai = Pembelajaran::where('guru_id', $guru->id)
            ->whereIn('rombel_id', $rombelId)
            ->where('status', 1)
            ->orderBy('mata_pelajaran_id', 'ASC')
            ->orderBy('rombel_id', 'ASC')
            ->get();

        // Tambahkan bobot nilai ke setiap data rencana pembelajaran
        foreach ($dataRencanaBobotNilai as $penilaian) {
            $bobot = K13RencanaBobotPenilaian::where('pembelajaran_id', $penilaian->id)->first();
            $penilaian->bobot_ph = $bobot->bobot_ph ?? 0;
            $penilaian->bobot_pts = $bobot->bobot_pts ?? 0;
            $penilaian->bobot_pas = $bobot->bobot_pas ?? 0;
        }

        return datatables($dataRencanaBobotNilai)
            ->addIndexColumn()
            ->editColumn('mata_pelajaran', function ($q) {
                return $q->mata_pelajaran ? $q->mata_pelajaran->nama : '';
            })
            ->editColumn('rombel', function ($q) {
                return $q->rombel ? $q->rombel->kelas->nama . ' ' . $q->rombel->nama : '';
            })
            ->editColumn('bobot_ph', function ($q) {
                return $q->bobot_ph == 0
                    ? '<span class="badge badge-danger">0</span>'
                    : '<span class="badge badge-success">' . $q->bobot_ph . '</span>';
            })
            ->editColumn('bobot_pts', function ($q) {
                return $q->bobot_pts == 0
                    ? '<span class="badge badge-danger">0</span>'
                    : '<span class="badge badge-success">' . $q->bobot_pts . '</span>';
            })
            ->editColumn('bobot_pas', function ($q) {
                return $q->bobot_pas == 0
                    ? '<span class="badge badge-danger">0</span>'
                    : '<span class="badge badge-success">' . $q->bobot_pas . '</span>';
            })
            ->addColumn('aksi', function ($q) {
                $aksi = '';
                if ($q->bobot_ph == 0) {
                    $aksi .= '
                    <button onclick="addForm(`' . route('bobotnilai.store') . '`, ' . $q->id . ')"
                            class="btn btn-sm btn-primary" title="Tambah">
                        <i class="fas fa-plus"></i>
                    </button>';
                } else {
                    $aksi .= '
                    <button onclick="editForm(`' . route('bobotnilai.show', $q->id) . '`)"
                            class="btn btn-sm btn-warning" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>';
                }

                return $aksi;
            })
            ->rawColumns(['bobot_ph', 'bobot_pts', 'bobot_pas', 'aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pembelajaran_id' => 'required',
            'bobot_ph' => 'required|numeric|between:1,5',
            'bobot_pts' => 'required|numeric|between:1,5',
            'bobot_pas' => 'required|numeric|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $bobot = new K13RencanaBobotPenilaian([
            'pembelajaran_id' => $request->pembelajaran_id,
            'bobot_ph' => $request->bobot_ph,
            'bobot_pts' => $request->bobot_pts,
            'bobot_pas' => $request->bobot_pas,
        ]);
        $bobot->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function show($id)
    {
        $bobot = K13RencanaBobotPenilaian::where('pembelajaran_id', $id)->first();

        if (!$bobot) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($bobot);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bobot_ph' => 'required|numeric',
            'bobot_pts' => 'required|numeric',
            'bobot_pas' => 'required|numeric',
        ]);

        K13RencanaBobotPenilaian::updateOrCreate(
            ['pembelajaran_id' => $id],
            [
                'bobot_ph' => $request->bobot_ph,
                'bobot_pts' => $request->bobot_pts,
                'bobot_pas' => $request->bobot_pas
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }
}
