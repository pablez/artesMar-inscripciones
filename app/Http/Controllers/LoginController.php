<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        // Validar los datos
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255|regex:/^[A-ZÁÉÍÓÚÑ][a-zA-ZáéíóúÁÉÍÓÚñÑ ]+\s+[A-ZÁÉÍÓÚÑ][a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/',
            'email'    => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:6|max:255',
        ], [
            'name.regex'    => 'Debe ingresar nombre y apellido. Cada uno debe comenzar con mayúscula y solo contener letras.',
            'email.unique'  => 'Este correo ya está registrado.',
            'password.min'  => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('inscripciones')->with('status', '¡Registro exitoso! Ahora puedes completar tu inscripción.');
    }

    public function login(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Ingrese un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $credentials = [
            "email"    => $request->email,
            "password" => $request->password,
        ];

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Verificar si es admin
            if ($this->isAdmin($request->email)) {
                return redirect()->route('admin.inscripciones')->with('status', '¡Bienvenido Administrador!');
            }
            
            // Usuario regular va a inscripciones
            return redirect()->route('inscripciones')->with('status', '¡Bienvenido! Puedes completar tu inscripción.');
        } else {
            return redirect()->back()
                           ->withErrors(['email' => 'Credenciales incorrectas.'])
                           ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('status', 'Sesión cerrada correctamente.');
    }

    private function isAdmin($email)
    {
        return $email === env('ADMIN_EMAIL', 'admin@artesmar.com');
    }
}
