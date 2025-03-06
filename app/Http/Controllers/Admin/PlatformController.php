<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PlatformController extends Controller
{
    public function index()
    {
        return view('admin.platform.index');
    }

    public function data()
    {
        $query = Platform::all();

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('gambar', function ($q) {
                $foto = $q->gambar ? Storage::url($q->gambar) : asset('AdminLTE/dist/img/avatar3.png');
                return '<img src="' . $foto . '" class="img-thumbnail rounded-circle" width="50" height="50">';
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('platform.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                <button onclick="deleteData(`' . route('platform.destroy', $q->id) . '`, `' . $q->nama . '`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
            ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|mimes:png,jpg,jpeg|max:2048',
            'nama' => 'required',
            'url' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = [
            'nama' => $request->nama,
            'url' => $request->url,
            'gambar' => upload('platform', $request->file('gambar'), 'platform'),
        ];

        Platform::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function show($id)
    {
        $data = Platform::findOrfail($id);
        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $platform = Platform::findOrfail($id);
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|mimes:png,jpg,jpeg|max:2048',
            'nama' => 'required',
            'url' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Data yang akan diperbarui
        $data = $request->except('gambar');

        // Jika ada foto baru yang diunggah
        if ($request->hasFile('gambar')) {
            // Hapus foto lama jika ada dan tidak null
            if (!empty($platform->image) && Storage::disk('public')->exists($platform->gambar)) {
                Storage::disk('public')->delete($platform->gambar);
            }

            // Simpan foto baru dan perbarui data
            $data['gambar'] = upload('platform', $request->file('gambar'), 'platform');
        }

        $platform->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function destroy($id)
    {
        $platform = Platform::findOrfail($id);
        if (Storage::disk('public')->exists($platform->gambar)) {
            Storage::disk('public')->delete($platform->gambar);
        }

        $platform->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ]);
    }
}
