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
								{{-- @dd($inventory->toArray()) --}}
								@if ($errors->any())
									<div class="alert alert-danger alert-dismissible show fade">
										<button class="close" data-dismiss="alert">
											<span>&times;</span>
										</button>
										<ul>
											@foreach ($errors->all() as $error)
												<li>{{ $error }}</li>
											@endforeach
										</ul>
									</div>
								@endif
								<div class="row">
									<div class="col-5 col-sm-3">
										<div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
											<a class="nav-link active" id="vert-tabs-pic-tab" data-toggle="pill" href="#vert-tabs-pic" role="tab"
												aria-controls="vert-tabs-pic" aria-selected="true">PIC</a>
											<a class="nav-link" id="vert-tabs-asset-tab" data-toggle="pill" href="#vert-tabs-asset" role="tab"
												aria-controls="vert-tabs-asset" aria-selected="false">Asset</a>
											<a class="nav-link" id="vert-tabs-location-tab" data-toggle="pill" href="#vert-tabs-location" role="tab"
												aria-controls="vert-tabs-location" aria-selected="false">Location</a>
											<a class="nav-link" id="vert-tabs-reference-tab" data-toggle="pill" href="#vert-tabs-reference"
												role="tab" aria-controls="vert-tabs-reference" aria-selected="false">Reference</a>
											<a class="nav-link" id="vert-tabs-specification-tab" data-toggle="pill" href="#vert-tabs-specification"
												role="tab" aria-controls="vert-tabs-specification" aria-selected="false">Specification <label
													class="text-danger text-sm">* if available</label></a>
										</div>
									</div>
									<div class="col-7 col-sm-9">
										<div class="tab-content" id="vert-tabs-tabContent">
											<div class="tab-pane text-left fade show active" id="vert-tabs-pic" role="tabpanel"
												aria-labelledby="vert-tabs-pic-tab">
												<dl class="row">
													<dt class="col-sm-4">Inventory No</dt>
													<dd class="col-sm-8">{{ $inventory->inventory_no }}</dd>
													<dt class="col-sm-4">Posting Date</dt>
													<dd class="col-sm-8">{{ $inventory->input_date }}</dd>
													<dt class="col-sm-4">Person in Charge</dt>
													<dd class="col-sm-8">{{ $inventory->employee->fullname }} - {{ $inventory->employee->nik }}</dd>
												</dl>
											</div>
											<div class="tab-pane fade" id="vert-tabs-asset" role="tabpanel" aria-labelledby="vert-tabs-asset-tab">
												<dl class="row">
													<dt class="col-sm-4">Asset</dt>
													<dd class="col-sm-8">{{ $inventory->asset->asset_name }}</dd>
													<dt class="col-sm-4">Brand</dt>
													<dd class="col-sm-8">{{ $inventory->brand ?? '-' }}</dd>
													<dt class="col-sm-4">Model</dt>
													<dd class="col-sm-8">{{ $inventory->model_asset ?? '-' }}</dd>
													<dt class="col-sm-4">Serial No</dt>
													<dd class="col-sm-8">{{ $inventory->serial_no ?? '-' }}</dd>
													<dt class="col-sm-4">Part No</dt>
													<dd class="col-sm-8">{{ $inventory->part_no ?? '-' }}</dd>
													<dt class="col-sm-4">Quantity</dt>
													<dd class="col-sm-8">{{ $inventory->quantity ?? '-' }}</dd>
													<dt class="col-sm-4">Status</dt>
													<dd class="col-sm-8">{{ $inventory->inventory_status }}</dd>
												</dl>
											</div>
											<div class="tab-pane fade" id="vert-tabs-location" role="tabpanel" aria-labelledby="vert-tabs-location-tab">
												<dl class="row">
													<dt class="col-sm-4">Project</dt>
													<dd class="col-sm-8">{{ $inventory->project->project_code ?? '-' }} -
														{{ $inventory->project->project_name ?? '-' }}</dd>
													<dt class="col-sm-4">Department</dt>
													<dd class="col-sm-8">{{ $inventory->department->dept_name ?? '-' }}</dd>
													<dt class="col-sm-4">Location</dt>
													<dd class="col-sm-8">{{ $inventory->location ?? '-' }}</dd>
												</dl>
											</div>
											<div class="tab-pane fade" id="vert-tabs-reference" role="tabpanel"
												aria-labelledby="vert-tabs-reference-tab">
												<dl class="row">
													<dt class="col-sm-4">Reference No</dt>
													<dd class="col-sm-8">{{ $inventory->reference_no ?? '-' }}</dd>
													<dt class="col-sm-4">Reference Date</dt>
													<dd class="col-sm-8">{{ $inventory->reference_date ?? '-' }}</dd>
													<dt class="col-sm-4">PO No</dt>
													<dd class="col-sm-8">{{ $inventory->po_no ?? '-' }}</dd>
													<dt class="col-sm-4">Remarks</dt>
													<dd class="col-sm-8">{{ $inventory->remarks ?? '-' }}</dd>
												</dl>
											</div>
											<div class="tab-pane fade" id="vert-tabs-specification" role="tabpanel"
												aria-labelledby="vert-tabs-specification-tab">
												<div class="table-responsive">
													<table class="table table-striped table-hover">
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
																	<td>
																		{{ $spec->component->component_name }}
																	</td>
																	<td>
																		{{ $spec->specification }}
																	</td>
																	<td>
																		{{ $spec->spec_remarks }}
																	</td>
																</tr>
															@endforeach
														</tbody>
													</table>
												</div>
											</div>
										</div>
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
