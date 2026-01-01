@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Lista de Pacientes</h1>

    <a href="{{ route('patients.create') }}" class="btn btn-primary mb-3">Agregar Paciente</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Edad</th>
                <th>Sexo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $patient)
            <tr>
                <td>{{ $patient->id }}</td>
                <td>{{ $patient->user->name }}</td>
                <td>{{ $patient->user->email }}</td>
                <td>{{ $patient->edad }}</td>
                <td>{{ $patient->sexo }}</td>
                <td>
                    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este paciente?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
