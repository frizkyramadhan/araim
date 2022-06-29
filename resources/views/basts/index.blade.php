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
											<a class="btn btn-warning" href="{{ url('basts/create') }}"><i class="fas fa-plus"></i>
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
												<th>Reg. No</th>
												<th>Date</th>
												<th>Who Submit</th>
												<th>Who Receive</th>
												<th class="text-center">Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($basts as $bast)
												<tr>
													<td class="text-center">{{ $loop->iteration }}</td>
													<td>{{ $bast->bast_reg }}</td>
													<td>{{ $bast->bast_date }}</td>
													<td>{{ $bast->submit_name }}</td>
													<td>{{ $bast->receive_name }}</td>
													<td class="text-center">
														<div class="btn-group">
															<a title="Detail" class="btn btn-icon btn-success" href="{{ url('basts/' . $bast->bast_no) }}"><i
																	class="fas fa-info-circle"></i></a>
															<button type="button" class="btn btn-success dropdown-toggle dropdown-icon" data-toggle="dropdown">
																<span class="sr-only">Toggle Dropdown</span>
															</button>
															<div class="dropdown-menu" role="menu">
																<a class="dropdown-item" href="{{ url('basts/' . $bast->bast_no . '/edit') }}"><i
																		class="fas fa-pen-square"></i>
																	Edit</a>
																<div class="dropdown-divider"></div>
																<form action="{{ url('basts/' . $bast->bast_no) }}" method="post"
																	onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
																	@method('delete')
																	@csrf
																	<button class="dropdown-item bg-danger" title="Delete"><i class="fas fa-times"></i>
																		Delete</button>
																</form>
															</div>
														</div>
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
@endsection
