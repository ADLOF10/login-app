<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'accepted' => 'El :attribute debe ser aceptado.',
    'active_url' => 'El :attribute no es una URL válida.',
    // ... otros mensajes predeterminados traducidos al español ...

    'unique' => 'El :attribute ya está registrado.',
    'exists' => 'El :attribute es inválido o no está registrado.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'correo_institucional' => [
            'unique' => 'El correo institucional ya está registrado.',
            'exists' => 'El correo institucional es inválido o no está registrado.',
        ],
        'real_email' => [
            'unique' => 'El correo personal ya está registrado.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'correo_institucional' => 'correo institucional',
        'real_email' => 'correo personal',
        'numero_cuenta' => 'número de cuenta',
        'nombre' => 'nombre',
        'apellidos' => 'apellidos',
        'semestre' => 'semestre',
    ],

    'real_email' => [
    'required',
    'email',
    'max:50',
    'unique:users,real_email',
    'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
],


'custom' => [
    'real_email' => [
        'unique' => 'El correo personal ya está registrado.',
        'regex' => 'El formato del correo personal es inválido. Asegúrate de no usar caracteres especiales no permitidos.',
    ],
],

'regex' => 'El formato del :attribute no es válido.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'max' => 'El :attribute no debe ser mayor a :max caracteres.',
    'required' => 'El campo :attribute es obligatorio.',


    'password' => [
        'required',
        'string',
        'min:8',
        'regex:/^(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', // Al menos una mayúscula y un carácter especial
        'confirmed', // Validación para confirmar que los campos coincidan
    ],

    'password.confirmed' => 'La confirmación de la contraseña no coincide.', // Mensaje personalizado
    'password.regex' => 'La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula y un carácter especial.',
    'confirmed' => 'La confirmación de :attribute no coincide.',

];
