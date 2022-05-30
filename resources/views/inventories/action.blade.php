{{-- <a title="Detail" class="btn btn-icon btn-success" href="{{ url('inventories/' . $model->id) }}"><i
		class="fas fa-info-circle"></i></a> --}}
{{-- <a class="btn btn-icon btn-primary" href="{{ url('inventories/' . $model->id . '/edit') }}"><i
		class="fas fa-pen-square"></i></a> --}}
{{-- <form action="{{ url('inventories/' . $model->id) }}" method="post"
	onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
	@method('delete')
	@csrf
	<button class="btn btn-icon btn-danger" title="Delete"><i class="fas fa-times"></i></button>
</form> --}}
<div class="btn-group">
	<a title="Detail" class="btn btn-icon btn-success" href="{{ url('inventories/' . $model->id) }}"><i
			class="fas fa-info-circle"></i></a>
	<button type="button" class="btn btn-success dropdown-toggle dropdown-icon" data-toggle="dropdown">
		<span class="sr-only">Toggle Dropdown</span>
	</button>
	<div class="dropdown-menu" role="menu">
		<a class="dropdown-item" href="{{ url('inventories/' . $model->id . '/edit') }}"><i class="fas fa-pen-square"></i>
			Edit</a>
		<a class="dropdown-item" href="{{ url('trackings?search=' . $model->inventory_no) }}"><i class="fas fa-search"></i>
			Track</a>
		@if ($model->transfer_status != 'Mutated')
			<a class="dropdown-item" href="{{ url('inventories/transfer/' . $model->id) }}"><i
					class="fas fa-exchange-alt"></i>
				Transfer</a>
		@endif

		<div class="dropdown-divider"></div>
		<form action="{{ url('inventories/' . $model->id) }}" method="post"
			onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
			@method('delete')
			@csrf
			<button class="dropdown-item bg-danger" title="Delete"><i class="fas fa-times"></i> Delete</button>
		</form>
	</div>
</div>
