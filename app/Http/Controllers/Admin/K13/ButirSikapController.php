<?php

namespace App\Http\Controllers\Admin\K13;

use App\Exports\K13ButirSikapExport;
use App\Http\Controllers\Controller;
use App\Imports\K13ButirSikapImport;
use App\Models\K13ButirSikap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ButirSikapController extends Controller
{
    public function index()
    {
        return view('admin.k13.butir_sikap.index');
    }

    public function data()
    {
        $query = K13ButirSikap::orderBy('jenis_kompetensi', 'ASC')->get();

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('jenis_kompetensi', function ($q) {
                return $q->jenis_kompetensi == '1' ? 'Spiritual' : 'Sosial';
            })
            ->addColumn('aksi', function ($q) {
                return '
                     <button onclick="editForm(`' . route('k13sikap.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_kompetensi' => 'required',
            'kode' => 'required|min:2|max:10|unique:k13_butir_sikaps',
            'butir_sikap' => 'required|min:4|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $sikap = new K13ButirSikap([
            'jenis_kompetensi' => $request->jenis_kompetensi,
            'kode' => $request->kode,
            'butir_sikap' => $request->butir_sikap,
        ]);

        $sikap->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function show($id)
    {
        $query = K13ButirSikap::findOrfail($id);
        return response()->json(['data' => $query]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|min:2|max:10|unique:k13_butir_sikaps' . ($id ? ",id,$id" : ''),
            'butir_sikap' => 'required|min:4|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        try {
            $sikap = K13ButirSikap::findorfail($id);
            $data_sikap = [
                'kode' => $request->kode,
                'butir_sikap' => $request->butir_sikap,
            ];
            $sikap->update($data_sikap);
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ], 201);
        } catch (\Throwable $th) {
            // return back()->with('toast_error', 'kode sudah ada sebelumnya');
            return response()->json([
                'status'  => 'error',
                'message' => 'Kode sudah ada sebelumnya.',
            ], 422);
        }
    }

    public function import(Request $request)
    {
        // Validasi file
        $validator = Validator::make($request->all(), [
            'excelFile' => 'required|file|mimes:xlsx,xls,csv|max:2048', // Maks 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            Excel::import(new K13ButirSikapImport, $request->file('excelFile'));

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

    public function export()
    {
        $fileName = 'butir_sikap_' . now()->format('Ymdhis') . '.xlsx';
        return Excel::download(new K13ButirSikapExport, $fileName);
    }
}
