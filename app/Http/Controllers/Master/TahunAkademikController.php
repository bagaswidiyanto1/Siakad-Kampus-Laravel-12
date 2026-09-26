<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TahunAkademikController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = TahunAkademik::query()->latest();

            return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn(
                    'kode',
                    fn($row) =>
                    '<span class="font-mono text-xs text-gray-700">' . e($row->kode) . '</span>'
                )

                ->addColumn('semester_badge', function ($row) {
                    $class = $row->semester === 'ganjil'
                        ? 'bg-blue-100 text-blue-800'
                        : 'bg-yellow-100 text-yellow-800';

                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '
                        . $class . '">' . e(ucfirst($row->semester)) . '</span>';
                })

                ->addColumn('tanggal_range', function ($row) {
                    // Format tanggal, jaga kalau null
                    $mulai   = $row->tanggal_mulai
                        ? \Carbon\Carbon::parse($row->tanggal_mulai)->format('d/m/Y')
                        : '-';
                    $selesai = $row->tanggal_selesai
                        ? \Carbon\Carbon::parse($row->tanggal_selesai)->format('d/m/Y')
                        : '-';

                    return '<span class="text-sm text-gray-700">' . $mulai . ' &ndash; ' . $selesai . '</span>';
                })

                ->addColumn('status_badge', function ($row) {
                    $html = '<div class="flex flex-col items-center gap-1">';

                    $html .= $row->is_active
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>'
                        : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>';

                    if ($row->is_current) {
                        $html .= '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Berjalan</span>';
                    }

                    return $html . '</div>';
                })

                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center justify-end gap-2 whitespace-nowrap">
                        <a href="' . route('master.tahun-akademik.edit', $row->id) . '"
                           class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                           Edit
                        </a>
                        <button type="button"
                            class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                            data-url="' . route('master.tahun-akademik.destroy', $row->id) . '"
                            data-name="Tahun Akademik ' . e($row->kode) . '">
                            Hapus
                        </button>
                    </div>';
                })

                ->rawColumns(['kode', 'semester_badge', 'tanggal_range', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.tahun-akademik.index', compact('useDatatables'));
    }

    public function create()
    {
        return view('master.tahun-akademik.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:tahun_akademik,kode',
            'nama' => 'required|string|max:50',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'nullable|boolean',
            'is_current' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors' => $validator->errors()
            ], 422);
        }

        TahunAkademik::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'semester' => $request->semester,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active' => 1,
            'is_current' => 0,
        ]);

        return response()->json([
            'message' => 'Tahun Akademik berhasil ditambahkan!',
            'redirect' => route('master.tahun-akademik.index')
        ]);
    }

    public function edit(TahunAkademik $tahunAkademik)
    {
        return view('master.tahun-akademik.edit', compact('tahunAkademik'));
    }

    public function update(Request $request, TahunAkademik $tahunAkademik)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:20|unique:tahun_akademik,kode,' . $tahunAkademik->id,
            'nama' => 'required|string|max:50',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'nullable|boolean',
            'is_current' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors' => $validator->errors()
            ], 422);
        }

        $tahunAkademik->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'semester' => $request->semester,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active' => $request->has('is_active'),
            'is_current' => $request->has('is_current'),
        ]);

        return response()->json([
            'message' => 'Tahun Akademik berhasil diupdate!',
            'redirect' => route('master.tahun-akademik.index')
        ]);
    }

    public function destroy(TahunAkademik $tahunAkademik)
    {
        // Cek apakah ada kelas yang terkait
        if ($tahunAkademik->kelas()->count() > 0) {
            return response()->json([
                'message' => 'Tahun Akademik tidak bisa dihapus karena masih memiliki Kelas!',
            ], 400);
        }

        $tahunAkademik->delete();

        return response()->json([
            'message' => 'Tahun Akademik berhasil dihapus!',
            'redirect' => route('master.tahun-akademik.index')
        ]);
    }
}
