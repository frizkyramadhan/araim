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
									<hr>
									<div class="form-group">
										<label class="col-form-label">Existing Assets</label>
										<div class="float-right">
											<a class="btn btn-success" href="{{ url('inventories/print_qrcode_employee/' . $employee->id) }}"
												target="_blank"><i class="fas fa-qrcode"></i>
												Print</a>
											<a class="btn btn-warning" href="{{ url('inventories/create/' . $employee->id) }}"><i
													class="fas fa-plus"></i>
												Add</a>
										</div>
									</div>
									<div class="table-responsive">
										<table id="example1" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th class="text-center align-middle">No</th>
													<th class="align-middle">Inventory No</th>
													<th class="align-middle">Date</th>
													<th class="align-middle">Asset</th>
													<th class="align-middle">Brand</th>
													<th class="align-middle">S/N / P/N</th>
													<th class="align-middle">QR Code</th>
													<th class="text-center align-middle">Inventory Status</th>
													<th class="text-center align-middle">Transfer Status</th>
													<th class="align-middle">PO No</th>
													<th class="text-center align-middle">Action</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($inventories as $inventory)
													<tr>
														<td class="text-center">{{ $loop->iteration }}</td>
														<td class="text-center">
															{{ $inventory->inventory_no }}
															<a href="{{ url('inventories/qrcode/' . $inventory->id) }}" class="btn btn-xs btn-secondary"><i
																	class="fas fa-qrcode"></i> Generate</a>
														</td>
														<td>{{ $inventory->input_date }}</td>
														<td>{{ $inventory->asset->asset_name }}</td>
														<td>{{ $inventory->brand }} - {{ $inventory->model_asset }}</td>
														<td>{{ $inventory->serial_no }} / {{ $inventory->part_no }}</td>
														<td class="text-center">
															@if ($inventory->qrcode)
																<a href="{{ url('inventories/print_qrcode/' . $inventory->id) }}" target="_blank">
																	<img src="{{ asset('storage/qrcode/' . $inventory->qrcode) }}" alt="QR Code" width="100px">
																</a>
																<a href="{{ url('inventories/delete_qrcode/' . $inventory->id) }}" class="btn btn-xs btn-danger"
																	onclick="return confirm('Are you sure to delete this qrcode?')">
																	<i class="fas fa-trash"> Delete</i>
																</a>
															@endif
														</td>
														<td class="text-center">
															@if ($inventory->inventory_status == 'Good')
																<span class="badge badge-primary">Good</span>
															@elseif ($inventory->inventory_status == 'Broken')
																<span class="badge badge-danger">Broken</span>
															@endif
														</td>
														<td class="text-center">
															@if ($inventory->transfer_status == 'Available')
																<span class="badge badge-success">Available</span>
															@elseif ($inventory->transfer_status == 'Discarded')
																<span class="badge badge-secondary">Discarded</span>
															@elseif ($inventory->transfer_status == 'Mutated')
																<span class="badge badge-warning">Mutated</span>
															@endif
														</td>
														<td>{{ $inventory->po_no }}</td>
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
