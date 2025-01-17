<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetController extends Controller
{
    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function reset(Request $request)
{
    $request->validate([
        'email' => [
            'required',
            'email',
            'exists:users,email', // Debe existir en la tabla 'users'
            'max:50',
            'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', // Sin caracteres especiales y formato válido
            function ($attribute, $value, $fail) {
                if (preg_match('/\s/', $value)) {
                    $fail('El correo no debe contener espacios.');
                }
            },
        ],
        'password' => [
            'required',
            'confirmed', // Contraseñas idénticas
            'min:8',
            'regex:/^(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', // Al menos una mayúscula y un carácter especial
        ],
    ], [
        'email.exists' => 'El correo electrónico no está registrado.',
        'email.regex' => 'El correo debe ser un formato válido sin caracteres especiales antes del @.',
        'password.regex' => 'La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula y un carácter especial.',
        'password.confirmed' => 'La confirmación de la contraseña no coincide.',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __('Contraseña restablecida correctamente.'))
        : back()->withErrors(['email' => [__($status)]]);
}



        public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }


}
