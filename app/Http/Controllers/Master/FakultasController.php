<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class FakultasController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Fakultas::query()->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn(
                    'kode',
                    fn($row) =>
                    '<span class="font-mono text-xs text-gray-700">' . e($row->kode) . '</span>'
                )
                ->addColumn('status_badge', function ($row) {
                    return $row->is_active
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>'
                        : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center gap-2 whitespace-nowrap">
                        <a href="' . route('master.fakultas.edit', $row->id) . '"
                           class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white">Edit</a>
                        <button type="button"
                            class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white"
                            data-url="' . route('master.fakultas.destroy', $row->id) . '"
                            data-name="Fakultas ' . e($row->nama) . '">Hapus</button>
                    </div>';
                })
                ->rawColumns(['kode', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.fakultas.index', compact('useDatatables'));
    }

    public function create()
    {
        return view('master.fakultas.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:fakultas,kode',
            'nama' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors' => $validator->errors()
            ], 422);
        }

        Fakultas::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'is_active' => 1,
        ]);


        return response()->json([
            'message' => 'Fakultas berhasil ditambahkan!',
            'redirect' => route('master.fakultas.index')
        ]);
    }

    public function edit(Fakultas $fakultas)
    {
        return view('master.fakultas.edit', compact('fakultas'));
    }

    public function update(Request $request, Fakultas $fakultas)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:fakultas,kode,' . $fakultas->id,
            'nama' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors' => $validator->errors()
            ], 422);
        }

        $fakultas->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message' => 'Fakultas berhasil diupdate!',
            'redirect' => route('master.fakultas.index')
        ]);
    }

    public function destroy(Fakultas $fakultas)
    {
        // Cek apakah ada prodi yang terkait
        if ($fakultas->programStudi()->count() > 0) {
            return response()->json([
                'message' => 'Fakultas tidak bisa dihapus karena masih memiliki Program Studi!',
                'redirect' => route('master.fakultas.index')
            ], 400);
        }

        $fakultas->delete();

        return response()->json([
            'message' => 'Fakultas berhasil dihapus!',
            'redirect' => route('master.fakultas.index')
        ]);
    }
}
