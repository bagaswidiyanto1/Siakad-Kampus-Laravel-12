<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KategoriBukuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = KategoriBuku::query()->latest();

            return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn(
                    'kode',
                    fn($row) =>
                    $row->kode
                        ? '<span class="font-mono text-xs text-gray-700">' . e($row->kode) . '</span>'
                        : '<span class="text-gray-400">-</span>'
                )

                ->addColumn(
                    'nama_text',
                    fn($row) =>
                    '<span class="font-medium text-gray-900">' . e($row->nama) . '</span>'
                )

                ->addColumn('deskripsi_text', function ($row) {
                    if (!$row->deskripsi) {
                        return '<span class="text-gray-400">-</span>';
                    }

                    return '<span class="text-sm text-gray-700">'
                        . e(\Illuminate\Support\Str::limit($row->deskripsi, 50))
                        . '</span>';
                })

                ->addColumn('status_badge', function ($row) {
                    return $row->is_active
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>'
                        : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>';
                })

                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                    <a href="' . route('master.kategori-buku.edit', $row->id) . '"
                       class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                       Edit
                    </a>
                    <button type="button"
                        class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                        data-url="' . route('master.kategori-buku.destroy', $row->id) . '"
                        data-name="Kategori ' . e($row->nama) . '">
                        Hapus
                    </button>
                </div>';
                })

                ->rawColumns(['kode', 'nama_text', 'deskripsi_text', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.kategori-buku.index', compact('useDatatables'));
    }

    public function create()
    {
        return view('master.kategori-buku.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:kategori_buku,kode',
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        KategoriBuku::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'message' => 'Kategori Buku berhasil ditambahkan!',
            'redirect' => route('master.kategori-buku.index'),
        ]);
    }

    public function edit(KategoriBuku $kategoriBuku)
    {
        return view('master.kategori-buku.edit', compact('kategoriBuku'));
    }

    public function update(Request $request, KategoriBuku $kategoriBuku)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:kategori_buku,kode,' . $kategoriBuku->id,
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $kategoriBuku->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message' => 'Kategori Buku berhasil diupdate!',
            'redirect' => route('master.kategori-buku.index'),
        ]);
    }

    public function destroy(KategoriBuku $kategoriBuku)
    {
        $kategoriBuku->delete();
        return response()->json([
            'message' => 'Kategori Buku berhasil dihapus!',
            'redirect' => route('master.kategori-buku.index'),
        ]);
    }
}
