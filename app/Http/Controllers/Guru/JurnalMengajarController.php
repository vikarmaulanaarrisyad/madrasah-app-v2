<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JurnalGuru;
use App\Models\MataPelajaran;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JurnalMengajarController extends Controller
{
    public function index()
    {
        $tapel = TahunPelajaran::aktif()->first();
        $userId = Auth::user()->id;
        $guru = Guru::where('user_id', $userId)->first();
        $rombel = Rombel::with('tahun_pelajaran', 'kelas')->where('tahun_pelajaran_id', $tapel->id)->get();

        return view('guru.jurnal_mengajar.index', compact('rombel'));
    }

    public function data(Request $request)
    {
        $query = JurnalGuru::with('mata_pelajaran', 'rombel', 'tahun_pelajaran')->when($request->has('tanggal') && $request->tanggal != "", function ($q) use ($request) {
            $q->where('tanggal', $request->tanggal);
        })
            ->orderBy('tanggal', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('mapel', function ($q) {
                return $q->mata_pelajaran->nama;
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('jurnalmengajar.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                <button onclick="deleteData(`' . route('jurnalmengajar.destroy', $q->id) . '`, `' . $q->mata_pelajaran->nama . '`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'rombel_id' => 'required',
            'mata_pelajaran_id' => 'required',
            'tujuan_pembelajaran' => 'required',
            'materi' => 'required',
            'penilaian' => 'required',
            'metode_pembelajaran' => 'required',
            'evaluasi' => 'required',
            'refleksi' => 'required',
            'tugas' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $tapel = TahunPelajaran::aktif()->pluck('id')->first();
        $userId = Auth::user()->id;
        $guruId = Guru::where('user_id', $userId)->pluck('id')->first();

        // Ambil pembelajaran terakhir berdasarkan rombel dan mata pelajaran
        $lastPembelajaran = JurnalGuru::where('rombel_id', $request->rombel_id)
            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
            ->where('guru_id', $guruId)
            ->orderBy('id', 'desc')
            ->value('pembelajaran_ke'); // Menggunakan value() agar langsung mendapatkan angka

        // Jika data ada, tambahkan 1, jika tidak ada, mulai dari 1
        $pembelajaranKe = $lastPembelajaran ? $lastPembelajaran + 1 : 1;

        $data = [
            'tahun_pelajaran_id' => $tapel,
            'rombel_id' => $request->rombel_id,
            'guru_id' => $guruId,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'tanggal' => $request->tanggal,
            'pembelajaran_ke' => $pembelajaranKe,
            'tema' => 'Null',
            'tujuan_pembelajaran' => $request->tujuan_pembelajaran,
            'materi' => $request->materi,
            'penilaian' => $request->penilaian,
            'metode_pembelajaran' => $request->metode_pembelajaran,
            'evaluasi' => $request->evaluasi,
            'refleksi' => $request->refleksi,
            'tugas' => $request->tugas,
        ];

        JurnalGuru::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function show($id)
    {
        $data = JurnalGuru::with('mata_pelajaran', 'tahun_pelajaran', 'rombel')->findOrfail($id);

        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'rombel_id' => 'required',
            'mata_pelajaran_id' => 'required',
            'tujuan_pembelajaran' => 'required',
            'materi' => 'required',
            'penilaian' => 'required',
            'metode_pembelajaran' => 'required',
            'evaluasi' => 'required',
            'refleksi' => 'required',
            'tugas' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $tapel = TahunPelajaran::aktif()->pluck('id')->first();
        $userId = Auth::user()->id;
        $guruId = Guru::where('user_id', $userId)->pluck('id')->first();

        $data = [
            'tahun_pelajaran_id' => $tapel,
            'rombel_id' => $request->rombel_id,
            'guru_id' => $guruId,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'tanggal' => $request->tanggal,
            'tema' => 'Null',
            'tujuan_pembelajaran' => $request->tujuan_pembelajaran,
            'materi' => $request->materi,
            'penilaian' => $request->penilaian,
            'metode_pembelajaran' => $request->metode_pembelajaran,
            'evaluasi' => $request->evaluasi,
            'refleksi' => $request->refleksi,
            'tugas' => $request->tugas,
        ];

        $jurnalGuru = JurnalGuru::findOrfail($id);
        $jurnalGuru->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function destroy($id)
    {
        $data = JurnalGuru::findOrfail($id);
        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ], 201);
    }

    public function getMataPelajaran($rombel_id)
    {
        $rombel = Rombel::where('id', $rombel_id)->first();
        $mataPelajaran = MataPelajaran::where('kurikulum_id', $rombel->kurikulum_id)->get();

        return response()->json([
            'success' => true,
            'data' => $mataPelajaran
        ]);
    }
}
