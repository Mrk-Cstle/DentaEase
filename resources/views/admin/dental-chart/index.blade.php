

@extends('layout.navigation')

@section('title', 'Dental Chart')

@section('main-content')
<h1>Dental Chart for {{ $patient->name }}</h1>

<livewire:dental-chart-board :patient="$patient" />
@endsection