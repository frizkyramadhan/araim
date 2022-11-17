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
											<a class="btn btn-primary" href="{{ url('bapbs/' . $bapb->bapb_no . '/edit') }}"><i
													class="fas fa-pen-square"></i>
												Edit</a>
											<a class="btn btn-success" href="{{ url('bapbs/' . $bapb->bapb_no . '/print') }}"><i
													class="fas fa-print"></i>
												Print</a>
											<a class="btn btn-warning text-dark" href="{{ url('bapbs') }}"><i class="fas fa-undo-alt"></i>
												Back</a>
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
								@endif
								<div class="row">
									<div class="col-md-4">
										<div class="card card-primary">
											<div class="card-header">
												<h3 class="card-title">BAPB Detail</h3>
												<div class="card-tools">
													<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
														<i class="fas fa-minus"></i>
													</button>
												</div>
											</div>
											<div class="card-body">
												<table width=100% class="table table-sm table-borderless">
													<tr>
														<td style="width: 20%">No</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $bapb->bapb_reg }}</b></td>
													</tr>
													<tr>
														<td>Date</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $bapb->bapb_date }}</b></td>
													</tr>
													<tr>
														<td colspan="3">Who Submit</td>
													</tr>
													<tr>
														<td>Name</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $bapb->submit_name }}</b></td>
													</tr>
													<tr>
														<td>NIK</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $bapb->submit_nik }}</b></td>
													</tr>
													<tr>
														<td>Position</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $bapb->submit_pos }}</b></td>
													</tr>
													<tr>
														<td colspan="3">Who Receive</td>
													</tr>
													<tr>
														<td>Name</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $bapb->receive_name }}</b></td>
													</tr>
													<tr>
														<td>NIK</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $bapb->receive_nik }}</b></td>
													</tr>
													<tr>
														<td>Position</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $bapb->receive_pos }}</b></td>
													</tr>
													<tr>
														<td>Duration</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $bapb->duration }} Days</b></td>
													</tr>
												</table>
											</div>
											<!-- /.card-body -->
										</div>
										<!-- /.card -->
									</div>
									<div class="col-md-8">
										<div class="card card-warning">
											<div class="card-header">
												<h3 class="card-title">Asset Detail</h3>
												<div class="card-tools">
													<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
														<i class="fas fa-minus"></i>
													</button>
												</div>
											</div>
											<div class="card-body">
												<div class="table-responsive">
													<table width=100% class="table table-sm table-striped table-hover">
														<thead>
															<tr>
																<th style="vertical-align: middle">Inventory No</th>
																<th style="vertical-align: middle">Asset</th>
																<th style="vertical-align: middle">Brand</th>
																<th style="vertical-align: middle">Model</th>
																<th style="vertical-align: middle">S/N</th>
																<th style="vertical-align: middle">Date</th>
																<th class="text-center" style="vertical-align: middle">Inventory Status</th>
																<th class="text-center" style="vertical-align: middle">Transfer Status</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($bapb_row as $row)
																<tr>
																	<td>{{ $row->inventory_no }}</td>
																	<td>{{ $row->asset_name }}</td>
																	<td>{{ $row->brand }}</td>
																	<td>{{ $row->model_asset }}</td>
																	<td>{{ $row->serial_no }}</td>
																	<td>{{ $row->input_date }}</td>
																	<td class="text-center">
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
																	</td>
																</tr>
															@endforeach
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<!-- /.card-body -->
									</div>
								</div>
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
