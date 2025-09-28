<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌱 Smart Irrigation Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444'
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'spin-slow': 'spin 3s linear infinite',
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'water-drop': 'waterDrop 1.5s ease-in-out infinite',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideIn: {
                            '0%': { transform: 'translateY(-10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        waterDrop: {
                            '0%, 100%': { transform: 'translateY(0) scale(1)', opacity: '1' },
                            '50%': { transform: 'translateY(-10px) scale(1.1)', opacity: '0.7' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .pump-active {
            animation: pulse 1.5s infinite;
        }
        .water-wave {
            position: relative;
            overflow: hidden;
        }
        .water-wave::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 40%;
            animation: wave 8s infinite linear;
        }
        @keyframes wave {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .moisture-bar {
            height: 8px;
            border-radius: 4px;
            background: #e5e7eb;
            overflow: hidden;
            margin-top: 10px;
        }
        .moisture-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 1s ease-in-out;
        }
        .gauge-container {
            position: relative;
            width: 120px;
            height: 120px;
        }
        .gauge {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: conic-gradient(
                #10b981 0% 25%,
                #3b82f6 25% 50%,
                #f59e0b 50% 75%,
                #ef4444 75% 100%
            );
            mask: radial-gradient(transparent 55%, black 56%);
            position: relative;
        }
        .gauge-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            z-index: 100;
            overflow: hidden;
        }
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        .sensor-reading {
            animation: fadeIn 0.5s ease-in-out;
        }
        .water-animation {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1;
        }
        .water-drop {
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(59, 130, 246, 0.7);
            border-radius: 50%;
            animation: waterDrop 1.5s ease-in-out infinite;
        }
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #10b981;
        }
        input:checked + .slider:before {
            transform: translateX(30px);
        }
        .real-time-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .real-time-indicator.active .pulse-dot {
            animation: pulse 1s infinite;
        }
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
        }
    </style>
</head>
<body class="gradient-bg">
    <!-- Real-time indicator -->
    <div class="real-time-indicator active">
        <div class="pulse-dot"></div>
        <span>Live Data</span>
    </div>

    <!-- Top Navigation Bar -->
    <nav class="glass-card shadow-sm mx-4 mt-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left side: Logo / Title -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-tint text-primary-500 text-2xl mr-2 animate-float"></i>
                        <span class="font-bold text-xl text-gray-800">SmartIrrigation</span>
                    </div>
                </div>

                <!-- Right side: User Dropdown -->
                <div class="flex items-center">
                    <div class="dropdown relative">
                        <!-- Trigger -->
                        <button class="flex items-center space-x-3 focus:outline-none p-2 rounded-lg">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </button>

                        <!-- Dropdown menu -->
                        <div class="dropdown-menu">
                            <div class="p-4 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-700">Signed in as</p>
                                <p class="text-sm text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>

                            <!-- Logout -->
                            <div class="border-t border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8 text-center animate-fade-in">
            <h1 class="text-4xl font-bold text-gray-800 mb-2 flex items-center justify-center">
                <i class="fas fa-tint mr-3 text-primary-500 animate-float"></i> Smart Irrigation Dashboard
            </h1>
            <p class="text-gray-600">Real-time monitoring and control of your smart irrigation system</p>
            <div class="mt-2 text-sm text-gray-500">
                Welcome back, {{ auth()->user()->name }}! • 
                
            </div>
        </div>

        <!-- Status Messages -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                {{ session('status') }}
            </div>
        @endif

        <!-- System Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Moisture Level Card -->
            <div class="glass-card overflow-hidden card-hover animate-slide-in">
                <div class="p-6 bg-gradient-to-r from-blue-50 to-cyan-100 relative water-wave">
                    <div class="flex items-center">
                        <div class="rounded-full bg-white p-3 shadow-md mr-4">
                            <i class="fas fa-cloud-rain fa-lg text-blue-500"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-500">Soil Moisture</h3>
                            <div class="flex items-baseline">
                                <p id="moistureValue" class="text-2xl font-semibold text-gray-900">
                                    {{ number_format($latestReading->moisture_level, 1) }}%
                                </p>
                                <span id="moistureStatus" class="ml-2 text-xs font-medium px-2 py-1 rounded-full 
                                    {{ $latestReading->moisture_level > $settings->moisture_threshold ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $latestReading->moisture_level > $settings->moisture_threshold ? 'Optimal' : 'Water Needed' }}
                                </span>
                            </div>
                            <div class="moisture-bar mt-2">
                                <div id="moistureFill" class="moisture-fill bg-gradient-to-r from-green-400 to-blue-500" 
                                     style="width: {{ $latestReading->moisture_level }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute top-4 right-4">
                        <i class="fas fa-leaf text-green-400"></i>
                    </div>
                </div>
            </div>

            <!-- Pump Status Card -->
            <div class="glass-card overflow-hidden card-hover animate-slide-in" style="animation-delay: 0.1s">
                <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 relative">
                    <div class="flex items-center">
                        <div id="pumpIcon" class="rounded-full bg-white p-3 shadow-md mr-4 {{ $latestReading->pump_status ? 'pump-active' : '' }}">
                            <i class="fas fa-water fa-lg text-blue-500"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Pump Status</h3>
                            <p id="pumpStatusText" class="text-2xl font-semibold text-gray-900">
                                {{ $latestReading->pump_status ? 'RUNNING' : 'STOPPED' }}
                            </p>
                            <span id="pumpStatusBadge" class="text-xs font-medium px-2 py-1 rounded-full 
                                {{ $latestReading->pump_status ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $latestReading->pump_status ? 'Watering' : 'Idle' }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 flex space-x-2">
                        <span class="status-dot {{ $latestReading->pump_status ? 'bg-green-500 animate-pulse' : 'bg-gray-300' }}"></span>
                        <span class="text-xs text-gray-500">Last active: <span id="lastActiveTime">{{ $latestReading->created_at ? $latestReading->created_at->diffForHumans() : 'Never' }}</span></span>
                    </div>
                    @if($latestReading->pump_status)
                    <div class="water-animation">
                        <div class="water-drop" style="top: 20%; left: 30%; animation-delay: 0s;"></div>
                        <div class="water-drop" style="top: 40%; left: 60%; animation-delay: 0.5s;"></div>
                        <div class="water-drop" style="top: 60%; left: 40%; animation-delay: 1s;"></div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- System Mode Card -->
            <div class="glass-card overflow-hidden card-hover animate-slide-in" style="animation-delay: 0.2s">
                <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-100">
                    <div class="flex items-center">
                        <div class="rounded-full bg-white p-3 shadow-md mr-4">
                            <i class="fas fa-cogs fa-lg text-green-500"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">System Mode</h3>
                            <p id="systemModeText" class="text-2xl font-semibold text-gray-900">
                                {{ $settings->auto_mode ? 'AUTO' : 'MANUAL' }}
                            </p>
                            <span id="systemStatusBadge" class="text-xs font-medium px-2 py-1 rounded-full 
                                {{ $settings->system_enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $settings->system_enabled ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-center">
                        <div class="gauge-container">
                            <div class="gauge"></div>
                            <div class="gauge-center">
                                <span id="gaugeValue">{{ number_format($latestReading->moisture_level, 0) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manual Control Card -->
            <div class="glass-card overflow-hidden card-hover animate-slide-in" style="animation-delay: 0.3s">
                <div class="p-6 bg-gradient-to-r from-purple-50 to-indigo-100">
                    <div class="flex items-center">
                        <div class="rounded-full bg-white p-3 shadow-md mr-4">
                            <i class="fas fa-hand-pointer fa-lg text-purple-500"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Manual Control</h3>
                            <button id="manualWaterBtn" 
                                class="px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all duration-300 shadow-md flex items-center">
                                <i class="fas fa-play mr-2"></i> Start Watering
                            </button>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i> Press to manually activate the pump
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Moisture Chart -->
            <div class="glass-card overflow-hidden animate-fade-in">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Soil Moisture Trend</h3>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-history mr-1"></i> Last 20 readings
                        </div>
                    </div>
                    <div class="chart-container h-64">
                        <canvas id="moistureChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Water Usage Chart -->
            <div class="glass-card overflow-hidden animate-fade-in" style="animation-delay: 0.2s">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Weekly Water Usage</h3>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-tint mr-1 text-blue-400"></i> 
                            <span id="totalWater">Total: {{ number_format($statistics['total_water'], 1) }} L</span>
                        </div>
                    </div>
                    <div class="chart-container h-64">
                        <canvas id="waterChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="glass-card p-6 text-center card-hover">
                <div id="todayWater" class="text-2xl font-bold text-blue-600">{{ number_format($statistics['today']['water_used'], 1) }} L</div>
                <div class="text-sm text-gray-500">Water Used Today</div>
                <i class="fas fa-calendar-day text-blue-400 mt-2"></i>
            </div>
            
            <div class="glass-card p-6 text-center card-hover">
                <div id="todaySessions" class="text-2xl font-bold text-green-600">{{ $statistics['today']['watering_sessions'] }}</div>
                <div class="text-sm text-gray-500">Watering Sessions Today</div>
                <i class="fas fa-play-circle text-green-400 mt-2"></i>
            </div>
            
            <div class="glass-card p-6 text-center card-hover">
                <div id="weekWater" class="text-2xl font-bold text-purple-600">{{ number_format($statistics['this_week']['water_used'], 1) }} L</div>
                <div class="text-sm text-gray-500">Water This Week</div>
                <i class="fas fa-chart-line text-purple-400 mt-2"></i>
            </div>
            
            <div class="glass-card p-6 text-center card-hover">
                <div id="avgMoisture" class="text-2xl font-bold text-orange-600">{{ number_format($statistics['avg_moisture'], 1) }}%</div>
                <div class="text-sm text-gray-500">Average Moisture</div>
                <i class="fas fa-percentage text-orange-400 mt-2"></i>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="glass-card overflow-hidden animate-fade-in mb-8">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-clock-rotate-left mr-2 text-gray-500"></i> Recent Activity
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Time</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Type</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Moisture</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Pump Status</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Water Used</th>
                            </tr>
                        </thead>
                        <tbody id="recentReadingsBody" class="divide-y divide-gray-200">
                            @foreach($recentReadings as $reading)
                            <tr class="hover:bg-gray-50 sensor-reading">
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $reading->created_at ? $reading->created_at->format('H:i:s') : 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 capitalize">{{ $reading->reading_type }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($reading->moisture_level, 1) }}%</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $reading->pump_status ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $reading->pump_status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($reading->water_used, 2) }} L</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Settings Panel -->
        <div class="glass-card overflow-hidden animate-fade-in">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-sliders-h mr-2 text-gray-500"></i> System Settings
                </h3>
                <form action="{{ route('settings.update') }}" method="POST" id="settingsForm">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Moisture Threshold -->
                        <div>
                            <label for="moisture_threshold" class="block text-sm font-medium text-gray-700 mb-1">
                                Moisture Threshold (%)
                            </label>
                            <div class="relative">
                                <input type="range" 
                                       id="moisture_threshold" 
                                       name="moisture_threshold" 
                                       value="{{ $settings->moisture_threshold }}"
                                       min="0" 
                                       max="100" 
                                       step="1"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>0%</span>
                                    <span id="thresholdValue">{{ $settings->moisture_threshold }}%</span>
                                    <span>100%</span>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Water when moisture drops below this level</p>
                        </div>

                        <!-- Pump Duration -->
                        <div>
                            <label for="pump_duration" class="block text-sm font-medium text-gray-700 mb-1">
                                Pump Duration (seconds)
                            </label>
                            <div class="relative">
                                <input type="range" 
                                       id="pump_duration" 
                                       name="pump_duration" 
                                       value="{{ $settings->pump_duration }}"
                                       min="5" 
                                       max="120"
                                       step="5"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>5s</span>
                                    <span id="durationValue">{{ $settings->pump_duration }}s</span>
                                    <span>120s</span>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">How long the pump runs each cycle</p>
                        </div>

                        <!-- Auto Mode Toggle -->
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <div class="toggle-switch">
                                    <input type="checkbox" 
                                           id="auto_mode" 
                                           name="auto_mode" 
                                           value="1"
                                           {{ $settings->auto_mode ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </div>
                                <span class="ml-3 text-sm text-gray-700">Automatic Mode</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-500">System will water automatically when needed</p>
                        </div>

                        <!-- System Enabled Toggle -->
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <div class="toggle-switch">
                                    <input type="checkbox" 
                                           id="system_enabled" 
                                           name="system_enabled" 
                                           value="1"
                                           {{ $settings->system_enabled ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </div>
                                <span class="ml-3 text-sm text-gray-700">System Enabled</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-500">Enable/disable the entire irrigation system</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-lg hover:from-blue-600 hover:to-cyan-700 transition-all duration-300 shadow-md flex items-center">
                            <i class="fas fa-save mr-2"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-gray-500 text-sm">
            <p>Smart Irrigation System Dashboard • Last updated: <span id="lastUpdate">{{ now()->format('H:i:s') }}</span></p>
        </div>
    </div>

    <script>
        // Chart instances
        let moistureChart, waterChart;
        
        // Initialize charts
        function initializeCharts() {
            const moistureCtx = document.getElementById('moistureChart').getContext('2d');
            const waterCtx = document.getElementById('waterChart').getContext('2d');
            
            moistureChart = new Chart(moistureCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Soil Moisture (%)',
                        data: [],
                        borderColor: '#0ea5e9',
                        backgroundColor: 'rgba(14, 165, 233, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });
            
            waterChart = new Chart(waterCtx, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Water Usage (L)',
                        data: [0, 0, 0, 0, 0, 0, 0],
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
        
        // Update slider values display
        document.getElementById('moisture_threshold').addEventListener('input', function() {
            document.getElementById('thresholdValue').textContent = this.value + '%';
        });
        
        document.getElementById('pump_duration').addEventListener('input', function() {
            document.getElementById('durationValue').textContent = this.value + 's';
        });

        // Real-time data updates
        let realTimeInterval;
        
        function startRealTimeMonitoring() {
            // Clear any existing interval
            if (realTimeInterval) {
                clearInterval(realTimeInterval);
            }
            
            // Fetch immediately
            fetchLatestData();
            
            // Then every second
            realTimeInterval = setInterval(fetchLatestData, 1000);
        }

        async function fetchLatestData() {
            try {
                const response = await fetch('{{ route("sensor.latest") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    cache: 'no-cache'
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    updateDashboard(data.data);
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                // Update indicator to show error
                const indicator = document.querySelector('.real-time-indicator');
                indicator.classList.remove('active');
                indicator.innerHTML = '<div class="pulse-dot bg-red-500"></div><span>Connection Error</span>';
                
                // Try to reconnect after 5 seconds
                setTimeout(startRealTimeMonitoring, 5000);
            }
        }

        function updateDashboard(data) {
            if (!data) return;
            
            // Update moisture level
            const moistureValue = parseFloat(data.moisture_level).toFixed(1);
            document.getElementById('moistureValue').textContent = moistureValue + '%';
            document.getElementById('moistureFill').style.width = moistureValue + '%';
            
            // Update gauge value safely
            const gaugeElement = document.getElementById('gaugeValue');
            if (gaugeElement) {
                gaugeElement.textContent = Math.round(data.moisture_level) + '%';
            }
            
            // Update moisture status
            const threshold = parseInt(document.getElementById('moisture_threshold').value) || 30;
            const moistureStatus = document.getElementById('moistureStatus');
            
            if (data.moisture_level > threshold) {
                if (moistureStatus) {
                    moistureStatus.textContent = 'Optimal';
                    moistureStatus.className = 'ml-2 text-xs font-medium px-2 py-1 rounded-full bg-green-100 text-green-800';
                }
                document.getElementById('moistureFill').className = 'moisture-fill bg-gradient-to-r from-green-400 to-blue-500';
            } else {
                if (moistureStatus) {
                    moistureStatus.textContent = 'Water Needed';
                    moistureStatus.className = 'ml-2 text-xs font-medium px-2 py-1 rounded-full bg-red-100 text-red-800';
                }
                document.getElementById('moistureFill').className = 'moisture-fill bg-gradient-to-r from-yellow-400 to-red-500';
            }
            
            // Update pump status
            updatePumpStatus(data.pump_status);

            // Update last updated time
            const now = new Date();
            const lastUpdateElement = document.getElementById('lastUpdate');
            if (lastUpdateElement) {
                lastUpdateElement.textContent = now.toLocaleTimeString();
            }
            
            // Update last active time
            if (data.created_at) {
                const lastActiveElement = document.getElementById('lastActiveTime');
                if (lastActiveElement) {
                    // Convert to relative time
                    const created = new Date(data.created_at);
                    const diffMs = now - created;
                    const diffMins = Math.floor(diffMs / 60000);
                    const diffHours = Math.floor(diffMs / 3600000);
                    
                    if (diffMins < 1) {
                        lastActiveElement.textContent = 'Just now';
                    } else if (diffMins < 60) {
                        lastActiveElement.textContent = `${diffMins} minute${diffMins !== 1 ? 's' : ''} ago`;
                    } else if (diffHours < 24) {
                        lastActiveElement.textContent = `${diffHours} hour${diffHours !== 1 ? 's' : ''} ago`;
                    } else {
                        lastActiveElement.textContent = created.toLocaleDateString();
                    }
                }
            }
            
            // Update charts with new data
            updateCharts(data);
            
            // Update statistics
            updateStatistics(data.statistics);
            
            // Update recent readings
            updateRecentReadings(data.recent_readings);
        }
        
        function updateCharts(data) {
            if (!moistureChart || !waterChart) return;
            
            // Update moisture chart
            const now = new Date();
            const timeLabel = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            // Add new data point
            moistureChart.data.labels.push(timeLabel);
            moistureChart.data.datasets[0].data.push(data.moisture_level);
            
            // Keep only last 20 points
            if (moistureChart.data.labels.length > 20) {
                moistureChart.data.labels.shift();
                moistureChart.data.datasets[0].data.shift();
            }
            
            moistureChart.update('none');
            
            // Update water chart with weekly data if available
            if (data.weekly_water_usage) {
                waterChart.data.datasets[0].data = data.weekly_water_usage;
                waterChart.update('none');
            }
        }
        
        function updateStatistics(stats) {
            if (!stats) return;
            
            // Update statistics cards
            if (stats.today_water) {
                document.getElementById('todayWater').textContent = `${parseFloat(stats.today_water).toFixed(1)} L`;
            }
            
            if (stats.today_sessions) {
                document.getElementById('todaySessions').textContent = stats.today_sessions;
            }
            
            if (stats.week_water) {
                document.getElementById('weekWater').textContent = `${parseFloat(stats.week_water).toFixed(1)} L`;
            }
            
            if (stats.avg_moisture) {
                document.getElementById('avgMoisture').textContent = `${parseFloat(stats.avg_moisture).toFixed(1)}%`;
            }
            
            if (stats.total_water) {
                document.getElementById('totalWater').textContent = `Total: ${parseFloat(stats.total_water).toFixed(1)} L`;
            }
        }
        
        function updateRecentReadings(readings) {
            if (!readings || !Array.isArray(readings)) return;
            
            const tbody = document.getElementById('recentReadingsBody');
            if (!tbody) return;
            
            // Clear existing rows
            tbody.innerHTML = '';
            
            // Add new rows
            readings.forEach(reading => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 sensor-reading';
                
                const time = reading.created_at ? 
                    new Date(reading.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second:'2-digit'}) : 
                    'N/A';
                
                row.innerHTML = `
                    <td class="px-4 py-3 text-sm text-gray-900">${time}</td>
                    <td class="px-4 py-3 text-sm text-gray-900 capitalize">${reading.reading_type || 'sensor'}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${parseFloat(reading.moisture_level).toFixed(1)}%</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            ${reading.pump_status ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'}">
                            ${reading.pump_status ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">${parseFloat(reading.water_used || 0).toFixed(2)} L</td>
                `;
                
                tbody.appendChild(row);
            });
        }

        function updatePumpStatus(isRunning) {
            const pumpStatusText = document.getElementById('pumpStatusText');
            const pumpStatusBadge = document.getElementById('pumpStatusBadge');
            const pumpIcon = document.getElementById('pumpIcon');
            
            if (pumpStatusText) {
                pumpStatusText.textContent = isRunning ? 'RUNNING' : 'STOPPED';
            }
            
            if (pumpStatusBadge) {
                pumpStatusBadge.textContent = isRunning ? 'Watering' : 'Idle';
                pumpStatusBadge.className = isRunning ? 
                    'text-xs font-medium px-2 py-1 rounded-full bg-yellow-100 text-yellow-800' : 
                    'text-xs font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-800';
            }
            
            if (pumpIcon) {
                if (isRunning) {
                    pumpIcon.classList.add('pump-active');
                    // Add water animation if not present
                    const pumpCard = document.querySelector('#pumpIcon').closest('.bg-gradient-to-r');
                    if (pumpCard && !pumpCard.querySelector('.water-animation')) {
                        const waterAnimation = document.createElement('div');
                        waterAnimation.className = 'water-animation';
                        waterAnimation.innerHTML = `
                            <div class="water-drop" style="top: 20%; left: 30%; animation-delay: 0s;"></div>
                            <div class="water-drop" style="top: 40%; left: 60%; animation-delay: 0.5s;"></div>
                            <div class="water-drop" style="top: 60%; left: 40%; animation-delay: 1s;"></div>
                        `;
                        pumpCard.appendChild(waterAnimation);
                    }
                } else {
                    pumpIcon.classList.remove('pump-active');
                    // Remove water animation if present
                    const pumpCard = document.querySelector('#pumpIcon').closest('.bg-gradient-to-r');
                    if (pumpCard) {
                        const waterAnim = pumpCard.querySelector('.water-animation');
                        if (waterAnim) {
                            waterAnim.remove();
                        }
                    }
                }
            }
        }

        // Manual watering button with improved error handling
        document.getElementById('manualWaterBtn').addEventListener('click', async function() {
            const button = this;
            const originalText = button.innerHTML;
            
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-sync-alt fa-spin mr-2"></i> Starting...';
            button.classList.add('opacity-50');
            
            try {
                const response = await fetch('{{ route("water.manual") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({})
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || `HTTP error! status: ${response.status}`);
                }

                if (data.success) {
                    showNotification('Manual watering started successfully!', 'success');
                    
                    // Immediately update the UI
                    updatePumpStatus(true);
                    
                } else {
                    showNotification(data.message || 'Failed to start watering', 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                showNotification('Error starting manual watering: ' + error.message, 'error');
            } finally {
                setTimeout(() => {
                    button.disabled = false;
                    button.innerHTML = originalText;
                    button.classList.remove('opacity-50');
                }, 2000);
            }
        });

        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.custom-notification');
            existingNotifications.forEach(notif => notif.remove());

            const notification = document.createElement('div');
            notification.className = `custom-notification fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg border-l-4 ${
                type === 'success' ? 'bg-green-100 border-green-500 text-green-700' :
                type === 'error' ? 'bg-red-100 border-red-500 text-red-700' :
                'bg-blue-100 border-blue-500 text-blue-700'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${
                        type === 'success' ? 'fa-check-circle' :
                        type === 'error' ? 'fa-exclamation-circle' :
                        'fa-info-circle'
                    } mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 5000);
        }

        // Initialize everything when page loads
        document.addEventListener('DOMContentLoaded', () => {
            initializeCharts();
            startRealTimeMonitoring();
            
            // Also fetch when page becomes visible
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    fetchLatestData();
                }
            });
        });
    </script>
</body>
</html>