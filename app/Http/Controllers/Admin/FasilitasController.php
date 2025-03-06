<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FasilitasController extends Controller
{
    public function index()
    {
        return view('admin.fasilitas.index');
    }

    public function data()
    {
        $query = Fasilitas::all();

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('gambar', function ($q) {
                $foto = $q->gambar ? Storage::url($q->gambar) : asset('AdminLTE/dist/img/avatar3.png');
                return '<img src="' . $foto . '" class="img-thumbnail rounded-circle" width="50" height="50">';
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('fasilitas.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                <button onclick="deleteData(`' . route('fasilitas.destroy', $q->id) . '`, `' . $q->nama . '`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
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
            'short' => 'required|max:150',
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
            'short' => $request->short,
            'gambar' => upload('fasilitas', $request->file('gambar'), 'fasilitas'),
        ];

        Fasilitas::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function show($id)
    {
        $data = Fasilitas::findOrfail($id);
        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $fasilitas = Fasilitas::findOrfail($id);
        $validator = Validator::make($request->all(), [
            'gambar' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'nama' => 'required',
            'short' => 'required|max:150',
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
            if (!empty($fasilitas->gambar) && Storage::disk('public')->exists($fasilitas->gambar)) {
                Storage::disk('public')->delete($fasilitas->gambar);
            }

            // Simpan foto baru dan perbarui data
            $data['gambar'] = upload('fasilitas', $request->file('gambar'), 'fasilitas');
        }

        $fasilitas->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function destroy($id)
    {
        $fasilitas = Fasilitas::findOrfail($id);
        if (Storage::disk('public')->exists($fasilitas->gambar)) {
            Storage::disk('public')->delete($fasilitas->gambar);
        }

        $fasilitas->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ]);
    }
}
