<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportUserApplicationController extends Controller
{
    public function index(Request $request)
    {
        // Query users with their roles and company
        $query = User::with(['role', 'company']);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);

        return view('pages.management-user.export-user-application.index', compact('users'));
    }

    public function export(Request $request)
    {
        $format = $request->input('format', 'excel');
        $groupedBy = $request->input('grouped_by', []);

        // Get filtered data
        $users = User::with(['role', 'company'])->get();

        // Format the collection to match what the export class expects
        // This maps the User model to the structure expected by the View/Export
        $formattedUsers = $users->map(function ($user) {
            // Determine level based on role or fallback
            $level = 'Agent';
            if ($user->role) {
                if (stripos($user->role->name, 'admin') !== false) {
                    $level = 'Administrator';
                } elseif (stripos($user->role->name, 'spv') !== false || stripos($user->role->name, 'supervisor') !== false) {
                    $level = 'Supervisor';
                } else {
                    $level = 'Layer 1'; // Default dummy assignment for UI
                }
            }

            return (object) [
                'user_name' => $user->name,
                'name' => $user->name,
                'level_user' => $level,
                'email' => $user->email,
                'department' => $user->company ? $user->company->name : 'N/A',
                'group' => '',
                'status' => 'Aktif',
            ];
        });

        switch (strtolower($format)) {
            case 'excel':
                return Excel::download(
                    new UserExport($formattedUsers, $groupedBy),
                    'user_export_' . time() . '.xlsx'
                );

            case 'csv':
                return Excel::download(
                    new UserExport($formattedUsers, $groupedBy),
                    'user_export_' . time() . '.csv'
                );

            case 'pdf':
                $pdf = Pdf::loadView('exports.users-pdf', [
                    'users' => $formattedUsers,
                    'grouped_by' => $groupedBy
                ]);
                return $pdf->download('user_export_' . time() . '.pdf');

            case 'json':
                return response()->json($formattedUsers);

            default:
                return response()->json(['error' => 'Invalid format'], 400);
        }
    }
}
