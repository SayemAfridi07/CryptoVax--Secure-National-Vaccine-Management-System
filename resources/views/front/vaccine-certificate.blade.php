@extends('layouts.frontend_app')

@section('title', 'Vaccine Certificate')

@section('app_content')
    <section id="vaccine-card">
        <div class="row container" style="height: 60vh;">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-info">Important Links</h4>
                        <ul class="mt-4">
                            <li><a href="{{ route('front.registration.status') }}" class="btn btn-link">Vaccine Status</a></li>
                            <li><a href="" class="btn btn-link">Vaccine Card</a></li>
                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">

                <div class="card">
                    
                    <div class="card-body">

                                            
                        <h2 class="card-title"> Vaccine Certificate </h2>
                    
                        <div class="card">
                            <div class="card-body">

                                <h5 class="card-title">Candidate: {{ $registration->user->name }}</h5>

                                <div class="mx-3">
                                    <p class="mb-0">Date of Birth: {{ $registration->user->dob }}</p>
                                    <p class="mb-0">Vaccine Center: {{ $registration->center->name }}</p>
                                    @if ($registration->center->address)
                                        <p class="mb-0">Vaccine Center Address: {{ $registration->center->address }}</p>
                                    @endif
                                </div>

                                <table class="table table-hover">
                            
                                    <tr>
                                        <th>Dose Type</th> <th>Date Scheduled</th> <th>Date Taken</th> <th>Given By</th> <th>Vaccine Name</th>
                                    </tr>

                                    @foreach ($registration->doses as $dose)
                                        <tr>
                                            <td> {{$dose->dose_type}} </td>
                                            <td> {{ $dose->scheduled_date }} </td>
                                            <td> {{$dose->taken_date}} </td>
                                            <td> {{ $dose->givenBy->name ?? "" }} </td>
                                            <td> {{$dose->vaccine->name}} </td>
                                        </tr>
                                    @endforeach
                            
                                </table>

                                <form action="{{ route('front.vaccine.certificate.download') }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                        class="btn btn-sm btn-success w-100"
                                    >
                                        Download Vaccine Certificate
                                    </button>
                                </form>

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection