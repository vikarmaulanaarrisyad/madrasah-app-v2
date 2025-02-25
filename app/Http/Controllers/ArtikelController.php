<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all();
        return view('admin.artikel.index', compact('kategori'));
    }

    public function data()
    {
        $query = Artikel::with('kategori')->orderBy('created_at', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('kategori', function ($q) {
                return $q->kategori->nama ?? '';
            })
            ->addColumn('content', function ($q) {
                return Str::limit(strip_tags($q->content), 100, '...'); // Hilangkan HTML dan batasi 100 karakter
            })
            ->addColumn('kategori', function ($q) {
                return $q->kategori->nama ?? '';
            })
            ->editColumn('status', function ($q) {
                $icon =  ($q->status == 'publish') ? 'fa-toggle-on text-success' : 'fa-toggle-off text-danger';
                return '
                <a href="#" onclick="updateStatus(' . $q->id . ')" class="status-toggle" kodeq="' . $q->id . '">
                    <i class="fas ' . $icon . ' fa-lg"></i>
                </a>
            ';
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('artikel.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                <button onclick="deleteData(`' . route('artikel.destroy', $q->id) . '`, `' . $q->judul . '`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
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
            'tgl_publish' => 'required|date',
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'tgl_publish' => $request->tgl_publish,
            'kategori_id' => $request->kategori_id,
            'slug' => Str::slug($request->judul),
            'judul' => $request->judul,
            'content' => $request->content,
            'image' => upload('artikel', $request->file('image'), 'artikel'),
        ];

        Artikel::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Artikel $artikel)
    {
        return response()->json(['data' => $artikel]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Artikel $artikel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artikel $artikel)
    {
        $rules = [
            'tgl_publish' => 'required|date',
            'kategori_id' => 'required|exists:kategoris,id',
            'judul' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        $data = $request->except('image');

        // Jika ada foto baru yang diunggah
        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada dan tidak null
            if (!empty($artikel->image) && Storage::disk('public')->exists($artikel->image)) {
                Storage::disk('public')->delete($artikel->image);
            }

            // Simpan foto baru dan perbarui data
            $data['image'] = upload('artikel', $request->file('image'), 'artikel');
        }

        $artikel->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artikel $artikel)
    {
        if (Storage::disk('public')->exists($artikel->image)) {
            Storage::disk('public')->delete($artikel->image);
        }

        $artikel->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ]);
    }


    public function updateStatus($id)
    {
        $artikel = Artikel::findOrFail($id);

        // Toggle status antara 'publish' dan 'archived'
        $newStatus = ($artikel->status == 'publish') ? 'archived' : 'publish';

        $artikel->update(['status' => $newStatus]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status berhasil diperbarui!',
            'new_status' => $newStatus
        ]);
    }
}
