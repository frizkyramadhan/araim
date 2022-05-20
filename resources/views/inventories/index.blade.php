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
											<a class="btn btn-warning" href="{{ url('inventories/create') }}"><i class="fas fa-plus"></i>
												Add</a>
										</li>
									</ul>
								</div>
							</div><!-- /.card-header -->
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
												<th>Inventory No</th>
												<th>Date</th>
												<th>Asset</th>
												<th>Brand</th>
												<th>Model</th>
												<th>S/N</th>
												<th>PIC</th>
												<th>Project</th>
												<th class="text-center">Status</th>
												<th class="text-center">Action</th>
											</tr>
										</thead>
										{{-- <tbody>
											@foreach ($inventories as $employee)
												<tr>
													<td class="text-center">{{ $loop->iteration }}</td>
													<td>{{ $employee->nik }}</td>
													<td>{{ $employee->fullname }}</td>
													<td>{{ $employee->position->position_name ?? 'null' }}</td>
													<td>{{ $employee->project->project_code ?? 'null' }}</td>
													<td class="text-center">
														@if ($employee->status == 1)
															<span class="badge badge-success">Active</span>
														@else
															<span class="badge badge-danger">Inactive</span>
														@endif
													</td>
													<td class="text-center">
														<a class="btn btn-icon btn-success" href="{{ url('inventories/' . $employee->id) }}"><i
																class="fas fa-info-circle"></i></a>
														<a class="btn btn-icon btn-primary" href="{{ url('inventories/' . $employee->id . '/edit') }}"><i
																class="fas fa-pen-square"></i></a>
														<form action="{{ url('inventories/' . $employee->id) }}" method="post"
															onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
															@method('delete')
															@csrf
															<button class="btn btn-icon btn-danger"><i class="fas fa-times"></i></button>
														</form>
													</td>
												</tr>
											@endforeach
										</tbody> --}}
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
	{{-- <script>
	 	$(function() {
	  		$("#example1").DataTable({
	   	"responsive": true,
			"lengthChange": false,
			"autoWidth": false,
			"buttons": ["copy", "csv", "excel", "pdf", "print"]
	  	}).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
	});
	</script> --}}
	<script>
	 $(function() {
	  var table = $("#example1").DataTable({
	   responsive: true,
	   lengthChange: true,
	   autoWidth: false,
	   dom: 'lBfrtpi',
	   buttons: ["copy", "csv", "excel", "pdf", "print"],
	   processing: true,
	   serverSide: true,
	   ajax: {
	    url: "{{ route('inventories.getInventories') }}",
	    data: function(d) {
	     d.search = $("input[type=search][aria-controls=example1]").val()
	     console.log(d);
	    }
	   },
	   columns: [{
	    data: 'DT_RowIndex',
	    orderable: false,
	    searchable: false,
	    className: 'text-center'
	   }, {
	    data: "inventory_no",
	    name: "inventory_no",
	    orderable: false,
	   }, {
	    data: "input_date",
	    name: "input_date",
	    orderable: false,
	   }, {
	    data: "asset_name",
	    name: "asset_name",
	    orderable: false,
	   }, {
	    data: "brand",
	    name: "brand",
	    orderable: false,
	   }, {
	    data: "model_asset",
	    name: "model_asset",
	    orderable: false,
	   }, {
	    data: "serial_no",
	    name: "serial_no",
	    orderable: false,
	   }, {
	    data: "fullname",
	    name: "fullname",
	    orderable: false,
	   }, {
	    data: "project_code",
	    name: "project_code",
	    orderable: false,
	   }, {
	    data: "inventory_status",
	    name: "inventory_status",
	    className: "text-center",
	    orderable: false,
	   }, {
	    data: "action",
	    name: "action",
	    orderable: false,
	    searchable: false,
	    className: "text-center"
	   }],
	   fixedColumns: true,
	  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
	 });
	</script>
@endsection
