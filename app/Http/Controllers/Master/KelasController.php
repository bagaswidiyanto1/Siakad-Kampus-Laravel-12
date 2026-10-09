<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\User;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KelasController extends Controller
{


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Kelas::with(['mataKuliah', 'dosen.user', 'tahunAkademik'])->latest();

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn(
                    'nama_kelas',
                    fn($row) =>
                    '<span class="font-semibold text-sm text-gray-800">Kelas ' . e($row->nama) . '</span>'
                )

                ->addColumn(
                    'mata_kuliah_nama',
                    fn($row) =>
                    $row->mataKuliah->nama ?? '-'
                )

                ->addColumn(
                    'dosen_nama',
                    fn($row) =>
                    $row->dosen->user->name ?? '-'
                )

                ->addColumn(
                    'kapasitas_text',
                    fn($row) =>
                    '<span class="text-sm text-gray-700">' . e($row->kapasitas) . ' mahasiswa</span>'
                )

                ->addColumn(
                    'tahun_akademik_nama',
                    fn($row) =>
                    $row->tahunAkademik->nama ?? '-'
                )

                ->addColumn('status_badge', function ($row) {
                    return $row->is_active
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>'
                        : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>';
                })

                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                    <a href="' . route('master.kelas.edit', $row->id) . '"
                       class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                       Edit
                    </a>
                    <button type="button"
                        class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                        data-url="' . route('master.kelas.destroy', $row->id) . '"
                        data-name="Kelas ' . e($row->nama) . '">
                        Hapus
                    </button>
                </div>';
                })

                ->rawColumns(['nama_kelas', 'kapasitas_text', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.kelas.index', compact('useDatatables'));
    }

    public function create()
    {
        $mataKuliah = MataKuliah::where('is_active', true)->get();
        // Ambil dosen dari tabel dosen yang aktif
        $dosen = Dosen::with('user')->where('status', 'aktif')->get();
        $tahunAkademik = TahunAkademik::where('is_active', true)->get();
        return view('master.kelas.create', compact('mataKuliah', 'dosen', 'tahunAkademik'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:20',
            'kapasitas' => 'required|integer|min:10|max:100',
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:dosen,id', // cek ke tabel dosen
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'ruang' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Cek duplikat
        $exists = Kelas::where('mata_kuliah_id', $request->mata_kuliah_id)
            ->where('tahun_akademik_id', $request->tahun_akademik_id)
            ->where('nama', $request->nama)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Kelas dengan nama ini sudah ada!',
            ], 422);
        }

        Kelas::create([
            'nama' => $request->nama,
            'kapasitas' => $request->kapasitas,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'dosen_id' => $request->dosen_id,
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'ruang' => $request->ruang,
            'is_active' => $request->has('is_active'),
        ]);


        return response()->json([
            'message' => 'Kelas berhasil ditambahkan!',
            'redirect' => route('master.kelas.index'),
        ]);
    }

    public function edit(Kelas $kelas)
    {
        $mataKuliah = MataKuliah::where('is_active', true)->get();
        $dosen = Dosen::with('user')->where('status', 'aktif')->get();
        $tahunAkademik = TahunAkademik::where('is_active', true)->get();
        return view('master.kelas.edit', compact('kelas', 'mataKuliah', 'dosen', 'tahunAkademik'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:20',
            'kapasitas' => 'required|integer|min:10|max:100',
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:dosen,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'ruang' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Cek duplikat
        $exists = Kelas::where('mata_kuliah_id', $request->mata_kuliah_id)
            ->where('tahun_akademik_id', $request->tahun_akademik_id)
            ->where('nama', $request->nama)
            ->where('id', '!=', $kelas->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Kelas dengan nama ini sudah ada!',
            ], 422);
        }

        $kelas->update([
            'nama' => $request->nama,
            'kapasitas' => $request->kapasitas,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'dosen_id' => $request->dosen_id,
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'ruang' => $request->ruang,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message' => 'Kelas berhasil diupdate!',
            'redirect' => route('master.kelas.index'),
        ]);
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return response()->json([
            'message' => 'Kelas berhasil dihapus!',
            'redirect' => route('master.kelas.index'),
        ]);
    }

    // API untuk get kelas by mata kuliah & tahun akademik
    public function getByMataKuliah($mataKuliahId, $tahunAkademikId)
    {
        $kelas = Kelas::where('mata_kuliah_id', $mataKuliahId)
            ->where('tahun_akademik_id', $tahunAkademikId)
            ->where('is_active', true)
            ->get();
        return response()->json($kelas);
    }
}
