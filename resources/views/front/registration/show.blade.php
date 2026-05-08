@extends('layouts.frontend_app')

@section('title', 'Vaccine Status')
@section('app_content')
    <section id="registraion_section">
        <div class="row container">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-info">Important Links</h4>
                        <ul class="mt-4">
                            <li><a href="{{ route('front.registration.status') }}" class="btn btn-link">Vaccine Status</a>
                            </li>
                            <li><a href="{{ route('front.vaccine.card') }}" class="btn btn-link">Vaccine Card</a></li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    
                    <div class="card-body">

                                            
                        <h2 class="card-title"> Vaccine Status </h2>
                    
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

                                    @forelse ($registration->doses as $dose)
                                        <tr>
                                            <td>{{$dose->dose_type}}</td>
                                            <td>
                                                
                                                @if(!$dose->taken_date)
                                                    <input type="date" 
                                                        name="scheduled_date" 
                                                        value="{{ $dose->scheduled_date }}" 
                                                        min="{{ today()->toDateString() }}" 
                                                        class="scheduled_date" 
                                                        data-dose-id="{{ $dose->id }}"
                                                        style="border: 1px solid #ffa687; border-radius: 3px;"
                                                    >

                                                    <a href="#" class="cancel_appointment" data-dose-id="{{ $dose->id }}">
                                                        Cancel
                                                    </a>
                                                @else
                                                    {{ $dose->scheduled_date }}
                                                @endif

                                            </td>
                                            <td>{{$dose->taken_date}}</td>
                                            <td>{{ $dose->givenBy->name ?? "" }}</td>
                                            <td>{{$dose->vaccine->name}}</td>
                                        </tr>
                                    @empty
                                        <td colspan="5" class="text-center"><span>First dose is not scheduled yet</span></td>
                                    @endforelse
                            
                                </table>

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
@stop

@push('script')
<script>
    let elems = document.getElementsByClassName('scheduled_date');

    Array.from(elems).forEach(function(elem) {

        elem.addEventListener('change', function (event) {
            updated_date = event.target.value;
            const doseId = event.target.dataset.doseId

            let xhr = new XMLHttpRequest();
            let url = '{{ route('front.registration.update_date') }}';

            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200)
                    {
                        data = JSON.parse(xhr.responseText);
                        toastr.success(data.message, 'Success!')
                    }
                    else
                    {
                        toastr.success(xhr.status + ' ' + xhr.responseText, 'Error!')
                    }
                }
            }
            xhr.send(JSON.stringify({ date: updated_date, dose_id: doseId, _token: '{{csrf_token()}}' }));
        });
    });


    elems = document.getElementsByClassName('cancel_appointment');

    Array.from(elems).forEach(function(elem) {

        elem.addEventListener('click', function (event) {

            event.preventDefault();

            const doseId = event.target.dataset.doseId

            let xhr = new XMLHttpRequest();
            let url = '{{ route('front.registration.cancel_appointment') }}';

            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200)
                    {
                        inputElem = elem.previousElementSibling
                        inputElem.value = '';
                        data = JSON.parse(xhr.responseText);
                        console.log(data.message);
                        toastr.success(data.message, 'Success!')
                    }
                    else
                    {
                        toastr.success(xhr.status + ' ' + xhr.responseText, 'Error!')
                    }
                }
            }
            xhr.send(JSON.stringify({ dose_id: doseId, _token: '{{csrf_token()}}' }));
        });
    });

</script>
@endpush
