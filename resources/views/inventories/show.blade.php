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
						<div class="card card-dark">
							<div class="card-header">
								<h3 class="card-title">
									<strong>{{ $subtitle }}</strong>
								</h3>
								<div class="card-tools">
									<ul class="nav nav-pills ml-auto">
										<li class="nav-item mr-2">
											<a class="btn btn-primary" href="{{ url('inventories/' . $inventory->id . '/edit') }}"><i
													class="fas fa-pen-square"></i>
												Edit</a>
											<a class="btn btn-warning" href="{{ url('inventories') }}"><i class="fas fa-undo-alt"></i>
												Back</a>
										</li>
									</ul>
								</div>
							</div><!-- /.card-header -->
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="card card-primary">
											<div class="card-header">
												<h3 class="card-title">PIC</h3>
												<div class="card-tools">
													<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
														<i class="fas fa-minus"></i>
													</button>
												</div>
											</div>
											<div class="card-body">
												<table width=100% class="table table-borderless">
													<tr>
														<td style="width: 20%">No</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->inventory_no }}</b></td>
													</tr>
													<tr>
														<td>Date</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->input_date }}</b></td>
													</tr>
													<tr>
														<td>PIC</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->employee->fullname }}</b> - <b>{{ $inventory->employee->nik }}</b></td>
													</tr>
												</table>
											</div>
											<!-- /.card-body -->
										</div>
										<!-- /.card -->
										<div class="card card-success">
											<div class="card-header">
												<h3 class="card-title">Asset Detail</h3>

												<div class="card-tools">
													<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
														<i class="fas fa-minus"></i>
													</button>
												</div>
											</div>
											<div class="card-body">
												<table width=100% class="table table-borderless">
													<tr>
														<td style="width: 20%">Asset</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->asset->asset_name }}</b></td>
													</tr>
													<tr>
														<td>Brand</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->brand }}</b></td>
													</tr>
													<tr>
														<td>Model</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->model_asset }}</b></td>
													</tr>
													<tr>
														<td>Serial No</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->serial_no }}</b></td>
													</tr>
													<tr>
														<td>Part No</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->part_no }}</b></td>
													</tr>
													<tr>
														<td>Quantity</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->quantity }}</b></td>
													</tr>
													<tr>
														<td>Status</td>
														<td style="width: 5%">:</td>
														<td><b>
																@if ($inventory->inventory_status == 'Good')
																	<span class="badge badge-primary">Good</span>
																@elseif ($inventory->inventory_status == 'Broken')
																	<span class="badge badge-danger">Broken</span>
																@elseif ($inventory->inventory_status == 'Mutated')
																	<span class="badge badge-warning">Mutated</span>
																@elseif ($inventory->inventory_status == 'Discarded')
																	<span class="badge badge-secondary">Discarded</span>
																@endif
															</b></td>
													</tr>
												</table>
											</div>
											<!-- /.card-body -->
										</div>
										<!-- /.card -->
									</div>
									<div class="col-md-6">
										<div class="card card-warning">
											<div class="card-header">
												<h3 class="card-title">Asset Location</h3>
												<div class="card-tools">
													<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
														<i class="fas fa-minus"></i>
													</button>
												</div>
											</div>
											<div class="card-body">
												<table width=100% class="table table-borderless">
													<tr>
														<td style="width: 20%">Project</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->project->project_code }}</b> - <b>{{ $inventory->project->project_name }}</b>
														</td>
													</tr>
													<tr>
														<td>Department</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->department->dept_name }}</b></td>
													</tr>
													<tr>
														<td>Location</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->location }}</b></td>
													</tr>
												</table>
											</div>
										</div>
										<!-- /.card-body -->
										<div class="card card-danger">
											<div class="card-header">
												<h3 class="card-title">References</h3>
												<div class="card-tools">
													<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
														<i class="fas fa-minus"></i>
													</button>
												</div>
											</div>
											<div class="card-body">
												<table width=100% class="table table-borderless">
													<tr>
														<td style="width: 20%">Reference No</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->reference_no }}</b></td>
													</tr>
													<tr>
														<td>Reference Date</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->reference_date }}</b></td>
													</tr>
													<tr>
														<td>PO No</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->po_no }}</b></td>
													</tr>
													<tr>
														<td>Remarks</td>
														<td style="width: 5%">:</td>
														<td><b>{{ $inventory->remarks }}</b></td>
													</tr>
												</table>
											</div>
											<!-- /.card-body -->
										</div>
									</div>
									<div class="col-md-12">
										<div class="card card-info">
											<div class="card-header">
												<h3 class="card-title">Specification <label class="text-danger text-sm">*if available</label></h3>
												<div class="card-tools">
													<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
														<i class="fas fa-minus"></i>
													</button>
												</div>
											</div>
											<div class="card-body">
												<div class="table-responsive">
													<table width=100% class="table table-striped table-hover" id="dynamicAddRemove">
														<thead>
															<tr>
																<th style="vertical-align: middle">Component</th>
																<th style="vertical-align: middle">Description</th>
																<th style="vertical-align: middle">Remarks</th>
															</tr>
														</thead>
														<tbody>
															@foreach ($specifications as $spec)
																<tr>
																	<td>{{ $spec->component->component_name }}</td>
																	<td>{{ $spec->specification }}</td>
																	<td>{{ $spec->spec_remarks }}</td>
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
