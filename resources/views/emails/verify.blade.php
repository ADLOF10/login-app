<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Cuenta</title>
</head>
<body>
    <h1>Verificación de Cuenta</h1>
    <p>Hola, {{ $name }}</p> 
    <p>Por favor, haz clic en el siguiente enlace para verificar tu cuenta:</p>
    <a href="{{ route('verify', ['token' => $token]) }}">Verificar Cuenta</a>
    <p>Si no solicitaste esto, puedes ignorar este correo.</p>
</body>
</html>
