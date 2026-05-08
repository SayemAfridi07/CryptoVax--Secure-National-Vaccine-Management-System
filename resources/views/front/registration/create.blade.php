@extends('layouts.frontend_app')

@section('title', 'Vaccine Registration')
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
                        <h2>Registraion For Vaccine</h2>
                        <form action="{{ route('front.registration.store') }}" class="mt-4" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="my-2">
                                        <label for="">Name</label>
                                        <input type="text" class="form-control" name="name" placeholder="Name"
                                            value="{{ auth()->user()->name }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="my-2">
                                        <label for="">Email</label>
                                        <input type="text" class="form-control" name="email" placeholder="Email"
                                            value="{{ auth()->user()->email }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="my-2">
                                        <label for="">NID</label>
                                        <input type="text" class="form-control" name="nid" placeholder="NID"
                                            value="{{ auth()->user()->nid }}">
                                        @error('nid')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="my-2">
                                        <label for="">Phone</label>
                                        <input type="text" class="form-control" name="phone" placeholder="Phone"
                                            value="{{ auth()->user()->phone }}">
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="my-2">
                                        <label for="">Date Of Birth</label>
                                        <input type="date" class="form-control" name="dob"
                                            value="{{ auth()->user()->dob }}">
                                        @error('dob')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="my-2">
                                        <label for="">Vaccine</label>
                                        <select name="vaccine_id" id="" class="form-control">
                                            <option value="">Select a Vaccine</option>
                                            @foreach ($vaccines as $vaccine)
                                                <option value="{{ $vaccine->id }}">{{ $vaccine->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('vaccine_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> --}}
                            </div>

                            {{-- <div class="row">
                                <div class="col-md-6">
                                    <div class="my-2">
                                        <label for="">Division</label>
                                        <select name="division" id="" class="form-control division">
                                            <option value="">Select Your Divison</option>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('division')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="my-2">
                                        <label for="">District</label>
                                        <select name="district" id="" class="form-control district">
                                            <option value="">Select Your District</option>

                                        </select>
                                        @error('district')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="my-2">
                                        <label for="">Hospital</label>
                                        <select name="hospital_id" id="" class="form-control hospital">
                                            <option value="">Select a Hospital</option>
                                        </select>
                                        @error('hospital_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="my-2">
                                        <label for="">Preferred Center</label>
                                        <select name="center_id" id="" class="form-control center">
                                            <option value="">Select a Center</option>
                                            @foreach ($centers as $center)
                                                <option value="{{ $center->id }}" @selected(old('center_id') == $center->id)>
                                                    {{ $center->name }}, address: {{ $center->address }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('center_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="my-2">
                                <button class="btn btn-sm btn-success w-100">Apply for Vaccine</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@push('script')
    <script>
    </script>
@endpush
