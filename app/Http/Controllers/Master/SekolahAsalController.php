<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\SekolahAsal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SekolahAsalController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SekolahAsal::query()->latest();

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn(
                    'nama_text',
                    fn($row) =>
                    '<span class="font-medium text-gray-900">' . e($row->nama) . '</span>'
                )

                ->addColumn(
                    'jenjang_badge',
                    fn($row) =>
                    $row->jenjang
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">'
                        . e($row->jenjang) . '</span>'
                        : '<span class="text-gray-400">-</span>'
                )

                ->addColumn(
                    'kota_text',
                    fn($row) =>
                    $row->kota
                        ? '<span class="text-sm text-gray-700">' . e($row->kota) . '</span>'
                        : '<span class="text-gray-400">-</span>'
                )

                ->addColumn('status_badge', function ($row) {
                    return $row->is_active
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>'
                        : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>';
                })

                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                    <a href="' . route('master.sekolah-asal.edit', $row->id) . '"
                       class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                       Edit
                    </a>
                    <button type="button"
                        class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                        data-url="' . route('master.sekolah-asal.destroy', $row->id) . '"
                        data-name="Sekolah ' . e($row->nama) . '">
                        Hapus
                    </button>
                </div>';
                })

                ->rawColumns(['kode', 'nama_text', 'jenjang_badge', 'kota_text', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.sekolah-asal.index', compact('useDatatables'));
    }

    public function create()
    {
        $jenjangList = ['SMA', 'SMK', 'MA', 'Sederajat'];
        return view('master.sekolah-asal.create', compact('jenjangList'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:200',
            'kota' => 'nullable|string|max:100',
            'jenjang' => 'required|in:SMA,SMK,MA,Sederajat',
            'npsn' => 'nullable|string|max:20|unique:sekolah_asal,npsn',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        SekolahAsal::create([
            'nama' => $request->nama,
            'kota' => $request->kota,
            'jenjang' => $request->jenjang,
            'npsn' => $request->npsn,
            'alamat' => $request->alamat,
        ]);


        return response()->json([
            'message' => 'Sekolah Asal berhasil ditambahkan!',
            'redirect' => route('master.sekolah-asal.index')
        ]);
    }

    public function edit(SekolahAsal $sekolahAsal)
    {
        $jenjangList = ['SMA', 'SMK', 'MA', 'Sederajat'];
        return view('master.sekolah-asal.edit', compact('sekolahAsal', 'jenjangList'));
    }

    public function update(Request $request, SekolahAsal $sekolahAsal)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:200',
            'kota' => 'nullable|string|max:100',
            'jenjang' => 'required|in:SMA,SMK,MA,Sederajat',
            'npsn' => 'nullable|string|max:20|unique:sekolah_asal,npsn,' . $sekolahAsal->id,
            'alamat' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $sekolahAsal->update([
            'nama' => $request->nama,
            'kota' => $request->kota,
            'jenjang' => $request->jenjang,
            'npsn' => $request->npsn,
            'alamat' => $request->alamat,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message' => 'Sekolah Asal berhasil diupdate!',
            'redirect' => route('master.sekolah-asal.index'),
        ]);
    }

    public function destroy(SekolahAsal $sekolahAsal)
    {
        $sekolahAsal->delete();
        return response()->json([
            'message' => 'Sekolah Asal berhasil dihapus!',
            'redirect' => route('master.sekolah-asal.index'),
        ]);
    }
}
