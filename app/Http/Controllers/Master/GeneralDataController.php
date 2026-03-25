<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrmAux;
use App\Models\MsLevelUser;
use App\Models\MsUser;

class GeneralDataController extends Controller
{
    public function getListData(Request $request) {
        $trxAction = $request->input('TrxAction');

        switch ($trxAction) {
            case 'UIDESK66': // Data Aux (Lunch, Prayer, dll)
                return response()->json(TrmAux::where('NA', 'Y')->get());

            case 'UIDESK01': // Data Level User (Layer 1, dll)
                return response()->json(MsLevelUser::all());

            case 'UIDESK07': // List User dari ms_users
                $data = MsUser::where('NA', 'Y')->get();
                return response()->json([
                    'status'  => true,
                    'message' => 'Success',
                    'data'    => $data
                ]);

            default:
                return response()->json([
                    'message' => 'Action ' . $trxAction . ' belum terdaftar di mapping Laravel.'
                ], 404);
        }
    }
}