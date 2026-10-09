<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\JenisPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class JenisPengajuanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = JenisPengajuan::query()->orderBy('urutan_approval');

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

                ->addColumn('urutan_badge', function ($row) {
                    $map = [
                        1 => 'bg-blue-100 text-blue-800',
                        2 => 'bg-purple-100 text-purple-800',
                        3 => 'bg-green-100 text-green-800',
                    ];
                    $class = $map[$row->urutan_approval] ?? 'bg-gray-100 text-gray-800';

                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '
                        . $class . '">' . e($row->urutan_label) . '</span>';
                })

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
                    <a href="' . route('master.jenis-pengajuan.edit', $row->id) . '"
                       class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                       Edit
                    </a>
                    <button type="button"
                        class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                        data-url="' . route('master.jenis-pengajuan.destroy', $row->id) . '"
                        data-name="Jenis Pengajuan ' . e($row->nama) . '">
                        Hapus
                    </button>
                </div>';
                })

                ->rawColumns(['kode', 'nama_text', 'urutan_badge', 'deskripsi_text', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.jenis-pengajuan.index', compact('useDatatables'));
    }
    public function create()
    {
        $urutanList = [
            1 => 'Prodi',
            2 => 'Fakultas',
            3 => 'BAA',
        ];
        return view('master.jenis-pengajuan.create', compact('urutanList'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:jenis_pengajuan,kode',
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'urutan_approval' => 'required|integer|min:1|max:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        JenisPengajuan::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'urutan_approval' => $request->urutan_approval,
        ]);

        return response()->json([
            'message' => 'Jenis Pengajuan berhasil ditambahkan!',
            'redirect' => route('master.jenis-pengajuan.index'),
        ]);
    }

    public function edit(JenisPengajuan $jenisPengajuan)
    {
        $urutanList = [
            1 => 'Prodi',
            2 => 'Fakultas',
            3 => 'BAA',
        ];
        return view('master.jenis-pengajuan.edit', compact('jenisPengajuan', 'urutanList'));
    }

    public function update(Request $request, JenisPengajuan $jenisPengajuan)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:jenis_pengajuan,kode,' . $jenisPengajuan->id,
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'urutan_approval' => 'required|integer|min:1|max:3',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $jenisPengajuan->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'urutan_approval' => $request->urutan_approval,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message' => 'Jenis Pengajuan berhasil diupdate!',
            'redirect' => route('master.jenis-pengajuan.index'),
        ]);
    }

    public function destroy(JenisPengajuan $jenisPengajuan)
    {
        $jenisPengajuan->delete();
        return response()->json([
            'message' => 'Jenis Pengajuan berhasil dihapus!',
            'redirect' => route('master.jenis-pengajuan.index'),
        ]);
    }
}
