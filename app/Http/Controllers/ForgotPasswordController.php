<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */


     
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }
     public function sendResetLinkFromFlutter(Request $request)
    {
        // Validar que el correo esté presente y tenga un formato válido
        $request->validate([
            'email' => 'required|email|exists:users,email', // Asegúrate de tener el campo 'email' en la tabla 'users'
        ]);

        // Intentar enviar el enlace de restablecimiento de contraseña
        $status = Password::sendResetLink($request->only('email'));

        // Retornar una respuesta según el estado del envío
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Correo enviado con éxito.'], 200)
            : response()->json(['error' => 'No se pudo enviar el correo. Intenta nuevamente.'], 400);
    }


    /**
     * Handle sending the password reset email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Display the password reset form.
     *
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    /**
     * Handle resetting the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',
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
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
