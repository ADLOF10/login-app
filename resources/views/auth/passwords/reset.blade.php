<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #004d40, #ffffff);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 400px;
            padding: 2rem;
        }
        .btn-primary {
            background-color: #004d40;
            border: none;
        }
        .btn-primary:hover {
            background-color: #00332e;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="mb-4 text-center" style="color: #004d40;">Restablecer Contraseña</h2>
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <!-- Correo -->
            <div class="mb-3">
                <label for="email" class="form-label" style="color: #004d40;">Correo. Sin caracteres especiales, solo -,_ y .   </label>
                <input type="email" name="email" id="email" class="form-control" 
                       value="{{ old('email') }}" 
                       pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" 
                       title="Debe ser un formato válido (@profesor.uaemex.wip o @alumno.uaemex.wip) y estar registrado en el sistema." 
                       required maxlength="50" placeholder="@profesor.uaemex.wip o @alumno.uaemex.wip">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Nueva Contraseña -->
            <div class="mb-3">
                <label for="password" class="form-label" style="color: #004d40;">Nueva Contraseña</label>
                <input type="password" name="password" id="password" class="form-control"
                       pattern="(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}"
                       title="Debe tener al menos 8 caracteres, una mayúscula y un carácter especial @$!%*?&."
                       required placeholder="Ejemplo: Juan1234*">
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Confirmar Contraseña -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label" style="color: #004d40;">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
                       required placeholder="Confirma tu contraseña">
                @error('password_confirmation')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Restablecer Contraseña</button>
        </form>
    </div>
</body>
</html>

<script>
    document.querySelector('form').addEventListener('submit', function (event) {
        const email = document.querySelector('input[name="email"]').value;
        const password = document.querySelector('input[name="password"]').value;

        if (/\s/.test(email)) {
            alert('El correo no debe contener espacios.');
            event.preventDefault();
        }

        if (!/^(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(password)) {
            alert('La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula y un carácter especial.');
            event.preventDefault();
        }
    });
</script>

