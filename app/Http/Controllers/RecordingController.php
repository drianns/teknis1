<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecordingController extends Controller
{
    /**
     * Display the recording index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pages.recording.index');
    }

    public function getData(Request $request)
    {
        // Asumsi tabel 'recordings', jika tidak ada kita return empty pagination array sesuai struktur
        $query = \Illuminate\Support\Facades\DB::table('recordings')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('file_name', 'like', "%{$search}%");
        }

        try {
            $perPage = $request->get('per_page', 10);
            $data = $query->paginate($perPage);
        } catch (\Exception $e) {
            // Jika tabel belum ada, kembalikan format pagination kosong
            $data = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        return response()->json($data);
    }
}
