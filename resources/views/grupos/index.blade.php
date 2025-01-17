@extends('layouts.app')

@section('title', 'Grupos')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Lista de Grupos</h1>
        <a href="{{ route('grupos.create') }}" class="btn btn-success-custom">Crear Nuevo Grupo</a>
    </div>
    
    <!-- Input para buscar -->
    <div class="mb-4">
        <input 
            type="text" 
            id="searchGrupo" 
            class="form-control" 
            placeholder="Buscar por nombre del grupo o materia..." 
            oninput="filterGrupos()"
        >
    </div>
    
    <table class="table table-striped table-bordered" id="gruposTable">
        <thead class="text-white" style="background-color: #004d40;">
            <tr>
                <th>ID</th>
                <th>Nombre del Grupo</th>
                <th>Materia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($grupos as $grupo)
            <tr>
                <td>{{ $grupo->id }}</td>
                <td>{{ $grupo->nombre_grupo }}</td>
                <td>{{ $grupo->materia->nombre }}</td>
                <td>
                    <a href="{{ route('grupos.show', $grupo->id) }}" class="btn btn-info btn-sm">Detalles</a>
                    <a href="{{ route('grupos.edit', $grupo->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('grupos.destroy', $grupo->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                            onclick="return confirm('¿Estás seguro de que deseas eliminar este grupo? Esta acción no se puede deshacer.')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No se encontraron grupos.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function filterGrupos() {
        const searchInput = document.getElementById('searchGrupo').value.toLowerCase();
        const table = document.getElementById('gruposTable');
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
