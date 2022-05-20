<a title="Detail" class="btn btn-icon btn-success" href="{{ url('inventories/' . $model->id) }}"><i
		class="fas fa-info-circle"></i></a>
{{-- <a class="btn btn-icon btn-primary" href="{{ url('inventories/' . $model->id . '/edit') }}"><i
		class="fas fa-pen-square"></i></a> --}}
<form action="{{ url('inventories/' . $model->id) }}" method="post"
	onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
	@method('delete')
	@csrf
	<button class="btn btn-icon btn-danger" title="Delete"><i class="fas fa-times"></i></button>
</form>
