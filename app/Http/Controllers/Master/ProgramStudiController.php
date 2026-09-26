<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ProgramStudiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ProgramStudi::with('fakultas')->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn(
                    'kode',
                    fn($row) =>
                    '<span class="font-mono text-xs text-gray-700">' . e($row->kode) . '</span>'
                )
                ->addColumn('jenjang_badge', function ($row) {
                    return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">'
                        . e($row->jenjang) . '</span>';
                })
                ->addColumn(
                    'fakultas_nama',
                    fn($row) =>
                    $row->fakultas->nama ?? '-'
                )
                ->addColumn('status_badge', function ($row) {
                    return $row->is_active
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>'
                        : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center justify-end gap-2 whitespace-nowrap">
                        <a href="' . route('master.prodi.edit', $row->id) . '"
                           class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                           Edit
                        </a>
                        <button type="button"
                            class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                            data-url="' . route('master.prodi.destroy', $row->id) . '"
                            data-name="Prodi ' . e($row->nama) . '">
                            Hapus
                        </button>
                    </div>';
                })
                ->rawColumns(['kode', 'jenjang_badge', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.prodi.index', compact('useDatatables'));
    }

    public function create()
    {
        $fakultas = Fakultas::where('is_active', true)->get();
        return view('master.prodi.create', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:program_studi,kode',
            'nama' => 'required|string|max:100',
            'jenjang' => 'required|in:D3,D4,S1,S2,S3',
            'fakultas_id' => 'required|exists:fakultas,id',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        ProgramStudi::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'jenjang' => $request->jenjang,
            'fakultas_id' => $request->fakultas_id,
            'is_active' => 1,
        ]);

        return response()->json([
            'message' => 'Program Studi berhasil ditambahkan!',
            'redirect' => route('master.prodi.index')
        ]);
    }

    public function edit(ProgramStudi $prodi)
    {
        $fakultas = Fakultas::where('is_active', true)->get();
        return view('master.prodi.edit', compact('prodi', 'fakultas'));
    }

    public function update(Request $request, ProgramStudi $prodi)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:program_studi,kode,' . $prodi->id,
            'nama' => 'required|string|max:100',
            'jenjang' => 'required|in:D3,D4,S1,S2,S3',
            'fakultas_id' => 'required|exists:fakultas,id',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors' => $validator->errors()
            ], 422);
        }

        $prodi->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'jenjang' => $request->jenjang,
            'fakultas_id' => $request->fakultas_id,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message' => 'Program Studi berhasil diupdate!',
            'redirect' => route('master.prodi.index')
        ]);
    }

    public function destroy(ProgramStudi $prodi)
    {
        // Cek apakah ada user yang terkait
        if ($prodi->users()->count() > 0) {
            return response()->json([
                'message' => 'Program Studi tidak bisa dihapus karena masih memiliki user!',
            ], 400);
        }

        // Cek apakah ada kurikulum yang terkait
        if ($prodi->kurikulum()->count() > 0) {
            return response()->json([
                'message' => 'Program Studi tidak bisa dihapus karena masih memiliki Kurikulum!',
            ], 400);
        }

        $prodi->delete();

        return response()->json([
            'message' => 'Program Studi berhasil dihapus!',
            'redirect' => route('master.prodi.index')
        ]);
    }
}
