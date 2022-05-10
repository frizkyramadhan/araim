@extends('layouts.main')

@section('content')
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">{{ $title }}</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
						<li class="breadcrumb-item active">{{ $title }}</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<!-- Main row -->
			<div class="row">
				<!-- Left col -->
				<section class="col-lg-12">
					<!-- Custom tabs (Charts with tabs)-->
					<div id="accordion">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">
									<strong>{{ $subtitle }}</strong>
								</h3>
								<div class="card-tools">
									<ul class="nav nav-pills ml-auto">
										<li class="nav-item mr-2">
											<a class="btn btn-warning" href="{{ url('departments/create') }}"><i class="fas fa-plus"></i>
												Add</a>
										</li>
									</ul>
								</div>
							</div><!-- /.card-header -->

							<div class="modal fade" id="modal-default">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">Default Modal</h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<form class="form-horizontal" action="{{ url('departments') }}" method="POST">
											@csrf
											<div class="modal-body">
												<div class="form-group row">
													<label class="col-sm-2 col-form-label">Department Name</label>
													<div class="col-sm-10">
														<input type="text" class="form-control @error('dept_name') is-invalid @enderror" name="dept_name"
															value="{{ old('dept_name') }}" placeholder="Department Name">
														@error('dept_name')
															<div class="error invalid-feedback">
																{{ $message }}
															</div>
														@enderror
													</div>
												</div>
											</div>
											<div class="modal-footer justify-content-between">
												<button type="submit" class="btn btn-info">Submit</button>
												<button type="reset" class="btn btn-default float-right">Cancel</button>
											</div>
										</form>
									</div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>
							<!-- /.modal -->

							{{-- accordion add --}}
							{{-- <div class="card-body collapse @error('dept_name') show @enderror" id="add" data-parent="#accordion">
								<form class="form-horizontal" action="{{ url('departments') }}" method="POST">
									@csrf
									<div class="card-body">
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Department Name</label>
											<div class="col-sm-10">
												<input type="text" class="form-control @error('dept_name') is-invalid @enderror" name="dept_name"
													value="{{ old('dept_name') }}" placeholder="Department Name">
												@error('dept_name')
													<div class="error invalid-feedback">
														{{ $message }}
													</div>
												@enderror
											</div>
										</div>
									</div>
									<!-- /.card-body -->
									<div class="card-footer">
										<button type="submit" class="btn btn-info">Submit</button>
										<button type="reset" class="btn btn-default float-right">Cancel</button>
									</div>
									<!-- /.card-footer -->
								</form>
							</div> --}}

							{{-- accordion edit --}}
							{{-- @foreach ($departments as $d)
								<div class="card-body collapse @error('dept_name') show @enderror" id="edit-{{ $d->id }}"
									data-parent="#accordion">
									<form class="form-horizontal" action="{{ url('departments/' . $d->id) }}" method="POST">
										@method('PATCH')
										@csrf
										<div class="card-body">
											<div class="form-group row">
												<label class="col-sm-2 col-form-label">Department Name</label>
												<div class="col-sm-10">
													<input type="text" class="form-control @error('dept_name') is-invalid @enderror" name="dept_name"
														value="{{ old('dept_name', $d->dept_name) }}" placeholder="Department Name">
													@error('dept_name')
														<div class="error invalid-feedback">
															{{ $message }}
														</div>
													@enderror
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-2 col-form-label">Status</label>
												<div class="col-sm-10">
													<select name="dept_status" class="form-control @error('dept_status') is-invalid @enderror">
														<option value="1" {{ old('dept_status', $d->dept_status) == '1' ? 'selected' : '' }}>
															Active</option>
														<option value="0" {{ old('dept_status', $d->dept_status) == '0' ? 'selected' : '' }}>Inactive
														</option>
													</select>
													@error('dept_status')
														<div class="invalid-feedback">
															{{ $message }}
														</div>
													@enderror
												</div>
											</div>
										</div>
										<!-- /.card-body -->
										<div class="card-footer">
											<button type="submit" class="btn btn-info">Submit</button>
											<button type="reset" class="btn btn-default float-right">Cancel</button>
										</div>
										<!-- /.card-footer -->
									</form>
								</div>
							@endforeach --}}

							<div class="card-body">
								@if (session('success'))
									<div class="alert alert-success alert-dismissible show fade">
										<div class="alert-body">
											<button class="close" data-dismiss="alert">
												<span>&times;</span>
											</button>
											{{ session('success') }}
										</div>
									</div>
								@elseif (session('error'))
									<div class="alert alert-error alert-dismissible show fade">
										<div class="alert-body">
											<button class="close" data-dismiss="alert">
												<span>&times;</span>
											</button>
											{{ session('error') }}
										</div>
									</div>
								@endif
								<div class="tab-content p-0">
									<table id="example1" class="table table-sm table-bordered table-striped">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th>Department Name</th>
												<th class="text-center">Status</th>
												<th class="text-center">Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($departments as $department)
												<tr>
													<td class="text-center">{{ $loop->iteration }}</td>
													<td>{{ $department->dept_name }}</td>
													<td class="text-center">
														@if ($department->dept_status == 1)
															<span class="badge badge-success">Active</span>
														@else
															<span class="badge badge-danger">Inactive</span>
														@endif
													</td>
													<td class="text-center">
														<a class="btn btn-icon btn-primary" href="{{ url('departments/' . $department->id . '/edit') }}"><i
																class="fas fa-pen-square"></i></a>
														<form action="{{ url('departments/' . $department->id) }}" method="post"
															onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
															@method('delete')
															@csrf
															<button class="btn btn-icon btn-danger"><i class="fas fa-times"></i></button>
														</form>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div><!-- /.card-body -->
						</div>
					</div>
					<!-- /.card -->
				</section>
				<!-- right col -->
			</div>
			<!-- /.row (main row) -->
		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
@endsection

@section('styles')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('scripts')
	<!-- DataTables  & Plugins -->
	<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
	<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
	<!-- Page specific script -->
	<script>
	 $(function() {
	  $("#example1").DataTable({
	   "responsive": true,
	   "lengthChange": false,
	   "autoWidth": false,
	   "buttons": ["copy", "csv", "excel", "pdf", "print"]
	  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
	 });
	</script>

	@if (Session::has('errors'))
		<script>
		 $(document).ready(function() {
		    $('#modal-default').modal({
		     show: true
		    });
		   }
		</script>
	@endif
@endsection
