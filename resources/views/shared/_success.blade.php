@if (session('success'))
	<div class="alert alert-success" role="alert">
		<button type="button" class="close" data-dismiss="alert">×</button>
		{{ session('success') }}
	</div>
@elseif (session('warning'))
	<div class="alert alert-warning" role="alert">
		<button type="button" class="close" data-dismiss="alert">×</button>
		{{ session('warning') }}
	</div>
@elseif(session('status'))
	<div class="alert alert-success" role="alert">
		{{ session('status') }}
	</div>
@endif