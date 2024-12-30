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
											<a class="btn btn-warning" href="{{ url('/') }}"><i class="fas fa-undo-alt"></i>
												Back</a>
										</li>
									</ul>
								</div>
							</div><!-- /.card-header -->
							<div class="card-body">
								<div class="tab-content p-0">
									<table id="example1" class="table table-sm table-bordered table-striped">
										<thead>
											<tr>
												<th class="text-center">No</th>
												<th>Inventory No</th>
												<th>Brand</th>
												<th>Model</th>
												<th>S/N</th>
												<th>PIC</th>
												<th>Project Asset</th>
												{{-- <th>Inventory Status</th>
												<th>Transfer Status</th> --}}
												<th class="text-center">Qty</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($summary as $row)
												<tr>
													<td class="text-center">{{ $loop->iteration }}</td>
													<td><a href="{{ url('inventories/' . $row->id) }}">{{ $row->inventory_no }}</a></td>
													<td>{{ $row->brand->brand_name }}</td>
													<td>{{ $row->model_asset }}</td>
													<td>{{ $row->serial_no }}</td>
													<td>{{ $row->employee->fullname ?? '' }}</td>
													<td>{{ $row->project->project_code ?? '' }}</td>
													{{-- <td class="text-center">
                            @if ($row->inventory_status == 'Good')
                            <span class="badge badge-primary">Good</span>
														@elseif ($row->inventory_status == 'Broken')
                            <span class="badge badge-danger">Broken</span>
														@endif
													</td>
													<td class="text-center">
                            @if ($row->transfer_status == 'Available')
                            <span class="badge badge-success">Available</span>
														@elseif ($row->transfer_status == 'Discarded')
                            <span class="badge badge-secondary">Discarded</span>
														@elseif ($row->transfer_status == 'Mutated')
                            <span class="badge badge-warning">Mutated</span>
														@endif
													</td> --}}
													<td class="text-right pr-3">{{ $row->quantity }}</td>
												</tr>
											@endforeach
										</tbody>
										<tfoot>
											<tr>
												<th colspan="7" style="text-align:right">Total:</th>
												<th></th>
											</tr>
										</tfoot>
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
	   "buttons": ["copy", "csv", "excel", "pdf", "print"],
	   footerCallback: function(row, data, start, end, display) {
	    var api = this.api();

	    // Remove the formatting to get integer data for summation
	    var intVal = function(i) {
	     return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
	    };

	    // Total over all pages
	    total = api
	     .column(7)
	     .data()
	     .reduce(function(a, b) {
	      return intVal(a) + intVal(b);
	     }, 0);

	    // Total over this page
	    pageTotal = api
	     .column(7, {
	      page: 'current'
	     })
	     .data()
	     .reduce(function(a, b) {
	      return intVal(a) + intVal(b);
	     }, 0);

	    // Update footer
	    $(api.column(7).footer()).html(pageTotal + ' (' + total + ' total)');
	   }
	  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
	 });
	</script>
@endsection
