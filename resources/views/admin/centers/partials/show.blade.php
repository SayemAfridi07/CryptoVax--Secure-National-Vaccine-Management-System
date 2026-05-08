<div class="container-fluid">
	<div class="card card-widget widget-user shadow">

		<div class="p-3 bg-dark rounded">
			<h3 class="widget-user-username"> Center name: {{ ucwords($center->name)}}</h3>
			
			<h5 class="widget-user-desc"> Address: {{ $center->address }}</h5>
			<br>
			
			<h6>Operators:</h6>
			@forelse ($center->operators as $operator)
				<p> Name: {{ $operator->name }} </p>
				<p> Email: {{ $operator->email }} </p>
			@empty
				<p>	No operators assigned yet. </p>
			@endforelse
		</div>
	</div>
</div>

@push('styles')
<style>
	#uni_modal .modal-footer{
		display: none
	}
	#uni_modal .modal-footer.display{
		display: flex
	}
</style>
@endpush