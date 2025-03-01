<?php

namespace App\Http\Controllers\Admin\K13;

use App\Http\Controllers\Controller;
use App\Models\K13ButirSikap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_kompetensi' => 'required',
            'kode' => 'required|min:2|max:10|unique:k13_butir_sikap',
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

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|min:2|max:10|unique:k13_butir_sikap' . ($id ? ",id,$id" : ''),
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
}
