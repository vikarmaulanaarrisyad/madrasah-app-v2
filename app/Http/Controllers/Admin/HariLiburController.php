<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HariLibur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HariLiburController extends Controller
{
    public function index()
    {
        return view('admin.hari_libur.index');
    }

    public function data()
    {
        $query = HariLibur::orderBy('tanggal', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('tanggal', function ($q) {
                return tanggal_indonesia($q->tanggal, true);
            })
            ->addColumn('checkbox', function ($q) {
                return '<input type="checkbox" class="check-item" name="ids[]" value="' . $q->id . '">';
            })
            ->addColumn('aksi', function ($q) {
                return '
            <button onclick="editForm(`' . route('harilibur.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
            <button onclick="deleteData(`' . route('harilibur.destroy', $q->id) . '`, `' . $q->keterangan . '`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
            ';
            })
            ->rawColumns(['checkbox', 'aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|string',
            'keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Pisahkan tanggal awal dan akhir
        $tanggal = explode(' - ', $request->tanggal);
        $startDate = Carbon::parse($tanggal[0]); // Ubah ke format Carbon
        $endDate = Carbon::parse($tanggal[1]); // Ubah ke format Carbon

        // Simpan setiap tanggal dalam rentang
        $savedDates = [];
        while ($startDate->lte($endDate)) {
            $data = [
                'tanggal' => $startDate->format('Y-m-d'), // Simpan format YYYY-MM-DD
                'keterangan' => $request->keterangan,
            ];

            HariLibur::create($data);
            $savedDates[] = $data;

            $startDate->addDay(); // Pindah ke hari berikutnya
        }

        return response()->json([
            'message' => 'Data berhasil disimpan!',
            'data' => $savedDates
        ], 201);
    }

    public function show($id)
    {
        $data = HariLibur::findOrfail($id);

        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|string',
            'keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = [
            'tanggal' => $request->tanggal, // Simpan format YYYY-MM-DD
            'keterangan' => $request->keterangan,
        ];

        $hariLibur = HariLibur::findOrfail($id);
        $hariLibur->update($data);

        return response()->json([
            'message' => 'Data berhasil disimpan!',
        ], 201);
    }

    public function destroy($id)
    {
        $hariLibur = HariLibur::findOrfail($id);
        $hariLibur->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus',
        ], 201);
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->ids;

        if (empty($ids)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data yang dipilih untuk dihapus!'
            ], 400);
        }

        // Hapus data berdasarkan ID yang dipilih
        HariLibur::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus!'
        ]);
    }
}
