<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación a un nuevo grupo</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <header style="background: #004d40; padding: 20px; color: white; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">¡Te han asignado a un nuevo grupo!</h1>
        </header>
        <main style="padding: 20px;">
            <p>Hola,</p>
            <p>Has sido asignado al grupo <strong>{{ $grupo->nombre_grupo }}</strong>.</p>
            <p><strong>Detalles del Grupo:</strong></p>
            <ul>
                <li><strong>Materia:</strong> {{ $grupo->materia->nombre }}</li>
                <li><strong>Profesor:</strong> {{ $profesor->name }}</li>
            </ul>
            <p>Si tienes alguna duda, no dudes en comunicarte con tu profesor.</p>
            <p>¡Te deseamos éxito en este nuevo grupo!</p>
        </main>
        <footer style="background: #004d40; color: white; text-align: center; padding: 10px;">
            <p style="margin: 0; font-size: 12px;">Sistema de Gestión de Grupos</p>
        </footer>
    </div>
</body>
</html>
