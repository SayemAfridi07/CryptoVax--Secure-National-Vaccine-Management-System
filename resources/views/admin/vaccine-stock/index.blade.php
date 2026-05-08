@extends('admin.layouts.body', ['title' => 'Central Vaccine Stock', 'page'=> 'central_vaccine_stock_list'])
@section('content')
    <div class="col-lg-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="{{route('admin.vaccine-stock.create')}}"><i class="fa fa-plus"></i> Enlist new Import</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table tabe-hover table-bordered" id="list">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Manufacturer</th>
                            <th>Vaccine Name</th>
                            <th>Total Vials in Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vaccineStocks ?? [] as $vaccineStock)
                        <tr>
                            <th class="text-center">{{$loop->iteration}}</th>
                            <td><b>{{ ucwords($vaccineStock->vaccine->vendor) }}</b></td>
                            <td><b>{{ ucwords($vaccineStock->vaccine->name) }}</b></td>
                            <td><b>{{ ucwords($vaccineStock->quantity) }}</b></td>
                        </tr>	
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection