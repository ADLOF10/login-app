<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@profesor\.uaemex\.wip$/|unique:users,email',
            'real_email' => 'required|string|email|max:255|unique:users,real_email',
            'password' => [
                'required',
                'string',
                'min:8', 
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', 
                'confirmed', 
            ],
        ], [
            'email.regex' => 'El correo debe tener el formato @profesor.uaemex.wip.',
            'password.regex' => 'La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);
        
       
        $verificationToken = sha1($request->real_email . now());

        try {
           
            Mail::send('emails.verify', [
                'token' => $verificationToken,
                'name' => $request->name, 
            ], function ($message) use ($request) {
                $message->to($request->real_email);
                $message->subject('Verificación de cuenta de profesor');
            });

            \Log::info('Correo enviado correctamente a: ' . $request->real_email);
        } catch (\Exception $e) {
            
            \Log::error('Error al enviar el correo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al enviar el correo de verificación. Inténtalo más tarde.');
        }

        
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email, 
                'real_email' => $request->real_email,
                'password' => Hash::make($request->password),
                'role' => 'profesor',
                'remember_token' => $verificationToken,
            ]);

            \Log::info('Usuario creado correctamente: ' . $request->email);
        } catch (\Exception $e) {
            \Log::error('Error al crear el usuario: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al crear tu cuenta. Inténtalo más tarde.');
        }

        return redirect()->route('login')->with('success', 'Revisa tu correo real para verificar tu cuenta.');
    }


    public function verify($token)
    {
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Token de verificación inválido.');
        }

        $user->update(['remember_token' => null]);

        return redirect()->route('login')->with('success', 'Cuenta verificada exitosamente. Ahora puedes iniciar sesión.');
    }
}
