<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SciGOP - Registro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .register-container {
            background: linear-gradient(135deg, #059669, #047857, #064e3b);
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
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: scale(1.02);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
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
        
        .step-animation {
            animation: stepIn 0.5s ease-out forwards;
            opacity: 0;
            transform: translateX(20px);
        }
        
        .step-animation:nth-child(1) { animation-delay: 0.1s; }
        .step-animation:nth-child(2) { animation-delay: 0.2s; }
        .step-animation:nth-child(3) { animation-delay: 0.3s; }
        .step-animation:nth-child(4) { animation-delay: 0.4s; }
        
        @keyframes stepIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="register-container min-h-screen flex items-center justify-center p-4">
    <div class="card-animation max-w-md w-full">
        <!-- Logo y título -->
        <div class="text-center mb-8">
            <div class="logo-animation inline-block mb-4">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-4xl shadow-lg">
                    🚀
                </div>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">SciGOP</h1>
            <p class="text-emerald-100">¡Únete a nosotros!</p>
        </div>

        <!-- Formulario de registro -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-emerald-800 text-center mb-6">Crear Cuenta</h2>
            
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

            <form method="POST" action="{{ route('register.attempt') }}" class="space-y-6">
                @csrf
                
                <!-- Nombre -->
                <div class="step-animation">
                    <label for="nombre" class="block text-sm font-medium text-emerald-700 mb-2">
                        Nombre Completo
                    </label>
                    <input 
                        type="text" 
                        id="nombre" 
                        name="nombre" 
                        value="{{ old('nombre') }}"
                        required
                        class="input-focus w-full px-4 py-3 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                        placeholder="Juan Pérez"
                    >
                </div>

                <!-- Email -->
                <div class="step-animation">
                    <label for="email" class="block text-sm font-medium text-emerald-700 mb-2">
                        Correo Electrónico
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
                        class="input-focus w-full px-4 py-3 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                        placeholder="tu@email.com"
                    >
                </div>

                <!-- Password -->
                <div class="step-animation">
                    <label for="password" class="block text-sm font-medium text-emerald-700 mb-2">
                        Contraseña
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="input-focus w-full px-4 py-3 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                        placeholder="••••••••"
                        minlength="6"
                    >
                    <p class="text-xs text-emerald-600 mt-1">Mínimo 6 caracteres</p>
                </div>

                <!-- Confirm Password -->
                <div class="step-animation">
                    <label for="password_confirmation" class="block text-sm font-medium text-emerald-700 mb-2">
                        Confirmar Contraseña
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required
                        class="input-focus w-full px-4 py-3 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200"
                        placeholder="••••••••"
                    >
                </div>

                <!-- Submit button -->
                <button 
                    type="submit" 
                    class="btn-hover w-full bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300"
                >
                    Crear Cuenta
                </button>
            </form>

            <!-- Login link -->
            <div class="mt-6 text-center">
                <p class="text-emerald-700">
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}" class="text-green-600 hover:text-green-800 font-medium underline">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </div>

        <!-- Benefits -->
        <div class="float-animation mt-6 bg-white/10 backdrop-blur-sm rounded-lg p-4 text-white text-sm">
            <h4 class="font-semibold mb-2">✨ Beneficios de registrarte:</h4>
            <ul class="space-y-1">
                <li>• Sistema completo de gestión</li>
                <li>• Control de inventario</li>
                <li>• Reportes detallados</li>
                <li>• Interfaz moderna y fácil</li>
            </ul>
        </div>
    </div>
</body>
</html>
