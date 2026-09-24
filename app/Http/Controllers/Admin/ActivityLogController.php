<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Activity::with('causer')->select('activity_log.*');

            // Filter by event
            if ($request->filled('event')) {
                $query->where('event', $request->event);
            }

            // Filter by subject type
            if ($request->filled('subject_type')) {
                $query->where('subject_type', $request->subject_type);
            }

            // Filter by date range
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            return DataTables::of($query)
                ->addIndexColumn()

                // Waktu
                ->editColumn(
                    'created_at',
                    fn($row) =>
                    $row->created_at->format('d M Y H:i:s')
                )

                // Causer
                ->addColumn('causer_name', function ($row) {
                    $name = $row->causer?->name ?? 'System';
                    return '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">'
                        . e($name) . '</span>';
                })

                // Event badge
                ->addColumn('event_badge', function ($row) {
                    $map = [
                        'created' => 'bg-green-100 text-green-800',
                        'updated' => 'bg-blue-100 text-blue-800',
                        'deleted' => 'bg-red-100 text-red-800',
                    ];
                    $class = $map[$row->event] ?? 'bg-gray-100 text-gray-800';
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '
                        . $class . '">' . e(ucfirst($row->event ?? '-')) . '</span>';
                })

                // Model
                ->addColumn(
                    'subject_name',
                    fn($row) =>
                    $row->subject_type ? class_basename($row->subject_type) : '-'
                )

                // Detail + properties
                ->addColumn('detail', function ($row) {
                    $desc = e($row->description ?? 'N/A');
                    $html = '<div class="flex flex-col gap-1">'
                        . '<span class="font-medium">' . $desc . '</span>';

                    if ($row->properties && count($row->properties) > 0) {
                        $html .= '<details class="text-xs">'
                            . '<summary class="cursor-pointer text-indigo-600 hover:text-indigo-900">Lihat perubahan</summary>'
                            . '<pre class="mt-1 p-2 bg-gray-100 rounded text-xs overflow-auto max-h-32">'
                            . e(json_encode($row->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))
                            . '</pre></details>';
                    }

                    return $html . '</div>';
                })

                // Aksi
                ->addColumn('action', function ($row) {
                    $showUrl = route('admin.activity-logs.show', $row->id);

                    return '<div class="flex items-center gap-2 whitespace-nowrap">'
                        . '<a href="' . $showUrl . '" class="bg-indigo-600 hover:bg-indigo-900 text-xs font-medium py-1 px-2 rounded-md text-white">Detail</a>'
                        . '<button type="button"
                                class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs font-medium py-1 px-2 rounded-md text-white"
                                data-url="' . route('admin.activity-logs.destroy', $row->id) . '"
                                data-name="Log #' . $row->id . '">Hapus</button>'
                        . '</div>';
                })

                ->rawColumns(['causer_name', 'event_badge', 'detail', 'action'])
                ->make(true);
        }

        // Data untuk dropdown filter
        $events = Activity::select('event')->distinct()->orderBy('event')->pluck('event')->filter();
        $subjectTypes = Activity::select('subject_type')->distinct()->orderBy('subject_type')->pluck('subject_type')->filter();

        return view('admin.activity-logs.index', compact('events', 'subjectTypes'));
    }

    /**
     * Export activity logs to CSV
     */
    public function export(Request $request)
    {
        $query = Activity::query();

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $activities = $query->with('causer')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];

        $columns = ['Tanggal & Waktu', 'Pengguna', 'Aksi', 'Model', 'ID', 'Keterangan'];
        $callback = function () use ($activities, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($activities as $activity) {
                $description = $activity->description ?? 'N/A';
                if ($activity->properties) {
                    $properties = json_encode($activity->properties->toArray(), JSON_UNESCAPED_UNICODE);
                    $description .= ' | ' . $properties;
                }

                fputcsv($file, [
                    $activity->created_at->format('d-m-Y H:i:s'),
                    $activity->causer?->name ?? 'System',
                    $activity->event,
                    class_basename($activity->subject_type),
                    $activity->subject_id ?? 'N/A',
                    $description,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Clear all activity logs (superadmin only)
     */
    public function clear()
    {
        Activity::truncate();

        return response()->json([
            'message' => 'Semua activity log berhasil dihapus!',
            'redirect' => route('admin.activity-logs.index'), // opsional
        ]);
    }

    /**
     * Delete single activity log
     */
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return response()->json([
            'message' => 'Activity log berhasil dihapus!',
            'redirect' => route('admin.activity-logs.index'),
        ]);
    }

    /**
     * Show detail activity log
     */
    public function show($id)
    {
        $activity = Activity::with('causer')->findOrFail($id);
        return view('admin.activity-logs.show', compact('activity'));
    }
}
