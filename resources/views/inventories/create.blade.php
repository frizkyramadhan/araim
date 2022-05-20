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
											<a class="btn btn-warning" href="{{ url('inventories') }}"><i class="fas fa-undo-alt"></i>
												Back</a>
										</li>
									</ul>
								</div>
							</div><!-- /.card-header -->

							<form class="form-horizontal" action="{{ url('inventories') }}" method="POST">
								@csrf
								<div class="card-body">
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
												<a class="nav-link" id="vert-tabs-location-tab" data-toggle="pill" href="#vert-tabs-location"
													role="tab" aria-controls="vert-tabs-location" aria-selected="false">Location</a>
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
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Inventory No</label>
														<div class="col-sm-10">
															<input type="text" class="form-control @error('inventory_no') is-invalid @enderror" name="inventory_no"
																value="{{ $year }}{{ $month }}{{ $inv_no }}" readonly>
															@error('inventory_no')
																<div class="error invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Posting Date</label>
														<div class="col-sm-10">
															<input type="date" class="form-control @error('input_date') is-invalid @enderror" name="input_date"
																value="{{ old('input_date') }}">
															@error('input_date')
																<div class="error invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Person in Charge</label>
														<div class="col-sm-10">
															<select name="employee_id" class="form-control @error('employee_id') is-invalid @enderror select2bs4"
																style="width: 100%;">
																@foreach ($employees as $employee)
																	<option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
																		{{ $employee->fullname }} - {{ $employee->nik }}
																	</option>
																@endforeach
															</select>
															@error('employee_id')
																<div class="invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
												</div>
												<div class="tab-pane fade" id="vert-tabs-asset" role="tabpanel" aria-labelledby="vert-tabs-asset-tab">
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Asset</label>
														<div class="col-sm-10">
															<select name="asset_id" class="form-control @error('asset_id') is-invalid @enderror select2bs4"
																style="width: 100%;">
																@foreach ($assets as $asset)
																	<option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
																		{{ $asset->asset_name }}
																	</option>
																@endforeach
															</select>
															@error('asset_id')
																<div class="invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Brand</label>
														<div class="col-sm-10">
															<input type="text" class="form-control @error('brand') is-invalid @enderror" name="brand"
																value="{{ old('brand') }}">
															@error('brand')
																<div class="error invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Model</label>
														<div class="col-sm-10">
															<input type="text" class="form-control @error('model_asset') is-invalid @enderror" name="model_asset"
																value="{{ old('model_asset') }}">
															@error('model_asset')
																<div class="error invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Serial No</label>
														<div class="col-sm-10">
															<input type="text" class="form-control @error('serial_no') is-invalid @enderror" name="serial_no"
																value="{{ old('serial_no') }}">
															@error('serial_no')
																<div class="error invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Part No</label>
														<div class="col-sm-10">
															<input type="text" class="form-control @error('part_no') is-invalid @enderror" name="part_no"
																value="{{ old('part_no') }}">
															@error('part_no')
																<div class="error invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Quantity</label>
														<div class="col-sm-10">
															<input type="text" class="form-control @error('quantity') is-invalid @enderror" name="quantity"
																value="{{ old('quantity') }}">
															@error('quantity')
																<div class="error invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Status</label>
														<div class="col-sm-10">
															<select name="inventory_status"
																class="form-control @error('inventory_status') is-invalid @enderror select2bs4" style="width: 100%;">
																<option value="Good" {{ old('inventory_status') == 'Good' ? 'selected' : '' }}>
																	Good
																</option>
																<option value="Mutated" {{ old('inventory_status') == 'Mutated' ? 'selected' : '' }}>
																	Mutated
																</option>
																<option value="Broken" {{ old('inventory_status') == 'Broken' ? 'selected' : '' }}>
																	Broken
																</option>
																<option value="Discarded" {{ old('inventory_status') == 'Discarded' ? 'selected' : '' }}>
																	Discarded
																</option>
															</select>
															@error('inventory_status')
																<div class="error invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
												</div>
												<div class="tab-pane fade" id="vert-tabs-location" role="tabpanel" aria-labelledby="vert-tabs-location-tab">
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Project</label>
														<div class="col-sm-10">
															<select name="project_id" class="form-control @error('project_id') is-invalid @enderror select2bs4"
																style="width: 100%;">
																@foreach ($projects as $project)
																	<option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
																		{{ $project->project_code }} - {{ $project->project_name }}
																	</option>
																@endforeach
															</select>
															@error('project_id')
																<div class="invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Department</label>
														<div class="col-sm-10">
															<select name="department_id" class="form-control @error('department_id') is-invalid @enderror select2bs4"
																style="width: 100%;">
																@foreach ($departments as $department)
																	<option value="{{ $department->id }}"
																		{{ old('department_id') == $department->id ? 'selected' : '' }}>
																		{{ $department->dept_name }}
																	</option>
																@endforeach
															</select>
															@error('department_id')
																<div class="invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Location</label>
														<div class="col-sm-10">
															<input type="text" class="form-control @error('location') is-invalid @enderror" name="location"
																value="{{ old('location') }}">
															@error('location')
																<div class="error invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
												</div>
												<div class="tab-pane fade" id="vert-tabs-reference" role="tabpanel"
													aria-labelledby="vert-tabs-reference-tab">
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Reference No</label>
														<div class="col-sm-10">
															<input type="text" class="form-control @error('reference_no') is-invalid @enderror" name="reference_no"
																value="{{ old('reference_no') }}">
															@error('reference_no')
																<div class="error invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Reference Date</label>
														<div class="col-sm-10">
															<input type="date" class="form-control @error('reference_date') is-invalid @enderror"
																name="reference_date" value="{{ old('reference_date') }}">
															@error('reference_date')
																<div class="error invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">PO No</label>
														<div class="col-sm-10">
															<input type="text" class="form-control @error('po_no') is-invalid @enderror" name="po_no"
																value="{{ old('po_no') }}">
															@error('po_no')
																<div class="error invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-2 col-form-label">Remarks</label>
														<div class="col-sm-10">
															<textarea class="form-control @error('remarks') is-invalid @enderror" rows="3"
                name="remarks">{{ old('remarks') }}</textarea>
															@error('remarks')
																<div class="error invalid-feedback">
																	{{ $message }}
																</div>
															@enderror
														</div>
													</div>
												</div>
												<div class="tab-pane fade" id="vert-tabs-specification" role="tabpanel"
													aria-labelledby="vert-tabs-specification-tab">
													<div class="table-responsive">
														<table class="table table-striped table-hover" id="dynamicAddRemove">
															<thead>
																<tr>
																	<th style="vertical-align: middle">Component</th>
																	<th style="vertical-align: middle">Description</th>
																	<th style="vertical-align: middle">Remarks</th>
																	<th style="width: 40px"><button type="button" id="dynamic-ar" class="btn btn-outline-primary"><i
																				class="fas fa-plus"></i></button></th>
																</tr>
															</thead>
															{{-- <tbody>
																<tr>
																	<td>
																		<select name="component_id[]" class="form-control select2bs4" style="width: 100%;">
																			<option value="">-- Select Component --</option>
																			<?php foreach($components as $component):
																			?>
																			<option value="{{ $component->id }}"
																				{{ old('component_id[]') == $component->id ? 'selected' : '' }}>
																				{{ $component->component_name }}
																			</option>
																			<?php endforeach;?>
																		</select>
																	</td>
																	<td>
																		<input type="text" class="form-control" name="specification[]">
																	</td>
																	<td>
																		<input type="text" class="form-control" name="spec_remarks[]">
																	</td>
																	<td>
																		<button type="button" class="btn btn-outline-danger remove-input-field"><i
																				class="fas fa-trash-alt"></i></button>
																	</td>
																</tr>
															</tbody> --}}
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
									<br>
									<div class="card-footer">
										<button type="submit" class="btn btn-info float-right">Submit</button>
									</div>
							</form>
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
	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('scripts')
	<!-- Select2 -->
	<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
	<script>
	 $(function() {
	  //Initialize Select2 Elements
	  $('.select2').select2()

	  //Initialize Select2 Elements
	  $('.select2bs4').select2({
	   theme: 'bootstrap4'
	  })

	  $(document).on('select2:open', () => {
	   document.querySelector('.select2-search__field').focus();
	  })
	 })

	 //  specification
	 $("#dynamic-ar").on('click', function() {
	  addTransmittalDetail();
	 });

	 function addTransmittalDetail() {
	  var tr =
	   `<tr>
			<td>
				<select name="component_id[]" class="form-control select2bs4" style="width: 100%;">
					<?php foreach($components as $component):?>
						<option value="{{ $component->id }}"
							{{ old('component_id[]') == $component->id ? 'selected' : '' }}>
							{{ $component->component_name }}
						</option>
					<?php endforeach;?>
				</select>
			</td>
			<td>
				<input type="text" class="form-control" name="specification[]" required>
			</td>
			<td>
				<input type="text" class="form-control" name="spec_remarks[]" required>
			</td>
			<td>
				<button type="button" class="btn btn-outline-danger remove-input-field"><i class="fas fa-trash-alt"></i></button>
			</td>
		</tr>`;
	  $("#dynamicAddRemove").append(tr);
	 };

	 $(document).on('click', '.remove-input-field', function() {
	  $(this).parents('tr').remove();
	 });
	</script>
@endsection
