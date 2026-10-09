<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = MataKuliah::with(['prodi', 'kurikulum'])->latest();

            return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn(
                    'kode',
                    fn($row) =>
                    '<span class="font-mono text-xs text-gray-700">' . e($row->kode) . '</span>'
                )

                ->addColumn(
                    'sks_text',
                    fn($row) =>
                    '<span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-gray-100 text-gray-800">'
                        . e($row->sks) . ' SKS</span>'
                )

                ->addColumn(
                    'semester_text',
                    fn($row) =>
                    '<span class="text-sm text-gray-700">Semester ' . e($row->semester) . '</span>'
                )

                ->addColumn('jenis_badge', function ($row) {
                    $class = $row->jenis === 'wajib'
                        ? 'bg-blue-100 text-blue-800'
                        : 'bg-purple-100 text-purple-800';

                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '
                        . $class . '">' . e(ucfirst($row->jenis)) . '</span>';
                })

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
                        <a href="' . route('master.mata-kuliah.edit', $row->id) . '"
                           class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                           Edit
                        </a>
                        <button type="button"
                            class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                            data-url="' . route('master.mata-kuliah.destroy', $row->id) . '"
                            data-name="Mata Kuliah ' . e($row->nama) . '">
                            Hapus
                        </button>
                    </div>';
                })

                ->rawColumns(['kode', 'sks_text', 'semester_text', 'jenis_badge', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.mata-kuliah.index', compact('useDatatables'));
    }

    public function create()
    {
        $prodi = ProgramStudi::where('is_active', true)->get();
        $kurikulum = Kurikulum::where('is_active', true)->get();
        return view('master.mata-kuliah.create', compact('prodi', 'kurikulum'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:mata_kuliah,kode',
            'nama' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:14',
            'jenis' => 'required|in:wajib,pilihan',
            'prodi_id' => 'required|exists:program_studi,id',
            'kurikulum_id' => 'required|exists:kurikulum,id',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors(),
            ], 422);
        }

        MataKuliah::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'sks' => $request->sks,
            'semester' => $request->semester,
            'jenis' => $request->jenis,
            'prodi_id' => $request->prodi_id,
            'kurikulum_id' => $request->kurikulum_id,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message'  => 'Mata Kuliah berhasil ditambahkan!',
            'redirect' => route('master.mata-kuliah.index'),
        ]);
    }

    public function edit(MataKuliah $mataKuliah)
    {
        $prodi = ProgramStudi::where('is_active', true)->get();
        $kurikulum = Kurikulum::where('is_active', true)->get();
        return view('master.mata-kuliah.edit', compact('mataKuliah', 'prodi', 'kurikulum'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:mata_kuliah,kode,' . $mataKuliah->id,
            'nama' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:14',
            'jenis' => 'required|in:wajib,pilihan',
            'prodi_id' => 'required|exists:program_studi,id',
            'kurikulum_id' => 'required|exists:kurikulum,id',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $mataKuliah->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'sks' => $request->sks,
            'semester' => $request->semester,
            'jenis' => $request->jenis,
            'prodi_id' => $request->prodi_id,
            'kurikulum_id' => $request->kurikulum_id,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message'  => 'Mata Kuliah berhasil diupdate!',
            'redirect' => route('master.mata-kuliah.index'),
        ]);
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        // Cek apakah ada kelas yang terkait
        if ($mataKuliah->kelas()->count() > 0) {
            return response()->json([
                'message' => 'Mata Kuliah tidak bisa dihapus karena masih memiliki Kelas!',
            ], 400);
        }

        $mataKuliah->delete();

        return response()->json([
            'message' => 'Mata Kuliah berhasil dihapus!',
            'redirect' => route('master.mata-kuliah.index'),
        ]);
    }


    // API untuk get mata kuliah by prodi & kurikulum
    public function getByProdi($prodiId)
    {
        $mataKuliah = MataKuliah::where('prodi_id', $prodiId)
            ->where('is_active', true)
            ->get();
        return response()->json($mataKuliah);
    }
}
