<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Smart Irrigation System</title>
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
                        'drip': 'drip 2s ease-in infinite',
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
                        },
                        drip: {
                            '0%': { transform: 'translateY(0)', opacity: '1' },
                            '80%': { transform: 'translateY(40px)', opacity: '1' },
                            '100%': { transform: 'translateY(40px)', opacity: '0' }
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
        
        .drip-animation {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1;
        }
        
        .drip {
            position: absolute;
            width: 4px;
            height: 20px;
            background: linear-gradient(to bottom, rgba(14, 165, 233, 0.8), rgba(14, 165, 233, 0.4));
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            animation: drip 2s ease-in infinite;
        }
        
        .form-input {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(14, 165, 233, 0.2);
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            background: rgba(255, 255, 255, 0.9);
            border-color: rgba(14, 165, 233, 0.5);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }
        
        .input-error {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .strength-0 { width: 0%; background: #ef4444; }
        .strength-1 { width: 25%; background: #ef4444; }
        .strength-2 { width: 50%; background: #f59e0b; }
        .strength-3 { width: 75%; background: #10b981; }
        .strength-4 { width: 100%; background: #10b981; }
        
        .pulse-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #10b981;
            animation: pulse 2s infinite;
        }
        
        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
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
    </div>
    
    <div class="water-animation">
        <div class="water-drop" style="top: 15%; left: 15%; animation-delay: 0s;"></div>
        <div class="water-drop" style="top: 25%; left: 75%; animation-delay: 0.5s;"></div>
        <div class="water-drop" style="top: 65%; left: 25%; animation-delay: 1s;"></div>
        <div class="ripple" style="top: 50%; left: 50%; animation-delay: 0s;"></div>
        <div class="ripple" style="top: 30%; left: 40%; animation-delay: 1.5s;"></div>
    </div>
    
    <div class="drip-animation">
        <div class="drip" style="left: 20%; animation-delay: 0.5s;"></div>
        <div class="drip" style="left: 50%; animation-delay: 1.2s;"></div>
        <div class="drip" style="left: 80%; animation-delay: 0.8s;"></div>
    </div>

    <!-- Navigation -->
    <nav class="glass-card shadow-sm mx-4 mt-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex-shrink-0 flex items-center">
                        <i class="fas fa-tint text-primary-500 text-2xl mr-2 animate-float"></i>
                        <span class="font-bold text-xl text-gray-800">SmartIrrigation</span>
                    </a>
                </div>

                <div class="flex items-center">
                    <a href="/login" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        Already have an account?
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <!-- Registration Form -->
            <div class="w-full md:w-1/2 lg:w-2/5">
                <div class="glass-card p-8 card-hover animate-slide-in">
                    <div class="text-center mb-8">
                        <div class="flex justify-center mb-4">
                            <div class="relative">
                                <i class="fas fa-tint text-primary-500 text-4xl hero-plant"></i>
                                <i class="fas fa-leaf text-green-500 text-2xl absolute -top-1 -right-1 animate-bounce-slow"></i>
                            </div>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-800">Create Your Account</h2>
                        <p class="text-gray-600 mt-2">Join Smart Irrigation and optimize your water usage</p>
                    </div>

                 <form method="POST" action="{{ route('register') }}" id="registrationForm">
    @csrf
    
    <!-- Name -->
    <div class="mb-6">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-user text-gray-400"></i>
            </div>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required 
                   class="form-input block w-full pl-10 pr-3 py-3 rounded-lg"
                   placeholder="Enter your full name">
        </div>
        @error('name')
            <div class="input-error">{{ $message }}</div>
        @enderror
        <div id="name-error" class="input-error hidden">
            Please enter your full name
        </div>
    </div>

    <!-- Email Address -->
    <div class="mb-6">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400"></i>
            </div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                   class="form-input block w-full pl-10 pr-3 py-3 rounded-lg"
                   placeholder="Enter your email address">
        </div>
        @error('email')
            <div class="input-error">{{ $message }}</div>
        @enderror
        <div id="email-error" class="input-error hidden">
            Please enter a valid email address
        </div>
    </div>

    <!-- Password -->
    <div class="mb-6">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input id="password" type="password" name="password" required 
                   class="form-input block w-full pl-10 pr-10 py-3 rounded-lg"
                   placeholder="Create a strong password">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <div class="password-strength strength-0" id="passwordStrength"></div>
        @error('password')
            <div class="input-error">{{ $message }}</div>
        @enderror
        <div id="password-error" class="input-error hidden">
            Password must be at least 8 characters with uppercase, lowercase, number, and special character
        </div>
    </div>

    <!-- Confirm Password -->
    <div class="mb-6">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input id="password_confirmation" type="password" name="password_confirmation" required 
                   class="form-input block w-full pl-10 pr-3 py-3 rounded-lg"
                   placeholder="Confirm your password">
        </div>
        <div id="password-confirm-error" class="input-error hidden">
            Passwords do not match
        </div>
    </div>

    <!-- Terms Agreement -->
    <div class="mb-6">
        <label class="flex items-center">
            <input type="checkbox" id="terms" name="terms" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
            <span class="ml-2 text-sm text-gray-600">
                I agree to the <a href="#" class="text-primary-600 hover:text-primary-500">Terms of Service</a> and <a href="#" class="text-primary-600 hover:text-primary-500">Privacy Policy</a>
            </span>
        </label>
        <div id="terms-error" class="input-error hidden">
            You must agree to the terms and conditions
        </div>
    </div>

    <div class="flex items-center justify-between mt-8">
        <a href="{{ route('login') }}" class="text-sm text-primary-600 hover:text-primary-500 font-medium">
            Already have an account?
        </a>

        <button type="submit" class="btn-primary inline-flex items-center px-6 py-3 text-white rounded-lg font-medium">
            <i class="fas fa-user-plus mr-2"></i> Create Account
        </button>
    </div>
</form>
                </div>
            </div>

            <!-- Benefits Section -->
            <div class="w-full md:w-1/2 lg:w-3/5 mt-8 md:mt-0">
                <div class="glass-card p-8 card-hover animate-slide-in" style="animation-delay: 0.1s">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Why Join Smart Irrigation?</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="feature-icon bg-blue-100">
                                <i class="fas fa-tint text-blue-500"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">Save Up to 50% Water</h4>
                                <p class="text-gray-600 mt-1">Our smart sensors optimize watering based on soil conditions and weather forecasts.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="feature-icon bg-green-100">
                                <i class="fas fa-chart-line text-green-500"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">Increase Crop Yield</h4>
                                <p class="text-gray-600 mt-1">Optimal watering leads to healthier plants and higher productivity.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="feature-icon bg-purple-100">
                                <i class="fas fa-mobile-alt text-purple-500"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">Remote Monitoring</h4>
                                <p class="text-gray-600 mt-1">Control and monitor your irrigation system from anywhere using our mobile app.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="feature-icon bg-yellow-100">
                                <i class="fas fa-bolt text-yellow-500"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">Reduce Energy Costs</h4>
                                <p class="text-gray-600 mt-1">Efficient watering means less pump operation and lower electricity bills.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center text-sm text-gray-500">
                            <div class="pulse-dot mr-2"></div>
                            <span>Join 5,000+ farmers already using our system</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p>&copy; 2023 Smart Irrigation System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Password visibility toggle
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            let strength = 0;
            
            // Check password length
            if (password.length >= 8) strength++;
            
            // Check for uppercase letters
            if (/[A-Z]/.test(password)) strength++;
            
            // Check for lowercase letters
            if (/[a-z]/.test(password)) strength++;
            
            // Check for numbers
            if (/[0-9]/.test(password)) strength++;
            
            // Check for special characters
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // Update strength bar
            strengthBar.className = 'password-strength strength-' + Math.min(strength, 4);
        });
        
        // Form validation
        
        // Form validation
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    let isValid = true;
    
    // Validate name
    const name = document.getElementById('name').value;
    const nameError = document.getElementById('name-error');
    if (name.trim() === '') {
        nameError.classList.remove('hidden');
        isValid = false;
    } else {
        nameError.classList.add('hidden');
    }
    
    // Validate email
    const email = document.getElementById('email').value;
    const emailError = document.getElementById('email-error');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        emailError.classList.remove('hidden');
        isValid = false;
    } else {
        emailError.classList.add('hidden');
    }
    
    // Validate password
    const password = document.getElementById('password').value;
    const passwordError = document.getElementById('password-error');
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordRegex.test(password)) {
        passwordError.classList.remove('hidden');
        isValid = false;
    } else {
        passwordError.classList.add('hidden');
    }
    
    // Validate password confirmation
    const passwordConfirm = document.getElementById('password_confirmation').value;
    const passwordConfirmError = document.getElementById('password-confirm-error');
    if (password !== passwordConfirm) {
        passwordConfirmError.classList.remove('hidden');
        isValid = false;
    } else {
        passwordConfirmError.classList.add('hidden');
    }
    
    // Validate terms agreement
    const terms = document.getElementById('terms').checked;
    const termsError = document.getElementById('terms-error');
    if (!terms) {
        termsError.classList.remove('hidden');
        isValid = false;
    } else {
        termsError.classList.add('hidden');
    }
    
    if (!isValid) {
        e.preventDefault(); // Only prevent default if validation fails
    }
    // If validation passes, the form will submit to Breeze normally
});
        // Initialize animations when page loads
        document.addEventListener('DOMContentLoaded', () => {
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