<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KurikulumController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Kurikulum::with('prodi')->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn(
                    'kode',
                    fn($row) =>
                    '<span class="font-mono text-xs text-gray-700">' . e($row->kode) . '</span>'
                )
                ->addColumn(
                    'tahun_berlaku_text',
                    fn($row) =>
                    '<span class="text-sm text-gray-700">' . e($row->tahun_berlaku) . '</span>'
                )
                ->addColumn(
                    'prodi_nama',
                    fn($row) =>
                    $row->prodi->nama ?? '-'
                )
                ->addColumn('status_badge', function ($row) {
                    return $row->is_active
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>'
                        : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                        <a href="' . route('master.kurikulum.edit', $row->id) . '"
                           class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                           Edit
                        </a>
                        <button type="button"
                            class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                            data-url="' . route('master.kurikulum.destroy', $row->id) . '"
                            data-name="Kurikulum ' . e($row->nama) . '">
                            Hapus
                        </button>
                    </div>';
                })
                ->rawColumns(['kode', 'tahun_berlaku_text', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.kurikulum.index', compact('useDatatables'));
    }

    public function create()
    {
        $prodi = ProgramStudi::where('is_active', true)->get();
        return view('master.kurikulum.create', compact('prodi'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode'          => 'required|string|max:20|unique:kurikulum,kode',
            'nama'          => 'required|string|max:100',
            'tahun_berlaku' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'prodi_id'      => 'required|exists:program_studi,id',
            'deskripsi'     => 'nullable|string',
            'is_active'     => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors(),
            ], 422);
        }

        Kurikulum::create([
            'kode'          => $request->kode,
            'nama'          => $request->nama,
            'tahun_berlaku' => $request->tahun_berlaku,
            'prodi_id'      => $request->prodi_id,
            'deskripsi'     => $request->deskripsi,
            'is_active'     => 1,
        ]);

        return response()->json([
            'message'  => 'Kurikulum berhasil ditambahkan!',
            'redirect' => route('master.kurikulum.index'),
        ]);
    }

    public function edit(Kurikulum $kurikulum)
    {
        $prodi = ProgramStudi::where('is_active', true)->get();
        return view('master.kurikulum.edit', compact('kurikulum', 'prodi'));
    }

    public function update(Request $request, Kurikulum $kurikulum)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:kurikulum,kode,' . $kurikulum->id,
            'nama' => 'required|string|max:100',
            'tahun_berlaku' => 'required|integer|min:2000|max:' . date('Y') + 5,
            'prodi_id' => 'required|exists:program_studi,id',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $kurikulum->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'tahun_berlaku' => $request->tahun_berlaku,
            'prodi_id' => $request->prodi_id,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message' => 'Kurikulum berhasil diupdate!',
            'redirect' => route('master.kurikulum.index'),
        ]);
    }

    public function destroy(Kurikulum $kurikulum)
    {
        // Cek apakah ada mata kuliah yang terkait
        if ($kurikulum->mataKuliah()->count() > 0) {
            return response()->json([
                'message' => 'Kurikulum tidak bisa dihapus karena masih memiliki Mata Kuliah!',
            ], 400);
        }

        $kurikulum->delete();

        return response()->json([
            'message' => 'Kurikulum berhasil dihapus!',
            'redirect' => route('master.kurikulum.index'),
        ]);
    }
}
