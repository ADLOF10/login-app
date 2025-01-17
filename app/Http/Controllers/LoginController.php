<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Verifica si la contraseña es la predeterminada
            if (Hash::check('Wip1234$', $user->password)) {
                return response()->json([
                    'role' => $user->role,
                    'message' => 'Cambio de contraseña requerido',
                    'change_password' => true
                ], 200);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => $user->role,
                'change_password' => false
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user(); // Obtiene al usuario autenticado

        // Verifica que el email proporcionado coincida con el usuario autenticado
        if ($user->email !== $request->email) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Actualiza la contraseña del usuario
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'Contraseña actualizada exitosamente'], 200);
    }
}


