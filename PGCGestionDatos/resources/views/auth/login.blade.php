<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SciGOP - Iniciar Sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .login-container {
            background: linear-gradient(135deg, #f59e0b, #d97706, #92400e);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .card-animation {
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo-animation {
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0,0,0);
            }
            40%, 43% {
                transform: translate3d(0, -10px, 0);
            }
            70% {
                transform: translate3d(0, -5px, 0);
            }
            90% {
                transform: translate3d(0, -2px, 0);
            }
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: scale(1.02);
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.3);
        }
        
        .btn-hover {
            transition: all 0.3s ease;
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="login-container min-h-screen flex items-center justify-center p-4">
    <div class="card-animation max-w-md w-full">
        <!-- Logo y título -->
        <div class="text-center mb-8">
            <div class="logo-animation inline-block mb-4">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-4xl shadow-lg">
                    ☕
                </div>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">SciGOP</h1>
            <p class="text-orange-100">Sistema de Gestión Empresarial</p>
        </div>

        <!-- Formulario de login -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-amber-800 text-center mb-6">Iniciar Sesión</h2>
            
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex">
                        <span class="text-red-600 mr-2">❌</span>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-red-800 text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-6">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-amber-700 mb-2">
                        Correo Electrónico
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
                        class="input-focus w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200"
                        placeholder="admin@scigop.com"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-amber-700 mb-2">
                        Contraseña
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="input-focus w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200"
                        placeholder="••••••••"
                    >
                </div>

                <!-- Remember me -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember"
                        class="w-4 h-4 text-amber-600 bg-orange-100 border-orange-300 rounded focus:ring-amber-500"
                    >
                    <label for="remember" class="ml-2 block text-sm text-amber-700">
                        Recordarme
                    </label>
                </div>

                <!-- Submit button -->
                <button 
                    type="submit" 
                    class="btn-hover w-full bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300"
                >
                    Iniciar Sesión
                </button>
            </form>

            <!-- Register link -->
            <div class="mt-6 text-center">
                <p class="text-amber-700">
                    ¿No tienes una cuenta? 
                    <a href="{{ route('register') }}" class="text-orange-600 hover:text-orange-800 font-medium underline">
                        Regístrate aquí
                    </a>
                </p>
            </div>
        </div>

        <!-- Demo credentials -->
        <div class="float-animation mt-6 bg-white/10 backdrop-blur-sm rounded-lg p-4 text-white text-sm">
            <h4 class="font-semibold mb-2">🔑 Credenciales de prueba:</h4>
            <p><strong>Email:</strong> admin@scigop.com</p>
            <p><strong>Contraseña:</strong> scigop2024</p>
        </div>
    </div>
</body>
</html>
