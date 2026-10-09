<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class RuangController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Ruang::query()->latest();

            return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn(
                    'kode',
                    fn($row) =>
                    '<span class="font-mono text-xs text-gray-700">' . e($row->kode) . '</span>'
                )

                ->addColumn(
                    'gedung_text',
                    fn($row) =>
                    $row->gedung
                        ? '<span class="text-sm text-gray-700">' . e($row->gedung) . '</span>'
                        : '<span class="text-gray-400">-</span>'
                )

                ->addColumn(
                    'jenis_badge',
                    fn($row) =>
                    '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">'
                        . e($row->jenis_label) . '</span>'
                )

                ->addColumn(
                    'kapasitas_text',
                    fn($row) =>
                    '<span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-gray-100 text-gray-800">'
                        . e($row->kapasitas) . ' orang</span>'
                )

                ->addColumn('status_badge', function ($row) {
                    return $row->is_active
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>'
                        : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>';
                })

                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                    <a href="' . route('master.ruang.edit', $row->id) . '"
                       class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                       Edit
                    </a>
                    <button type="button"
                        class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                        data-url="' . route('master.ruang.destroy', $row->id) . '"
                        data-name="Ruang ' . e($row->nama) . '">
                        Hapus
                    </button>
                </div>';
                })

                ->rawColumns(['kode', 'gedung_text', 'jenis_badge', 'kapasitas_text', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.ruang.index', compact('useDatatables'));
    }

    public function create()
    {
        return view('master.ruang.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:ruang,kode',
            'nama' => 'required|string|max:100',
            'gedung' => 'nullable|string|max:50',
            'kapasitas' => 'required|integer|min:1|max:500',
            'jenis' => 'required|in:kelas,lab,auditorium,ruang_seminar',
            'fasilitas' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        Ruang::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'gedung' => $request->gedung,
            'kapasitas' => $request->kapasitas,
            'jenis' => $request->jenis,
            'fasilitas' => $request->fasilitas,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message' => 'Ruang berhasil ditambahkan!',
            'redirect' => route('master.ruang.index')
        ]);
    }

    public function edit(Ruang $ruang)
    {
        return view('master.ruang.edit', compact('ruang'));
    }

    public function update(Request $request, Ruang $ruang)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:ruang,kode,' . $ruang->id,
            'nama' => 'required|string|max:100',
            'gedung' => 'nullable|string|max:50',
            'kapasitas' => 'required|integer|min:1|max:500',
            'jenis' => 'required|in:kelas,lab,auditorium,ruang_seminar',
            'fasilitas' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $ruang->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'gedung' => $request->gedung,
            'kapasitas' => $request->kapasitas,
            'jenis' => $request->jenis,
            'fasilitas' => $request->fasilitas,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message' => 'Ruang berhasil diupdate!',
            'redirect' => route('master.ruang.index'),
        ]);
    }

    public function destroy(Ruang $ruang)
    {
        $ruang->delete();
        return response()->json([
            'message' => 'Ruang berhasil dihapus!',
            'redirect' => route('master.ruang.index'),
        ]);
    }
}
