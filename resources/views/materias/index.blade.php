@extends('layouts.app')

@section('title', 'Lista de Materias')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Materias</h1>
        <a href="{{ route('materias.create') }}" class="btn btn-success-custom">Registrar Nueva Materia</a>
    </div>
    
    <!-- Input para buscar -->
    <div class="mb-4">
        <input 
            type="text" 
            id="searchMateria" 
            class="form-control" 
            placeholder="Buscar por ID, nombre, clave o docente..." 
            oninput="filterMaterias()"
        >
    </div>
    
    <table class="table table-striped table-bordered" id="materiasTable">
        <thead class="text-white" style="background-color: #004d40;">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Clave</th>
                <th>Docente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materias as $materia)
            <tr>
                <td>{{ $materia->id }}</td>
                <td>{{ $materia->nombre }}</td>
                <td>{{ $materia->clave }}</td>
                <td>{{ Auth::user()->name }}</td>
                <td>
                    <a href="{{ route('materias.show', $materia->id) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('materias.edit', $materia->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('materias.destroy', $materia->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta materia?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function filterMaterias() {
        const searchInput = document.getElementById('searchMateria').value.toLowerCase();
        const table = document.getElementById('materiasTable');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
            if (rowText.includes(searchInput)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
