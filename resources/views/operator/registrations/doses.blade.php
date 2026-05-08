@extends('operator.layouts.body', ['title' => 'Vaccine Dose List - Vaccine Center', 'page'=> 'registrations'])
@section('content')
    <div class="col-lg-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="{{ route('operator.registrations.doses.create', $registration->id) }}"><i class="fa fa-plus"></i> Add Another Dose</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table tabe-hover table-bordered" id="list">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Dose Type</th>
                            <th>Dose Name</th>
                            <th>Given By</th>
                            <th>Appointment Date</th>
                            <th>Vaccination Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registration->doses as $dose)
                        <tr>
                            <th class="text-center">{{$loop->iteration}}</th>
                            <td><b>{{ ucwords($dose->dose_type) }}</b></td>
                            <td><b>{{ ucwords($dose->vaccine->name) }}</b></td>
                            <td><b>{{ ucwords($dose->givenBy?->name) }}</b></td>
                            <td><b>{{ ucwords($dose->scheduled_date) }}</b></td>
                            <td><b>{{ ucwords($dose->taken_date) }}</b></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                Action
                                </button>
                                <div class="dropdown-menu" style="">
                                    <a href="{{ route('operator.registrations.doses.mark-as-taken', [$registration->id, $dose->id]) }}" class="dropdown-item"><i class="fa fa-check mr-1 text-success"></i>Mark as taken</a>
                                </div>
                            </td>
                        </tr>	
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection