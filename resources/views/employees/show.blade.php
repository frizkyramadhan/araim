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
											<a class="btn btn-primary" href="{{ url('employees/' . $employee->id . '/edit') }}"><i
													class="fas fa-pen-square"></i>
												Edit</a>
											<a class="btn btn-warning" href="{{ url('employees') }}"><i class="fas fa-undo-alt"></i>
												Back</a>
										</li>
									</ul>
								</div>
							</div><!-- /.card-header -->
							<div class="card-body">
								<div class="tab-content p-0">
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">NIK</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="nik" value="{{ $employee->nik }}" disabled>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Full Name</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="fullname" value="{{ $employee->fullname }}" disabled>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Position</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="position_id"
												value="{{ $employee->position->position_name }}" disabled>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Project</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="project_id"
												value="{{ $employee->project->project_code }} - {{ $employee->project->project_name }}" disabled>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Email</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="email" value="{{ $employee->email }}" disabled>
										</div>
									</div>
									<div class="form-group text-center">
										<label class="col-form-label">Existing Assets</label>
										<a class="btn btn-warning float-right" href="{{ url('inventories/create/' . $employee->id) }}"><i
												class="fas fa-plus"></i>
											Add</a>
									</div>
									<div class="table-responsive">
										<table id="example1" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th class="text-center">No</th>
													<th>Inventory No</th>
													<th>Date</th>
													<th>Asset</th>
													<th>Brand / Model</th>
													<th>S/N / P/N</th>
													<th>PO No</th>
													<th>QR Code</th>
													<th class="text-center">Status</th>
													<th class="text-center">Action</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($inventories as $inventory)
													<tr>
														<td class="text-center">{{ $loop->iteration }}</td>
														<td>{{ $inventory->inventory_no }}</td>
														<td>{{ $inventory->input_date }}</td>
														<td>{{ $inventory->asset->asset_name }}</td>
														<td>{{ $inventory->brand }} - {{ $inventory->model_asset }}</td>
														<td>{{ $inventory->serial_no }} / {{ $inventory->part_no }}</td>
														<td>{{ $inventory->po_no }}</td>
														<td>{{ $inventory->qrcode }}</td>
														<td class="text-center">
															@if ($inventory->inventory_status == 'Good')
																<span class="badge badge-primary">Good</span>
															@elseif ($inventory->inventory_status == 'Broken')
																<span class="badge badge-danger">Broken</span>
															@elseif ($inventory->inventory_status == 'Mutated')
																<span class="badge badge-warning">Mutated</span>
															@elseif ($inventory->inventory_status == 'Discarded')
																<span class="badge badge-secondary">Discarded</span>
															@endif
														</td>
														<td class="text-center">
															<a title="Detail" class="btn btn-icon btn-success" href="{{ url('inventories/' . $inventory->id) }}"><i
																	class="fas fa-info-circle"></i></a>
															<a class="btn btn-icon btn-primary" href="{{ url('inventories/' . $inventory->id . '/edit') }}"><i
																	class="fas fa-pen-square"></i></a>
															<form action="{{ url('inventories/' . $inventory->id) }}" method="post"
																onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
																@method('delete')
																@csrf
																<button class="btn btn-icon btn-danger" title="Delete"><i class="fas fa-times"></i></button>
															</form>

														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
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
@endsection
