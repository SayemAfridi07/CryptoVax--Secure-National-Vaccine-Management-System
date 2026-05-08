@extends('admin.layouts.body', ['title' => 'Admin Dashboard', 'page' => 'admin_dashboard'])
@section('content')

    <h3 style="text-align:center">Surokkha Admin Dashboard</h3>
    <hr>

    <div class="card mx-auto mt-5" style="width: 24rem;">
        <div class="card-header text-white bg-primary">
            Admin Details
        </div>
        <div class="card-body">
            <h5 class="card-title"><strong>Name:</strong> {{ $user->name }}</h5>
            <p class="card-text">
                <strong>Email:</strong>  {{ $user->email }}<br>
                <strong>Phone:</strong> {{ $user->phone }}<br>
            </p>
        </div>
    </div>

    <div style="padding:6%;font-size:1.5rem;">
        <a href="{{ route('admin.centers.index') }}">Centers</a> <br>
    </div>

@endsection