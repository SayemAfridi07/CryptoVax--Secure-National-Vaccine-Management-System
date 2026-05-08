@extends('operator.layouts.body', ['title' => 'Show registration', 'page'=> 'registrations'])
@section('content')
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<form action="{{ route('operator.registrations.update', $registration->id) }}" id="registration_form_in_center">
                    @csrf
                    @method('PUT')
					<input type="hidden" name="id" value="{{$registration->id}}">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="" class="control-label">Name</label>
								<input type="text" name="name" class="form-control form-control-sm" value="{{$registration->user->name}}" readonly>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">Phone</label>
								<input type="text" class="form-control form-control-sm" name="phone" value="{{$registration->user->phone}}">
								<small id="#msg"></small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">Email</label>
								<input type="text" class="form-control form-control-sm" name="email" value="{{$registration->user->email}}" readonly>
								<small id="#msg"></small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">NID</label>
								<input type="text" class="form-control form-control-sm" name="nid" value="{{$registration->user->nid}}" readonly>
								<small id="#msg"></small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">Date Of Birth</label>
								<input type="date" class="form-control form-control-sm" name="dob" value="{{$registration->user->dob}}">
								<small id="#msg"></small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">Registration Date</label>
								<input type="text" class="form-control form-control-sm" name="" value="{{$registration->created_at->toDateString()}}" readonly>
								<small id="#msg"></small>
							</div>
						</div>
					</div>

                    <div class="card p-3">
                        <h4 class="card-title">Vaccine Center</h4>
                        <div class="card-body">
                            <h5 class="p-2">{{ $registration->center->name }}</h5>
                            <p class="p-2">{{ $registration->center->address }}</p>
                        </div>
                    </div>
					<hr>
					<div class="col-lg-12 text-right justify-content-center d-flex">
						<button type="submit" class="btn btn-primary mr-2" id="center_registration_update_btn">Save</button>
						<button class="btn btn-secondary" type="button" onclick="location.href = '{{route('operator.registrations.index')}}'">Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#registration_form_in_center').on('submit', function(e){
                e.preventDefault();

                $('input').removeClass("border-danger")
                start_load()
                $('#msg').html('')
                
                var url = '{{route("operator.registrations.update", ":id")}}';
                url = url.replace(':id', '{{$registration->id}}');
                $.ajax({
                    url:url,
                    data: new FormData($(this)[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    success:function(resp){
                        if(resp == 1){
                            alert_toast('Data successfully saved.',"success");
                            setTimeout(function(){
                                location.replace('{{route("operator.registrations.show", $registration->id)}}')
                            },750)
                        }else if(resp == 2){
                            alert_toast('Something went wrong.',"error");
                            end_load()
                        }
                        else {
                            alert_toast("something went wrong", "danger");
                            end_load()
                        }
                    },
                    error:function(err) {
                        console.log(err.responseJSON.message);
                        alert_toast(err.responseJSON.message, "danger");
                        end_load()
                    }
                })
            })
        });
    </script>    
@endpush