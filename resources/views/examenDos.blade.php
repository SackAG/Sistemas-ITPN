@extends('examen')

@section('contenido')
    <h3>Detalle del examen con ID: {{ $id }}</h3>

    <h4>Lista de Carreras:</h4>
    
    <form action="{{ route('examen.show', $id) }}" method="GET">
        <input type="hidden" name="mostrar" value="1">
        <button type="submit">Mostrar Carreras</button>
    </form>
    @isset($carreras)
        <ul>
            @foreach ($carreras as $carrera)
                <li>
                    <a href="{{ route('examen.materias', ['id' => $id, 'carrera_id' => $carrera->id]) }}">
                        ID: {{ $carrera->id }} - Nombre: {{ $carrera->nombre }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endisset
    @isset($materias)
        <h4>Materias de {{ $carrera_seleccionada->nombre }}:</h4>
        <ul>
            @foreach ($materias as $materia)
                <li>ID: {{ $materia->id }} - {{ $materia->nombre }} </li>
            @endforeach
        </ul>
    @endisset
@endsection