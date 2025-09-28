<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use App\Models\IrrigationSetting;
use App\Models\WaterLog;
use App\Models\ManualRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IrrigationApiController extends Controller
{
    public function receiveData(Request $request)
{
    $request->validate([
        'moisture_level' => 'required|numeric',
        'pump_status' => 'sometimes|boolean',
        'water_used' => 'sometimes|numeric',
    ]);

    $settings = IrrigationSetting::first();
    if (!$settings) {
        return response()->json([
            'success' => false,
            'message' => 'System settings not configured'
        ], 500);
    }
    
    // Create sensor reading
    $reading = SensorReading::create([
        'moisture_level' => $request->moisture_level,
        'pump_status' => $request->pump_status ?? false,
        'water_used' => $request->water_used ?? 0,
        'reading_type' => 'automatic'
    ]);

    // Log water usage if pump was active
    if ($request->pump_status && $request->water_used > 0) {
        WaterLog::create([
            'water_used' => $request->water_used,
            'duration_seconds' => $settings->pump_duration,
            'trigger_type' => 'automatic'
        ]);
    }

    // Check if we need to trigger watering - RESPECT system_enabled setting
    $shouldWater = $settings->system_enabled && 
                  $settings->auto_mode && 
                  $request->moisture_level < $settings->moisture_threshold;

    return response()->json([
        'success' => true,
        'should_water' => $shouldWater,
        'pump_duration' => $settings->pump_duration,
        'moisture_threshold' => $settings->moisture_threshold,
        'auto_mode' => $settings->auto_mode,
        'system_enabled' => $settings->system_enabled,
        'message' => $shouldWater ? 'Watering required' : 
                    (!$settings->system_enabled ? 'System disabled' : 
                    ($request->moisture_level >= $settings->moisture_threshold ? 'Moisture optimal' : 'No watering needed'))
    ]);
}

    public function getSystemStatus()
    {
        $latestReading = SensorReading::latest()->first();
        $settings = IrrigationSetting::first();

        if (!$settings) {
            return response()->json([
                'success' => false,
                'message' => 'System settings not configured'
            ], 500);
        }

        return response()->json([
            'moisture_level' => $latestReading->moisture_level ?? 0,
            'pump_status' => $latestReading->pump_status ?? false,
            'auto_mode' => $settings->auto_mode,
            'system_enabled' => $settings->system_enabled,
            'moisture_threshold' => $settings->moisture_threshold,
            'pump_duration' => $settings->pump_duration
        ]);
    }

    public function manualWater(Request $request)
    {
        $settings = IrrigationSetting::first();
        
        if (!$settings) {
            return response()->json([
                'success' => false,
                'message' => 'System settings not configured'
            ], 500);
        }

        // Check if system is enabled
        if (!$settings->system_enabled) {
            return response()->json([
                'success' => false,
                'message' => 'System is disabled. Enable system first.'
            ]);
        }

        // Check for pending manual requests (expire after 2 minutes)
        $manualRequest = ManualRequest::pending()->first();

        if ($manualRequest) {
            // Mark as processed
            $manualRequest->update(['processed' => true]);
            
            // Create sensor reading for manual watering
            $currentMoisture = SensorReading::latest()->first()->moisture_level ?? 50;
            SensorReading::create([
                'moisture_level' => $currentMoisture,
                'pump_status' => true,
                'water_used' => 0,
                'reading_type' => 'manual'
            ]);

            return response()->json([
                'success' => true,
                'duration' => $manualRequest->duration,
                'message' => 'Manual watering triggered'
            ]);
        }

        // No pending manual request found
        return response()->json([
            'success' => false,
            'duration' => $settings->pump_duration,
            'message' => 'No active manual watering request'
        ]);
    }
}