@extends('admin.layouts.body', ['title' => 'Enlist new Import', 'page'=> 'create_vaccine'])
@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="" id="create_vaccine">
                    @csrf
                    @method('POST')
            
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="vaccine_id">Select Vaccine</label>
                            <select class="form-control form-control-sm select2" name="vaccine_id" id="vaccine_id" required>

                                <option value="">Select Vaccine</option>
                                @foreach($vaccines as $vaccine)
                                    <option value="{{ $vaccine->id }}">{{ $vaccine->vendor . ' - ' . $vaccine->name}}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="quantity">Quantity</label>
                            <input class="form-control form-control-sm" type="number" name="quantity" id="quantity" required>
                        </div>
                    </div>

                    <hr>
                    <div class="col-lg-12 text-right justify-content-center d-flex">
                        <button class="btn btn-primary mr-2">Enlist</button>
                        <button class="btn btn-secondary" type="button" onclick="location.href = '{{route('admin.centers.index')}}'">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
	<style>
		img#cimg{
			height: 15vh;
			width: 15vh;
			object-fit: cover;
			border-radius: 100% 100%;
		}
	</style>
	<script>
        
		$('#create_vaccine').submit(function(e) {
			e.preventDefault()

			$('input').removeClass("border-danger")

			start_load()

			$('#msg').html('')

            var url = '{{ route("admin.vaccine-stock.store") }}';

			$.ajax({
				url:url,
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
				success:function(resp) {
					if(resp == 1)
                    {
						alert_toast('Vaccine import enlisted successfully!',"success");
						setTimeout(function() {
							location.replace('{{ route("admin.vaccine-stock.index") }}')
						},750)
					}
                    else
                    {
					    alert_toast("something went wrong", "danger");
					    end_load()
                    }
				},
                error:function(err) {
					alert_toast('something went wrong', "danger");
					end_load()
                }
			})
		})
	</script>
@endsection