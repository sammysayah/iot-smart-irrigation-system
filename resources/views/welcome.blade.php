<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Irrigation System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
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
                        'wave': 'wave 12s linear infinite',
                        'ripple': 'ripple 6s linear infinite',
                        'grow': 'grow 8s ease-in-out infinite',
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
                        },
                        wave: {
                            '0%': { transform: 'translateX(0) translateZ(0) scaleY(1)' },
                            '50%': { transform: 'translateX(-25%) translateZ(0) scaleY(0.55)' },
                            '100%': { transform: 'translateX(-50%) translateZ(0) scaleY(1)' }
                        },
                        ripple: {
                            '0%': { transform: 'scale(0.8)', opacity: '1' },
                            '100%': { transform: 'scale(2.4)', opacity: '0' }
                        },
                        grow: {
                            '0%, 100%': { transform: 'scale(1)' },
                            '50%': { transform: 'scale(1.05)' }
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
    position: relative;
    /* overflow: hidden; */  /* REMOVE THIS */
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
        
        .floating-leaves {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1;
        }
        
        .leaf {
            position: absolute;
            font-size: 24px;
            opacity: 0.7;
            animation: float 6s ease-in-out infinite;
        }
        
        .water-drop {
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(59, 130, 246, 0.7);
            border-radius: 50%;
            animation: waterDrop 1.5s ease-in-out infinite;
        }
        
        .ripple {
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(14, 165, 233, 0.6);
            border-radius: 50%;
            animation: ripple 3s linear infinite;
        }
        
        .hero-plant {
            animation: grow 8s ease-in-out infinite;
            transform-origin: center bottom;
        }
        
        .feature-icon {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(14, 165, 233, 0.3);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .stats-counter {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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
        
        .parallax-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%230ea5e9' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            animation: wave 120s linear infinite;
        }
    </style>
</head>
<body class="gradient-bg">
    <!-- Background Elements -->
    <div class="parallax-bg"></div>
    
    <div class="floating-leaves">
        <i class="leaf fas fa-leaf text-green-400" style="top: 10%; left: 5%; animation-delay: 0s;"></i>
        <i class="leaf fas fa-leaf text-green-500" style="top: 20%; left: 90%; animation-delay: 1s;"></i>
        <i class="leaf fas fa-seedling text-green-300" style="top: 60%; left: 10%; animation-delay: 2s;"></i>
        <i class="leaf fas fa-leaf text-green-400" style="top: 80%; left: 80%; animation-delay: 3s;"></i>
        <i class="leaf fas fa-seedling text-green-500" style="top: 40%; left: 70%; animation-delay: 4s;"></i>
    </div>
    
    <div class="water-animation">
        <div class="water-drop" style="top: 15%; left: 15%; animation-delay: 0s;"></div>
        <div class="water-drop" style="top: 25%; left: 75%; animation-delay: 0.5s;"></div>
        <div class="water-drop" style="top: 65%; left: 25%; animation-delay: 1s;"></div>
        <div class="ripple" style="top: 50%; left: 50%; animation-delay: 0s;"></div>
        <div class="ripple" style="top: 30%; left: 40%; animation-delay: 1.5s;"></div>
        <div class="ripple" style="top: 70%; left: 60%; animation-delay: 3s;"></div>
    </div>

    <!-- Navigation -->
    @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            @auth
                <a href="{{ url('/dashboard') }}" class="glass-card inline-flex items-center px-4 py-2 font-semibold text-gray-700 hover:text-gray-900 transition-colors">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="glass-card inline-flex items-center px-4 py-2 font-semibold text-gray-700 hover:text-gray-900 transition-colors mr-2">
                    <i class="fas fa-sign-in-alt mr-2"></i> Log in
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary inline-flex items-center px-4 py-2 font-semibold text-white rounded-lg">
                        <i class="fas fa-user-plus mr-2"></i> Register
                    </a>
                @endif
            @endauth
        </div>
    @endif

    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center animate-fade-in">
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <i class="fas fa-tint text-primary-500 text-6xl hero-plant"></i>
                    <i class="fas fa-leaf text-green-500 text-4xl absolute -top-2 -right-2 animate-bounce-slow"></i>
                </div>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold text-gray-800 mb-4">
                Smart<span class="text-primary-500">Irrigation</span>
            </h1>
            
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Revolutionize your farming with our intelligent irrigation system. Save water, increase yields, and monitor your crops in real-time.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-lg text-lg font-semibold">
                        <i class="fas fa-tachometer-alt mr-2"></i> Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-lg text-lg font-semibold">
                        <i class="fas fa-rocket mr-2"></i> Get Started
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary inline-flex items-center px-6 py-3 rounded-lg text-lg font-semibold">
                        <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                    </a>
                @endauth
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
            <div class="glass-card p-6 text-center card-hover animate-slide-in">
                <div class="stats-counter mb-2" id="waterSaved">0L</div>
                <div class="text-sm text-gray-600">Water Saved</div>
                <i class="fas fa-tint text-blue-400 mt-2"></i>
            </div>
            
            <div class="glass-card p-6 text-center card-hover animate-slide-in" style="animation-delay: 0.1s">
                <div class="stats-counter mb-2" id="cropsMonitored">0</div>
                <div class="text-sm text-gray-600">Crops Monitored</div>
                <i class="fas fa-seedling text-green-400 mt-2"></i>
            </div>
            
            <div class="glass-card p-6 text-center card-hover animate-slide-in" style="animation-delay: 0.2s">
                <div class="stats-counter mb-2" id="energySaved">0%</div>
                <div class="text-sm text-gray-600">Energy Saved</div>
                <i class="fas fa-bolt text-yellow-400 mt-2"></i>
            </div>
            
            <div class="glass-card p-6 text-center card-hover animate-slide-in" style="animation-delay: 0.3s">
                <div class="stats-counter mb-2" id="satisfactionRate">0%</div>
                <div class="text-sm text-gray-600">Satisfaction Rate</div>
                <i class="fas fa-smile text-purple-400 mt-2"></i>
            </div>
        </div>

        <!-- Features Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Why Choose Smart Irrigation?</h2>
            <p class="text-gray-600 text-center mb-8">Our system offers cutting-edge features for modern agriculture</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass-card p-6 feature-card card-hover">
                    <div class="feature-icon bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-cloud-rain text-blue-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center">Smart Watering</h3>
                    <p class="text-gray-600 text-center">Automated irrigation based on real-time soil moisture data and weather forecasts.</p>
                </div>
                
                <div class="glass-card p-6 feature-card card-hover">
                    <div class="feature-icon bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-mobile-alt text-green-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center">Remote Monitoring</h3>
                    <p class="text-gray-600 text-center">Monitor and control your irrigation system from anywhere using our mobile app.</p>
                </div>
                
                <div class="glass-card p-6 feature-card card-hover">
                    <div class="feature-icon bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-chart-line text-purple-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center">Data Analytics</h3>
                    <p class="text-gray-600 text-center">Gain insights into water usage, plant health, and optimization opportunities.</p>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="glass-card p-8 mb-16">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">How It Works</h2>
            
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="text-center mb-8 md:mb-0 md:w-1/4">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-sensor text-blue-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">1. Install Sensors</h3>
                    <p class="text-gray-600">Place soil moisture sensors in your fields</p>
                </div>
                
                <div class="hidden md:block">
                    <i class="fas fa-arrow-right text-gray-400 text-2xl"></i>
                </div>
                
                <div class="text-center mb-8 md:mb-0 md:w-1/4">
                    <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-microchip text-green-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">2. Connect System</h3>
                    <p class="text-gray-600">Link sensors to our smart irrigation controller</p>
                </div>
                
                <div class="hidden md:block">
                    <i class="fas fa-arrow-right text-gray-400 text-2xl"></i>
                </div>
                
                <div class="text-center md:w-1/4">
                    <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tint text-purple-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">3. Automate & Save</h3>
                    <p class="text-gray-600">System waters plants only when needed</p>
                </div>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">What Our Users Say</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="glass-card p-6 card-hover">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold mr-4">
                            CM
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Christopher Muriwa</h4>
                            <p class="text-sm text-gray-600">Farm Owner</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Smart Irrigation reduced my water usage by 40% while improving crop yield. The dashboard is incredibly intuitive!"</p>
                </div>
                
                <div class="glass-card p-6 card-hover">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-4">
                            LT
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Luka Thipa</h4>
                            <p class="text-sm text-gray-600">Vineyard Manager</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"The real-time monitoring feature saved my grapes during a heatwave. I can't imagine farming without this system now."</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center glass-card p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Ready to Transform Your Irrigation?</h2>
            <p class="text-gray-600 mb-6 max-w-2xl mx-auto">Join thousands of farmers who are already saving water, time, and money with our smart irrigation system.</p>
            
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-lg text-lg font-semibold">
                    <i class="fas fa-tachometer-alt mr-2"></i> Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-lg text-lg font-semibold mr-4">
                    <i class="fas fa-rocket mr-2"></i> Start Free Trial
                </a>
                <a href="{{ route('login') }}" class="btn-secondary inline-flex items-center px-6 py-3 rounded-lg text-lg font-semibold">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                </a>
            @endauth
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Smart Irrigation</h3>
                    <p class="text-gray-400">Revolutionizing agriculture through intelligent water management solutions.</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Home</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Features</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">About Us</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Resources</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Support</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Community</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2">
                        <li class="text-gray-400"><i class="fas fa-envelope mr-2"></i> sayahsamson@gamil.com</li>
                        <li class="text-gray-400"><i class="fas fa-envelope mr-2"></i> abdulrazaq@gamil.com</li>

                        <li class="text-gray-400"><i class="fas fa-phone mr-2"></i>  (+265) 881-481-387</li>
                        <li class="text-gray-400"><i class="fas fa-phone mr-2"></i>  (+265) 997-013-014</li>
                        <li class="text-gray-400"><i class="fas fa-map-marker-alt mr-2"></i> Chipembere Highway, MUBAS Agriculture City</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date('Y'); ?> Smart Irrigation System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Animated counters
        function animateCounter(elementId, targetValue, suffix = '') {
            let current = 0;
            const increment = targetValue / 100;
            const element = document.getElementById(elementId);
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= targetValue) {
                    current = targetValue;
                    clearInterval(timer);
                }
                element.textContent = Math.round(current) + suffix;
            }, 20);
        }
        
        // Initialize animations when page loads
        document.addEventListener('DOMContentLoaded', () => {
            // Animate stats counters
            setTimeout(() => {
                animateCounter('waterSaved', 12500, 'L');
                animateCounter('cropsMonitored', 8500);
                animateCounter('energySaved', 35, '%');
                animateCounter('satisfactionRate', 98, '%');
            }, 500);
            
            // Add scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in');
                    }
                });
            }, observerOptions);
            
            // Observe all cards for animation
            document.querySelectorAll('.glass-card').forEach(card => {
                observer.observe(card);
            });
        });
    </script>
</body>
</html>