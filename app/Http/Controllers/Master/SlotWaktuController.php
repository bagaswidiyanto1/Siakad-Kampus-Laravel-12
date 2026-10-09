<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\SlotWaktu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SlotWaktuController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SlotWaktu::query()->orderBy('hari')->orderBy('jam_mulai');

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
                    'hari_text',
                    fn($row) =>
                    '<span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold bg-indigo-100 text-indigo-800">'
                        . e($row->hari) . '</span>'
                )

                ->addColumn(
                    'jam_mulai_text',
                    fn($row) =>
                    '<span class="text-sm text-gray-700">' . e($row->jam_mulai_formatted) . '</span>'
                )

                ->addColumn(
                    'jam_selesai_text',
                    fn($row) =>
                    '<span class="text-sm text-gray-700">' . e($row->jam_selesai_formatted) . '</span>'
                )

                ->addColumn('status_badge', function ($row) {
                    return $row->is_active
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>'
                        : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>';
                })

                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                    <a href="' . route('master.slot-waktu.edit', $row->id) . '"
                       class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                       Edit
                    </a>
                    <button type="button"
                        class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                        data-url="' . route('master.slot-waktu.destroy', $row->id) . '"
                        data-name="Slot Waktu ' . e($row->hari . ' ' . $row->jam_mulai_formatted) . '">
                        Hapus
                    </button>
                </div>';
                })

                ->rawColumns(['kode', 'hari_text', 'jam_mulai_text', 'jam_selesai_text', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.slot-waktu.index', compact('useDatatables'));
    }

    public function create()
    {
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        return view('master.slot-waktu.create', compact('hariList'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        SlotWaktu::create([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return response()->json([
            'message' => 'Slot Waktu berhasil ditambahkan!',
            'redirect' => route('master.slot-waktu.index')
        ]);
    }

    public function edit(SlotWaktu $slotWaktu)
    {
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        return view('master.slot-waktu.edit', compact('slotWaktu', 'hariList'));
    }

    public function update(Request $request, SlotWaktu $slotWaktu)
    {
        $validator = Validator::make($request->all(), [
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $slotWaktu->update([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'message' => 'Slot Waktu berhasil diupdate!',
            'redirect' => route('master.slot-waktu.index')
        ]);
    }

    public function destroy(SlotWaktu $slotWaktu)
    {
        $slotWaktu->delete();
        return response()->json([
            'message' => 'Slot Waktu berhasil dihapus!',
            'redirect' => route('master.slot-waktu.index')
        ]);
    }
}
