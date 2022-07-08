@extends('layouts.main')

@section('content')
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">{{ $subtitle }}</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">Dashboard</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<!-- Small boxes (Stat box) -->
			<div class="row">
				<div class="col-lg-3 col-6">
					<!-- small box -->
					<div class="small-box bg-info">
						<div class="inner">
							<h3>{{ $total_inv->sum }}</h3>
							<p>Total Inventories</p>
						</div>
						<div class="icon">
							<i class="fas fa-boxes"></i>
						</div>
						{{-- <a href="{{ url('inventories') }}" class="small-box-footer">More info <i
								class="fas fa-arrow-circle-right"></i></a> --}}
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-6">
					<!-- small box -->
					<div class="small-box bg-success">
						<div class="inner">
							<h3>{{ $good_inv->sum }}</h3>

							<p>Good Inventories</p>
						</div>
						<div class="icon">
							<i class="fas fa-check"></i>
						</div>
						{{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-6">
					<!-- small box -->
					<div class="small-box bg-danger">
						<div class="inner">
							<h3>{{ $broken_inv->sum }}</h3>

							<p>Broken Inventories</p>
						</div>
						<div class="icon">
							<i class="fas fa-times"></i>
						</div>
						{{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-6">
					<!-- small box -->
					<div class="small-box bg-warning">
						<div class="inner">
							<h3>{{ $total_emp }}</h3>

							<p>Total Employees</p>
						</div>
						<div class="icon">
							<i class="fas fa-users"></i>
						</div>
						{{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
					</div>
				</div>
				<!-- ./col -->
			</div>
			<!-- /.row -->
			<!-- Main row -->
			<div class="row">
				<!-- Left col -->
				<section class="col-lg-7 connectedSortable">
					<!-- Custom tabs (Charts with tabs)-->
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">
								<i class="fas fa-boxes mr-1"></i>
								Available Inventory by Assets
							</h3>
						</div><!-- /.card-header -->
						<div class="card-body">
							<div class="tab-content p-0">
								<div class="table-responsive">
									<table id="example1" class="table table-sm table-hover">
										<thead>
											<tr>
												<th>Assets</th>
												<th class="text-right pr-5">Total</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($asset_sum as $row)
												<tr>
													<td><a href="{{ url('dashboard/summary/' . $row->id) }}">{{ $row->asset_name }}</a></td>
													<td class="text-right pr-5">{{ $row->asset_sum }}</td>
												</tr>
											@endforeach
										</tbody>
										<tfoot>
											<tr>
												<th style="text-align:right">Total:</th>
												<th style="text-align:right"></th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div><!-- /.card-body -->
					</div>
					<!-- /.card -->
				</section>
				<!-- /.Left col -->
				<!-- right col (We are only adding the ID to make the widgets sortable)-->
				<section class="col-lg-5 connectedSortable">
					<!-- DONUT CHART -->
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Available Inventory by Project</h3>
						</div>
						<div class="card-body">
							<div class="form-group">
								<select onchange="updateChartAsset(this)" class="custom-select form-control-border">
									@foreach ($projects as $project)
										<option value="{{ $project->id }}">{{ $project->project_code }} -
											{{ $project->project_name }}</option>
									@endforeach
								</select>
							</div>
							<canvas id="myChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
						</div>
						<!-- /.card-body -->
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
	     .column(1)
	     .data()
	     .reduce(function(a, b) {
	      return intVal(a) + intVal(b);
	     }, 0);

	    // Total over this page
	    pageTotal = api
	     .column(1, {
	      page: 'current'
	     })
	     .data()
	     .reduce(function(a, b) {
	      return intVal(a) + intVal(b);
	     }, 0);

	    // Update footer
	    $(api.column(1).footer()).html(pageTotal + ' (' + total + ' total)');
	   }
	  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
	 });
	</script>

	<!-- ChartJS -->
	<script src="{{ asset('assets/plugins/chart.js/dist/chart.js') }}"></script>
	<script>
    // setup 
	const assetValues = [
		@foreach($projectAssets as $asset)
		{
	   x: {
			{{ $asset->id }}: '{{ $asset->asset_name }}'
		 },
	   y: {
	    {{ $asset->id }}: {{ $asset->count }},
	   }
	  },
		@endforeach
	 ]
	 console.log(assetValues)

	 var backgroundcolor = [];
	 var bordercolor = [];
	 for (i = 0; i < assetValues.length; i++) {
	  var r = Math.floor(Math.random() * 255);
	  var g = Math.floor(Math.random() * 255);
	  var b = Math.floor(Math.random() * 255);
	  backgroundcolor.push('rgba(' + r + ', ' + g + ', ' + b + ', 0.7)');
	  bordercolor.push('rgba(' + r + ', ' + g + ', ' + b + ', 1)');
	 }

    const data = {
      datasets: [{
				label: 'Inventory by Project',
        data: assetValues,
        backgroundColor: backgroundcolor,
        borderColor: bordercolor,
        borderWidth: 1,
				parsing: {
					xAxisKey: 'x.1',
					yAxisKey: 'y.1'
				}
      }]
    };

    // config 
    const config = {
      type: 'bar',
      data,
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    };

    // render init block
    const myChart = new Chart(
      document.getElementById('myChart'),
      config
    );

		function updateChartAsset(option){
			myChart.data.datasets[0].parsing.xAxisKey = `x.${option.value}`;
			myChart.data.datasets[0].parsing.yAxisKey = `y.${option.value}`;
			myChart.update();
		}
    </script>
@endsection
