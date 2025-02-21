<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\Agama;
use App\Models\JenisKelamin;
use App\Models\Kewarganegaraan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisKelamin = JenisKelamin::all();
        $kewarganegaraan = Kewarganegaraan::all();
        $agama = Agama::all();

        return view('siswa.index', compact('jenisKelamin', 'kewarganegaraan', 'agama'));
    }

    public function data()
    {
        $query = Siswa::with('jenis_kelamin')->aktif()->orderBy('id', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('status', function ($q) {
                $icon = $q->status ? 'fa-toggle-on text-success' : 'fa-toggle-off text-danger';
                return '
                <a href="#" onclick="updateStatus(' . $q->id . ')" class="status-toggle" kodeq="' . $q->id . '">
                    <i class="fas ' . $icon . ' fa-lg"></i>
                </a>
            ';
            })
            ->addColumn('rombel', function ($q) {
                $rombel = optional($q->siswa_rombel->first());
                return ($rombel->kelas ? $rombel->kelas->nama . ' ' . $rombel->nama : '<span class="badge badge-info">Aktif tanpa rombel</span>');
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('siswa.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
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
            'nisn' => 'required|min:10|numeric',
            'nik' => 'required|min:16|numeric',
            'nis' => 'required',
            'kk' => 'required|min:16',
            'nama_lengkap' => 'required',
            'nama_panggilan' => 'required',
            'jenis_kelamin_id' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'agama_id' => 'required',
            'kewarganegaraan_id' => 'required',
            'jumlah_saudara' => 'required',
            'anakke' => 'required',
            'alamat' => 'required',
            'foto_siswa' => 'required|mimes:png, jpeg, jpg|max:3048',
        ];

        $message = [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.min' => 'NISN harus terdiri dari minimal 10 angka.',
            'nisn.numeric' => 'NISN harus berupa angka.',

            'nik.required' => 'NIK wajib diisi.',
            'nik.min' => 'NIK harus terdiri dari minimal 16 angka.',
            'nik.numeric' => 'NIK harus berupa angka.',

            'nis.required' => 'NIS wajib diisi.',

            'kk.required' => 'Nomor KK wajib diisi.',
            'kk.min' => 'Nomor KK harus terdiri dari minimal 16 angka.',

            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_panggilan.required' => 'Nama panggilan wajib diisi.',

            'jenis_kelamin_id.required' => 'Jenis kelamin wajib dipilih.',

            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',

            'agama_id.required' => 'Agama wajib dipilih.',
            'kewarganegaraan_id.required' => 'Kewarganegaraan wajib dipilih.',

            'jumlah_saudara.required' => 'Jumlah saudara wajib diisi.',
            'anakke.required' => 'Anak ke-berapa wajib diisi.',

            'alamat.required' => 'Alamat wajib diisi.',

            'foto.required' => 'Foto wajib diunggah.',
            'foto.mimes' => 'Foto harus dalam format PNG, JPEG, atau JPG.',
            'foto.max' => 'Ukuran foto tidak boleh lebih dari 3MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = $request->except('foto_siswa', 'categories');
        $data = [
            'nisn' => $request->nisn,
            'nik' => $request->nik,
            'nis' => $request->nis,
            'kk' => $request->kk,
            'nama_lengkap' => $request->nama_lengkap,
            'nama_panggilan' => $request->nama_panggilan,
            'jenis_kelamin_id' => $request->jenis_kelamin_id,
            'agama_id' => $request->agama_id,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'kewarganegaraan_id' => $request->kewarganegaraan_id,
            'jumlah_saudara' => $request->jumlah_saudara,
            'alamat' => $request->alamat,
            'foto' => upload('upload/siswa', $request->file('foto_siswa'), $request->nisn)
        ];

        Siswa::create($data);

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
        $data = Siswa::findOrfail($id);
        $data->foto = Storage::url($data->foto);

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
        $siswa = Siswa::findOrFail($id);

        $rules = [
            'nisn' => 'required|min:10|numeric',
            'nik' => 'required|min:16|numeric',
            'nis' => 'required',
            'kk' => 'required|min:16',
            'nama_lengkap' => 'required',
            'nama_panggilan' => 'required',
            'jenis_kelamin_id' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'agama_id' => 'required',
            'kewarganegaraan_id' => 'required',
            'jumlah_saudara' => 'required',
            'anakke' => 'required',
            'alamat' => 'required',
            'foto_siswa' => 'nullable|mimes:png,jpeg,jpg|max:3048', // Foto tidak wajib diupload
        ];

        $message = [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.min' => 'NISN harus terdiri dari minimal 10 angka.',
            'nisn.numeric' => 'NISN harus berupa angka.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.min' => 'NIK harus terdiri dari minimal 16 angka.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nis.required' => 'NIS wajib diisi.',
            'kk.required' => 'Nomor KK wajib diisi.',
            'kk.min' => 'Nomor KK harus terdiri dari minimal 16 angka.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_panggilan.required' => 'Nama panggilan wajib diisi.',
            'jenis_kelamin_id.required' => 'Jenis kelamin wajib dipilih.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'agama_id.required' => 'Agama wajib dipilih.',
            'kewarganegaraan_id.required' => 'Kewarganegaraan wajib dipilih.',
            'jumlah_saudara.required' => 'Jumlah saudara wajib diisi.',
            'anakke.required' => 'Anak ke-berapa wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'foto_siswa.mimes' => 'Foto harus dalam format PNG, JPEG, atau JPG.',
            'foto_siswa.max' => 'Ukuran foto tidak boleh lebih dari 3MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Data yang akan diperbarui
        $data = $request->except('foto_siswa');

        // Jika ada foto baru yang diunggah
        if ($request->hasFile('foto_siswa')) {
            // Hapus foto lama jika ada dan tidak null
            if (!empty($siswa->foto) && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }

            // Simpan foto baru dan perbarui data
            $data['foto'] = upload('upload/siswa', $request->file('foto_siswa'), $request->nisn);
        }

        // Update data siswa
        $siswa->update($data);


        // Update data siswa
        $siswa->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function importEXCEL(Request $request)
    {
        // Validasi file
        $validator = Validator::make($request->all(), [
            'excelFile' => 'required|file|mimes:xlsx,xls|max:2048', // Maks 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Proses import menggunakan Laravel Excel
            Excel::import(new SiswaImport, $request->file('excelFile'), null, \Maatwebsite\Excel\Excel::XLSX);

            return response()->json([
                'status' => 'success',
                'message' => 'File berhasil diupload dan diproses!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
