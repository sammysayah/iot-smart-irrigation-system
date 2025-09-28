<?php

namespace App\Http\Controllers;

use App\Models\SensorReading;
use App\Models\IrrigationSetting;
use App\Models\WaterLog;
use App\Models\ManualRequest;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Latest sensor reading with null safety
        $latestReading = SensorReading::latest()->first();
        
        // Create a default reading if none exists
        if (!$latestReading) {
            $latestReading = new SensorReading([
                'moisture_level' => 65.5,
                'pump_status' => false,
                'water_used' => 0,
                'reading_type' => 'automatic',
                'created_at' => now()
            ]);
        }

        // System settings
        $settings = IrrigationSetting::firstOrCreate([]);

        // Charts
        $moistureChart = $this->createMoistureChart();
        $waterChart = $this->createWaterUsageChart();

        // Recent readings (for activity log)
        $recentReadings = SensorReading::latest()->take(10)->get();

        // Statistics
        $statistics = $this->getIrrigationStatistics();

        return view('dashboard', compact(
            'latestReading',
            'settings',
            'moistureChart',
            'waterChart',
            'recentReadings',
            'statistics'
        ));
    }



    private function createMoistureChart()
    {
        $chart = new Chart();
        $readings = SensorReading::latest()->take(20)->get()->reverse();

        if ($readings->count() > 0) {
            $chart->labels($readings->pluck('created_at')->map(fn($d) => $d->format('H:i')));
            $chart->dataset('Soil Moisture %', 'line', $readings->pluck('moisture_level'))
                  ->options([
                      'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                      'borderColor' => 'rgb(59, 130, 246)',
                      'borderWidth' => 2,
                      'fill' => true,
                      'tension' => 0.4,
                  ]);
        } else {
            // Demo data
            $chart->labels(['10:00','11:00','12:00','13:00','14:00']);
            $chart->dataset('Soil Moisture %', 'line', [60, 62, 65, 63, 64])
                  ->options([
                      'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                      'borderColor' => 'rgb(59, 130, 246)',
                      'borderWidth' => 2,
                      'fill' => true,
                      'tension' => 0.4,
                  ]);
        }

        return $chart;
    }

    private function createWaterUsageChart()
    {
        $chart = new Chart();
        $usage = WaterLog::where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(water_used) as total_water')
            ->groupBy('date')
            ->get();

        if ($usage->count() > 0) {
            $chart->labels($usage->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M j')));
            $chart->dataset('Water Usage (L)', 'bar', $usage->pluck('total_water'))
                  ->options([
                      'backgroundColor' => 'rgba(16, 185, 129, 0.7)',
                      'borderColor' => 'rgb(16, 185, 129)',
                      'borderWidth' => 1,
                  ]);
        } else {
            // Demo data
            $chart->labels(['Mon','Tue','Wed','Thu','Fri']);
            $chart->dataset('Water Usage (L)', 'bar', [5, 8, 6, 7, 5])
                  ->options([
                      'backgroundColor' => 'rgba(16, 185, 129, 0.7)',
                      'borderColor' => 'rgb(16, 185, 129)',
                      'borderWidth' => 1,
                  ]);
        }

        return $chart;
    }

    private function getIrrigationStatistics()
    {
        $today = Carbon::today();
        $weekStart = now()->subDays(7);

        return [
            'today' => [
                'water_used' => WaterLog::whereDate('created_at', $today)->sum('water_used') ?? 0,
                'watering_sessions' => WaterLog::whereDate('created_at', $today)->count() ?? 0,
            ],
            'this_week' => [
                'water_used' => WaterLog::where('created_at', '>=', $weekStart)->sum('water_used') ?? 0,
                'watering_sessions' => WaterLog::where('created_at', '>=', $weekStart)->count() ?? 0,
            ],
            'total_water' => WaterLog::sum('water_used') ?? 0,
            'avg_moisture' => SensorReading::avg('moisture_level') ?? 50,
        ];
    }

    // ... keep the existing chart methods and statistics method ...

    public function updateSettings(Request $request)
    {
        $settings = IrrigationSetting::firstOrCreate([]);

        $request->validate([
            'moisture_threshold' => 'required|numeric|min:0|max:100',
            'pump_duration' => 'required|integer|min:5|max:300',
            'auto_mode' => 'sometimes|boolean',
            'system_enabled' => 'sometimes|boolean',
        ]);

        $settings->update([
            'moisture_threshold' => $request->moisture_threshold,
            'pump_duration' => $request->pump_duration,
            'auto_mode' => $request->boolean('auto_mode'),
            'system_enabled' => $request->boolean('system_enabled'),
        ]);

        return redirect()->back()->with('status', 'Settings updated successfully!');
    }

    public function manualWater(Request $request)
    {
        try {
            $settings = IrrigationSetting::firstOrCreate([]);

            if (!$settings->system_enabled) {
                return response()->json([
                    'success' => false,
                    'message' => 'System is disabled. Please enable it in settings.'
                ], 200);
            }

            // Create a manual request instead of direct sensor reading
            ManualRequest::create([
                'active' => true,
                'processed' => false,
                'duration' => $settings->pump_duration,
                'expires_at' => now()->addMinutes(2) // Request expires in 2 minutes
            ]);

            return response()->json([
                'success' => true,
                'duration' => $settings->pump_duration,
                'message' => 'Manual watering request sent. ESP32 will process it shortly.'
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Manual watering error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function latestSensorData()
    {
        try {
            $latestReading = SensorReading::latest()->first();
            
            if (!$latestReading) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'moisture_level' => 0,
                        'pump_status' => false,
                        'created_at' => now()->toISOString(),
                        'reading_type' => 'automatic'
                    ]
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'moisture_level' => $latestReading->moisture_level,
                    'pump_status' => $latestReading->pump_status,
                    'created_at' => $latestReading->created_at->toISOString(),
                    'reading_type' => $latestReading->reading_type,
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Latest sensor data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sensor data'
            ], 500);
        }
    }

    // Add cleanup method to remove expired requests (optional)
    public function cleanupExpiredRequests()
    {
        ManualRequest::where('expires_at', '<', now())->update(['active' => false]);
        return response()->json(['success' => true, 'message' => 'Expired requests cleaned up']);
    }
}