<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.event.index');
    }

    public function data()
    {
        $query = Event::orderBy('created_at', 'DESC')->get();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('waktu', function ($q) {
                return $q->waktu_mulai . ' s/d ' . $q->waktu_selesai;
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('event.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                <button onclick="deleteData(`' . route('event.destroy', $q->id) . '`, `' . $q->judul . '`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
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
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'judul' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
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
            'tanggal' => $request->tanggal,
            'slug' => Str::slug($request->judul),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'image' => upload('event', $request->file('image'), 'event'),
        ];

        Event::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return response()->json(['data' => $event]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $rules = [
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'judul' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
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
            if (!empty($event->image) && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }

            // Simpan foto baru dan perbarui data
            $data['image'] = upload('event', $request->file('image'), 'event');
        }

        $event->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if (Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ]);
    }
}
