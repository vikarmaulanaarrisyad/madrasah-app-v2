<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Kurikulum;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RombelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.rombel.index');
    }

    public function data()
    {
        $tahunPelajaran = TahunPelajaran::aktif()->first();

        $query = Rombel::with('siswa_rombel', 'kelas', 'walikelas', 'kurikulum')
            ->whereHas('tahun_pelajaran', function ($q) use ($tahunPelajaran) {
                $q->where('tahun_pelajaran_id', $tahunPelajaran->id);
            })
            ->get();

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('walikelas', function ($q) {
                return $q->walikelas ? $q->walikelas->nama_lengkap : '<span class="badge badge-info">Belum ada walikelas</span>';
            })
            ->addColumn('tingkat', function ($q) {
                return $q->kelas->tingkat ?? '';
            })
            ->addColumn('walikelas', function ($q) {
                return $q->walikelas->nama_lengkap ?? '';
            })
            ->addColumn('kelas', function ($q) {
                return $q->kelas->nama;
            })
            ->addColumn('jumlahsiswa', function ($q) {
                return $q->siswa_rombel->count() ?? 0;
            })
            ->addColumn('aksi', function ($q) {
                return '
                    <a href="' . route('rombel.show', $q->id) . '" class="btn btn-sm btn-primary">DETAIL</a>
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
        $kelas = Kelas::all();
        $walikelas = Guru::all();
        $kurikulum = Kurikulum::all();

        return view('admin.rombel.create', compact('kelas', 'walikelas', 'kurikulum'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Aturan validasi untuk setiap field
        $rules = [
            'kelas_id' => 'required',  // Validasi dengan pengecekan di tabel kelas
            'kurikulum_id' => 'required',  // Validasi dengan pengecekan di tabel kurikulum
            'walikelas' => 'required',  // Validasi dengan pengecekan di tabel guru untuk wali kelas
        ];

        // Pesan kesalahan kustom
        $messages = [
            'kelas_id.required' => 'Kelas harus dipilih.',
            'kurikulum_id.required' => 'Kurikulum harus dipilih.',
            'walikelas.required' => 'Wali Kelas harus dipilih.',
        ];

        // Validasi input berdasarkan rules dan pesan kesalahan
        $validator = Validator::make($request->all(), $rules, $messages);

        // Jika validasi gagal, kembalikan respons dengan status 422 dan pesan kesalahan
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $tahunPelajaran = TahunPelajaran::aktif()->first();

        // Menyimpan data rombel ke dalam database
        $data = Rombel::create([
            'tahun_pelajaran_id' => $tahunPelajaran->id,
            'kelas_id' => $request->kelas_id,
            'kurikulum_id' => $request->kurikulum_id,
            'wali_kelas_id' => $request->walikelas,
            'nama' => $request->nama,
        ]);

        // Jika data berhasil disimpan, kirimkan response sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Data Rombel berhasil disimpan!',
            'data' => $data,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rombel = Rombel::findOrfail($id);
        $kelas = Kelas::all();
        $walikelas = Guru::all();
        $kurikulum = Kurikulum::all();
        return view('admin.rombel.show', compact('rombel', 'kelas', 'walikelas', 'kurikulum'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $rombel = Rombel::findOrfail($id);
        $kelas = Kelas::all();
        $walikelas = Guru::all();
        $kurikulum = Kurikulum::all();

        return view('admin.rombel.edit', compact('kelas', 'walikelas', 'kurikulum', 'rombel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Aturan validasi untuk setiap field
        $rules = [
            'nama' => 'required',  // Validasi dengan pengecekan di tabel guru untuk wali kelas
            'walikelas' => 'required',  // Validasi dengan pengecekan di tabel guru untuk wali kelas
        ];

        // Pesan kesalahan kustom
        $messages = [
            'walikelas.required' => 'Wali Kelas harus dipilih.',
        ];

        // Validasi input berdasarkan rules dan pesan kesalahan
        $validator = Validator::make($request->all(), $rules, $messages);

        // Jika validasi gagal, kembalikan respons dengan status 422 dan pesan kesalahan
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Menyimpan data rombel ke dalam database
        $data = [
            'wali_kelas_id' => $request->walikelas,
            'nama' => $request->nama,
        ];

        $rombel = Rombel::findOrfail($id);
        $rombel->update($data);

        // Jika data berhasil disimpan, kirimkan response sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Data Rombel berhasil disimpan!',
            'data' => $data,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getDataSiswa(Request $request)
    {
        // Dapatkan rombel dari request
        $rombel = Rombel::findOrFail($request->rombel_id);

        // Cek apakah tahun pelajaran aktif tersedia
        $tahunPelajaran = TahunPelajaran::aktif()->first();
        if (!$tahunPelajaran) {
            return response()->json(['success' => false, 'message' => 'Tahun pelajaran aktif tidak ditemukan.']);
        }

        $tahunPelajaranId = $tahunPelajaran->id;

        // Dapatkan level dari kelas yang terkait dengan rombel
        $kelasLevel = $rombel->kelas->tingkat;

        // Dapatkan siswa dengan level yang sesuai dan belum terdaftar di siswa_rombel untuk tahun pelajaran aktif
        $siswa = Siswa::where('level', $kelasLevel) // Filter berdasarkan level
            ->whereDoesntHave('siswa_rombel', function ($query) use ($tahunPelajaranId) {
                $query->where('siswa_rombel.tahun_pelajaran_id', $tahunPelajaranId); // Gunakan nama tabel secara eksplisit
            })
            ->get();

        // Kembalikan data siswa dalam format DataTables
        return datatables($siswa)
            ->addIndexColumn()
            ->addColumn('aksi', function ($siswa) {
                return '<input type="checkbox" class="select-siswa" name="siswa_id[]" value="' . $siswa->id . '">';
            })

            ->escapeColumns([]) // Prevent HTML escaping
            ->make(true);
    }

    public function addSiswa(Request $request)
    {
        // Validasi request
        $validated = $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'siswa_ids' => 'required|array',
        ]);

        // Dapatkan tahun pelajaran aktif
        $tahunPelajaranId = TahunPelajaran::aktif()->first()->id;

        if (!$tahunPelajaranId) {
            return response()->json(['success' => false, 'message' => 'No active academic year found.']);
        }

        // Temukan rombel yang dipilih
        $rombel = Rombel::findOrFail($validated['rombel_id']);

        // Menambahkan siswa yang dipilih ke rombel pada tahun pelajaran aktif
        foreach ($validated['siswa_ids'] as $siswaId) {
            // Cek jika siswa sudah terdaftar di rombel untuk tahun pelajaran aktif
            $existingEntry = $rombel->siswa_rombel()
                ->where('siswa_id', $siswaId)
                ->where('tahun_pelajaran_id', $tahunPelajaranId)
                ->exists();

            if ($existingEntry) {
                // Jika sudah ada, tolak penambahan siswa dan beri pesan error
                return response()->json(['success' => false, 'message' => "Siswa dengan ID {$siswaId} sudah terdaftar di rombel ini untuk tahun pelajaran aktif."]);
            }

            // Jika belum terdaftar, tambahkan siswa ke rombel
            $rombel->siswa_rombel()->attach($siswaId, ['tahun_pelajaran_id' => $tahunPelajaranId]);
        }

        // Dapatkan siswa yang ditambahkan
        $siswa = Siswa::whereIn('id', $validated['siswa_ids'])->get();

        return response()->json(['success' => true, 'siswa' => $siswa]);
    }

    public function getSiswaRombel($id)
    {
        $siswa = Siswa::whereHas('siswa_rombel', function ($query) use ($id) {
            $query->where('rombel_id', $id);
        })->get();

        return datatables($siswa)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                return '
                    <button type="button" onclick="hapusSiswa(' . $row->id . ')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>
                ';
            })
            ->rawColumns(['aksi']) // Pastikan HTML tidak di-escape
            ->make(true);
    }

    public function removeSiswa(Request $request)
    {
        try {
            $siswaId = $request->input('siswa_id');
            $rombelId = $request->input('rombel_id');

            // Cari siswa
            $siswa = Siswa::find($siswaId);

            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan.'
                ], 404);
            }

            // Hapus relasi siswa dari rombel
            $deleted = $siswa->siswa_rombel()->detach($rombelId);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Siswa berhasil dihapus dari rombel.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak terdaftar dalam rombel ini.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus siswa.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
