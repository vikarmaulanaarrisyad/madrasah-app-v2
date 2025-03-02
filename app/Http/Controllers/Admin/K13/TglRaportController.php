<?php

namespace App\Http\Controllers\Admin\K13;

use App\Http\Controllers\Controller;
use App\Models\K13TglRaport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TglRaportController extends Controller
{
    public function index()
    {
        return view('admin.k13.tgl_raport.index');
    }

    public function data()
    {
        $query = K13TglRaport::with('tahun_pelajaran')->orderBy('id', 'desc');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('tapel', function ($q) {
                return $q->tahun_pelajaran->nama . ' ' . $q->tahun_pelajaran->semester->nama;
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('k13tglraport.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                <button onclick="deleteData(`' . route('k13tglraport.destroy', $q->id) . '`, `Tanggal Raport`)" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
            ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun_pelajaran_id' => 'required|unique:k13_tgl_raports',
            'tempat_penerbitan' => 'required|min:3|max:50',
            'tanggal_pembagian' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $tgl_raport = new K13TglRaport([
            'tahun_pelajaran_id' => $request->tahun_pelajaran_id,
            'tempat_penerbitan' => $request->tempat_penerbitan,
            'tanggal_pembagian' => $request->tanggal_pembagian,
        ]);
        $tgl_raport->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function show($id)
    {
        $data = K13TglRaport::findOrfail($id);
        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tempat_penerbitan' => 'required|min:3|max:50',
            'tanggal_pembagian' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $tgl_raport = K13TglRaport::findorfail($id);
        $data_tgl_raport = [
            'tempat_penerbitan' => $request->tempat_penerbitan,
            'tanggal_pembagian' => $request->tanggal_pembagian,
        ];

        $tgl_raport->update($data_tgl_raport);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    public function destroy($id)
    {
        $tgl_raport = K13TglRaport::findorfail($id);
        try {
            $tgl_raport->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }
}
