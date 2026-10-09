<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\PtAsal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PtAsalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PtAsal::query()->latest();

            return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn(
                    'kode_pt',
                    fn($row) =>
                    $row->kode_pt
                        ? '<span class="font-mono text-xs text-gray-700">' . e($row->kode_pt) . '</span>'
                        : '<span class="text-gray-400">-</span>'
                )

                ->addColumn(
                    'nama_text',
                    fn($row) =>
                    '<span class="font-medium text-gray-900">' . e($row->nama) . '</span>'
                )

                ->addColumn(
                    'kota_text',
                    fn($row) =>
                    $row->kota
                        ? '<span class="text-sm text-gray-700">' . e($row->kota) . '</span>'
                        : '<span class="text-gray-400">-</span>'
                )

                ->addColumn(
                    'jenjang_badge',
                    fn($row) =>
                    $row->jenis
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">'
                        . e($row->jenis) . '</span>'
                        : '<span class="text-gray-400">-</span>'
                )

                ->addColumn('status_badge', function ($row) {
                    return $row->is_active
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>'
                        : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>';
                })

                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                    <a href="' . route('master.pt-asal.edit', $row->id) . '"
                       class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                       Edit
                    </a>
                    <button type="button"
                        class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                        data-url="' . route('master.pt-asal.destroy', $row->id) . '"
                        data-name="PT Asal ' . e($row->nama) . '">
                        Hapus
                    </button>
                </div>';
                })

                ->rawColumns(['kode_pt', 'nama_text', 'kota_text', 'jenjang_badge', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.pt-asal.index', compact('useDatatables'));
    }

    public function create()
    {
        $jenisList = ['PTN', 'PTS', 'Politeknik', 'Akademi'];
        return view('master.pt-asal.create', compact('jenisList'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:200',
            'kota' => 'nullable|string|max:100',
            'kode_pt' => 'nullable|string|max:20',
            'jenis' => 'required|in:PTN,PTS,Politeknik,Akademi',
        ]);

        if ($validator->fails()) {
            return  response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        PtAsal::create([
            'nama' => $request->nama,
            'kota' => $request->kota,
            'kode_pt' => $request->kode_pt,
            'jenis' => $request->jenis,
        ]);

        return response()->json([
            'message' => 'PT Asal berhasil ditambahkan!',
            'redirect' => route('master.pt-asal.index'),
        ]);
    }

    public function edit(PtAsal $ptAsal)
    {
        $jenisList = ['PTN', 'PTS', 'Politeknik', 'Akademi'];
        return view('master.pt-asal.edit', compact('ptAsal', 'jenisList'));
    }

    public function update(Request $request, PtAsal $ptAsal)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:200',
            'kota' => 'nullable|string|max:100',
            'kode_pt' => 'nullable|string|max:20',
            'jenis' => 'required|in:PTN,PTS,Politeknik,Akademi',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $ptAsal->update([
            'nama' => $request->nama,
            'kota' => $request->kota,
            'kode_pt' => $request->kode_pt,
            'jenis' => $request->jenis,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message' => 'PT Asal berhasil diupdate!',
            'redirect' => route('master.pt-asal.index'),
        ]);
    }

    public function destroy(PtAsal $ptAsal)
    {
        $ptAsal->delete();
        return response()->json([
            'message' => 'PT Asal berhasil dihapus!',
            'redirect' => route('master.pt-asal.index'),
        ]);
    }
}
