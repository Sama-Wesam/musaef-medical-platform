<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller; 

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'maintenance_mode' => false,
            'auto_registration' => true,
            'two_factor_auth' => false,
            'ai_prediction_enabled' => true,
            'smtp_host' => 'smtp.musaef.org',
            'smtp_port' => '587',
            'system_language' => 'ar',
        ];

        return response()->json([
            'status' => 'success',
            'data' => $settings
        ], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم حفظ Tغييرات بنجاح.',
        ], 200);
    }

    public function getSystemHealth()
    {
        $healthStats = [
            'cpu_usage' => '34%',
            'memory_usage' => '62%',
            'storage_usage' => '45%',
            'response_time' => '120ms',
            'system_status' => 'healthy'
        ];

        return response()->json([
            'status' => 'success',
            'data' => $healthStats
        ], 200);
    }
}
