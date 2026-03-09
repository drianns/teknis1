<?php

use App\Helpers\LogHelpers;
use App\Models\Company;
use App\Models\WebConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
if (!function_exists("current_agent")) {
    function current_agent()
    {
        return auth()->user();
    }
}

if (!function_exists('get_config')) {
    function get_config($key, $company_id)
    {
        $companyConfig = DB::table('company_configs')->where('company_id', $company_id)->where('key', $key)->first();
        if ($companyConfig != null) {
            return $companyConfig->value;
        }

        return null;
    }
}

if (!function_exists("phone_enam_dua")) {
    function phone_enam_dua($number)
    {
        if (substr($number, 0, 1) == "8") {
            $number = "62" . ((int) $number);
        }

        return $number;
    }
}
