<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\KomponenBiaya;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KomponenBiayaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = KomponenBiaya::with('prodi')->latest();

            return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn(
                    'kode',
                    fn($row) =>
                    '<span class="font-mono text-xs text-gray-700">' . e($row->kode) . '</span>'
                )

                ->addColumn('jenis_badge', function ($row) {
                    $map = [
                        'ukt'       => 'bg-red-100 text-red-800',
                        'spp'       => 'bg-blue-100 text-blue-800',
                        'praktikum' => 'bg-green-100 text-green-800',
                        'seminar'   => 'bg-purple-100 text-purple-800',
                        'wisuda'    => 'bg-yellow-100 text-yellow-800',
                    ];
                    $class = $map[$row->jenis] ?? 'bg-gray-100 text-gray-800';

                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '
                        . $class . '">' . e(strtoupper($row->jenis)) . '</span>';
                })

                ->addColumn(
                    'nominal_text',
                    fn($row) =>
                    '<span class="font-semibold text-sm text-green-600">Rp '
                        . number_format($row->nominal, 0, ',', '.') . '</span>'
                )

                ->addColumn(
                    'semester_text',
                    fn($row) =>
                    $row->semester_berlaku
                        ? '<span class="text-sm text-gray-700">' . e($row->semester_berlaku) . '</span>'
                        : '<span class="text-gray-400">-</span>'
                )

                ->addColumn(
                    'prodi_nama',
                    fn($row) =>
                    $row->prodi->nama ?? 'Semua Prodi'
                )

                ->addColumn('status_badge', function ($row) {
                    return $row->is_active
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>'
                        : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>';
                })

                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                    <a href="' . route('master.komponen-biaya.edit', $row->id) . '"
                       class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                       Edit
                    </a>
                    <button type="button"
                        class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                        data-url="' . route('master.komponen-biaya.destroy', $row->id) . '"
                        data-name="Komponen Biaya ' . e($row->nama) . '">
                        Hapus
                    </button>
                </div>';
                })

                ->rawColumns(['kode', 'jenis_badge', 'nominal_text', 'semester_text', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.komponen-biaya.index', compact('useDatatables'));
    }

    public function create()
    {
        $prodi = ProgramStudi::where('is_active', true)->get();
        return view('master.komponen-biaya.create', compact('prodi'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:komponen_biaya,kode',
            'nama' => 'required|string|max:100',
            'jenis' => 'required|in:ukt,spp,praktikum,seminar,wisuda,lainnya',
            'nominal' => 'required|numeric|min:0',
            'semester_berlaku' => 'nullable|string|max:50',
            'prodi_id' => 'nullable|exists:program_studi,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors' => $validator->errors()
            ], 422);
        }

        KomponenBiaya::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'nominal' => $request->nominal,
            'semester_berlaku' => $request->semester_berlaku,
            'prodi_id' => $request->prodi_id,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message' => 'Komponen Biaya berhasil ditambahkan!',
            'redirect' => route('master.komponen-biaya.index'),
        ]);
    }

    public function edit(KomponenBiaya $komponenBiaya)
    {
        $prodi = ProgramStudi::where('is_active', true)->get();
        return view('master.komponen-biaya.edit', compact('komponenBiaya', 'prodi'));
    }

    public function update(Request $request, KomponenBiaya $komponenBiaya)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:komponen_biaya,kode,' . $komponenBiaya->id,
            'nama' => 'required|string|max:100',
            'jenis' => 'required|in:ukt,spp,praktikum,seminar,wisuda,lainnya',
            'nominal' => 'required|numeric|min:0',
            'semester_berlaku' => 'nullable|string|max:50',
            'prodi_id' => 'nullable|exists:program_studi,id',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors' => $validator->errors()
            ], 422);
        }

        $komponenBiaya->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'nominal' => $request->nominal,
            'semester_berlaku' => $request->semester_berlaku,
            'prodi_id' => $request->prodi_id,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message' => 'Komponen Biaya berhasil diupdate!',
            'redirect' => route('master.komponen-biaya.index'),
        ]);
    }

    public function destroy(KomponenBiaya $komponenBiaya)
    {
        $komponenBiaya->delete();

        return response()->json([
            'message' => 'Komponen Biaya berhasil dihapus!',
            'redirect' => route('master.komponen-biaya.index'),
        ]);
    }
}
