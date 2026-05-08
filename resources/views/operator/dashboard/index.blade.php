@extends('operator.layouts.body', ['title' => 'Vaccine Center Dashboard', 'page'=> 'operator_dashboard'])

@section('content')

    <h3 style="text-align:center">Vaxx Operator Dashboard</h3>
    
    <hr>

    <div class="card mx-auto mt-5" style="width: 24rem;">
        <div class="card-header text-white bg-primary">
            Center Details
        </div>
        <div class="card-body">
          <h5 class="card-title">Name: {{ $center->name }}</h5>
          <p class="card-text">
            <strong>Center ID:</strong>  {{ $center->id }}<br>
            <strong>Address:</strong>  {{ $center->address }}<br>
          </p>
          <hr>
          <h6 class="card-subtitle mb-2 text-muted">Operator Information</h6>
            @forelse ($center->operators ?? [] as $operator)
                <p class="card-text">
                    <strong>Name:</strong>  {{ $operator->name }}<br>
                    <strong>Email:</strong>  {{ $operator->email }}<br>
                    <strong>Phone:</strong> {{ $operator->phone }}<br>
                </p>
            @empty
                <p class="card-text">Operator not added yet.</p>
            @endforelse
        </div>
        <div class="card-footer text-muted">
          Founded:  {{ $center->created_at->toDateString() }}<br>
        </div>
    </div>
      
      
    
    <div style="padding:6%;font-size:1.5rem;">
        <a href="{{ route('operator.registrations.index') }}">Vaccine Registrations</a> <br>
    </div>

@endsection