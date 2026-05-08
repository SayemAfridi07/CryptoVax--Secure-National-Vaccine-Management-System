@extends('admin.layouts.body', ['title' => 'New Center', 'page'=> 'new_center'])
@section('content')
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <form action="" id="manage_center">
                @csrf
                <div class="row">
                    <div class="col-md-6 border-right">
                        <div class="form-group">
                            <label for="" class="control-label">Center Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" required value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address" class="control-label">Address</label>
                            <textarea name="address" id="address" cols="30" rows="6" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-lg-12 text-right justify-content-center d-flex">
                    <button class="btn btn-primary mr-2">Save</button>
                    <button class="btn btn-secondary" type="button" onclick="location.href = '{{ route('admin.centers.index' )}}'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    
</style>
@endpush


@push('scripts')
<script>
    $('#manage_center').submit(function(e) {
        e.preventDefault();

        $('input').removeClass("border-danger");

        start_load()

        $('#msg').html('');

        $.ajax({
            url:'{{ route("admin.centers.store") }}',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp == 1){
                    alert_toast('Data successfully saved.',"success");

                    setTimeout(function(){
                        location.replace('{{ route("admin.centers.index") }}')
                    },750);
                }
                else {
                    alert_toast("something went wrong", "danger");
                    end_load();
                }
            },
            error:function(err) {
                console.log(err.responseJSON.message);
                alert_toast(err.responseJSON.message, "danger");
                end_load()
            }
        })
    })
</script>
@endpush
