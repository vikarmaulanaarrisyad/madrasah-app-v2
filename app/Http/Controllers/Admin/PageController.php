<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index()
    {
        $menus = Menu::all();

        return view('admin.pages.index', compact('menus'));
    }

    public function data()
    {
        $query = Page::with('menu')->orderBy('created_at', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('menu', function ($q) {
                return $q->menu->menu_title ?? '';
            })
            ->addColumn('content', function ($q) {
                return Str::limit(strip_tags($q->content), 100, '...'); // Hilangkan HTML dan batasi 100 karakter
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('pages.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                <button onclick="deleteData(`' . route('pages.destroy', $q->id) . '`, `' . $q->title . '`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
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
            'menu_id' => 'required|exists:menus,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'gambar' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = $request->except('gambar');
        if ($request->hasFile('gambar')) {
            // Simpan foto baru dan perbarui data
            $data['gambar'] = upload('page', $request->file('gambar'), 'page');
        }
        $data = [
            'menu_id' => $request->menu_id,
            'slug' => Str::slug($request->title),
            'title' => $request->title,
            'content' => $request->content,
            // 'gambar' => upload('pages', $request->file('gambar'), 'pages'),
        ];

        Page::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return response()->json(['data' => $page]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $rules = [
            'menu_id' => 'required|exists:menus,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'gambar' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
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
        $data = $request->except('gambar');

        // Jika ada foto baru yang diunggah
        if ($request->hasFile('gambar')) {
            // Hapus foto lama jika ada dan tidak null
            if (!empty($page->gambar) && Storage::disk('public')->exists($page->gambar)) {
                Storage::disk('public')->delete($page->gambar);
            }

            // Simpan foto baru dan perbarui data
            $data['gambar'] = upload('page', $request->file('gambar'), 'page');
        }

        $page->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        if (Storage::disk('public')->exists($page->gambar)) {
            Storage::disk('public')->delete($page->gambar);
        }

        $page->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ]);
    }
}
