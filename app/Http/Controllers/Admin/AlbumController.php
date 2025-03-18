<?php

namespace App\Http\Controllers\Admin;

use App\Models\Album;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AlbumController extends Controller
{
    public function data()
    {
        $query = Album::all();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('deskripsi', function ($q) {
                return Str::limit(strip_tags($q->deskripsi), 100, '...'); // Hilangkan HTML dan batasi 100 karakter
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('album.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                <button onclick="deleteData(`' . route('album.destroy', $q->id) . '`, `' . $q->title . '`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
            ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function index()
    {
        return view('admin.album.index');
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = [
            'title' => $request->title,
            'deskripsi' => $request->deskripsi,
            'foto' => upload('album', $request->file('foto'), 'album'),
        ];

        Album::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function show($id)
    {
        $query = Album::findOrfail($id);

        return response()->json(['data' => $query]);
    }

    public function update(Request $request, Album $album)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Data yang akan diperbarui
        $data = $request->except('foto');

        // Jika ada foto baru yang diunggah
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada dan tidak null
            if (!empty($album->foto) && Storage::disk('public')->exists($album->foto)) {
                Storage::disk('public')->delete($album->foto);
            }

            // Simpan foto baru dan perbarui data
            $data['foto'] = upload('album', $request->file('foto'), 'album');
        }

        $album->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }
}
