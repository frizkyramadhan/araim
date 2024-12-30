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
                    @if ($employee_id == null)
                    <a class="btn btn-warning text-dark" href="{{ url('inventories/' . $inventory->id) }}"><i class="fas fa-undo-alt"></i>
                      Back</a>
                    @else
                    <a class="btn btn-warning text-dark" href="{{ url('inventories/' . $inventory->id.'/'.$employee->id) }}"><i class="fas fa-undo-alt"></i>
                      Back</a>
                    @endif
                  </li>
                </ul>
              </div>
            </div><!-- /.card-header -->

            <form class="form-horizontal" action="{{ url('inventories/' . $inventory->id) }}" method="POST">
              @method('PATCH')
              @csrf
              <input type="hidden" name="id_employee" value="{{ $employee_id }}">
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
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">No</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control @error('inventory_no') is-invalid @enderror" name="inventory_no" value="{{ $inventory->inventory_no }}" readonly>
                            @error('inventory_no')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">PIC</label>
                          <div class="col-sm-9">
                            @if ($employee_id == null)
                            <select name="employee_id" class="form-control @error('employee_id') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="1">
                              @foreach ($employees as $employee)
                              <option value="{{ $employee->id }}" {{ old('employee_id', $inventory->employee_id) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->fullname }} - {{ $employee->nik }}
                              </option>
                              @endforeach
                            </select>
                            @else
                            <input type="text" class="form-control @error('employee_id') is-invalid @enderror" value="{{ $employee->fullname }}" readonly>
                            <input type="hidden" class="form-control @error('employee_id') is-invalid @enderror" value="{{ $employee->id }}" name="employee_id" readonly>
                            @endif
                            @error('employee_id')
                            <div class="invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Date</label>
                          <div class="col-sm-9">
                            <input type="date" class="form-control @error('input_date') is-invalid @enderror" name="input_date" value="{{ old('input_date', $inventory->input_date) }}" tabindex="2">
                            @error('input_date')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
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
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Asset</label>
                          <div class="col-sm-9">
                            <select name="asset_id" class="form-control @error('asset_id') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="3">
                              @foreach ($assets as $asset)
                              <option value="{{ $asset->id }}" {{ old('asset_id', $inventory->asset_id) == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_name }} - {{ $asset->category_name }}
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
                          <label class="col-sm-3 col-form-label">Brand</label>
                          <div class="col-sm-9">
                            {{-- <input type="text" class="form-control @error('brand') is-invalid @enderror" name="brand" value="{{ old('brand', $inventory->brand) }}" tabindex="4"> --}}
                            <select name="brand_id" class="form-control @error('brand_id') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="4">
                              @foreach ($brands as $brand)
                              <option value="{{ $brand->id }}" {{ old('brand_id', $inventory->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->brand_name }}
                              </option>
                              @endforeach
                            </select>
                            @error('brand_id')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Model</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control @error('model_asset') is-invalid @enderror" name="model_asset" value="{{ old('model_asset', $inventory->model_asset) }}" tabindex="5">
                            @error('model_asset')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Serial No</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control @error('serial_no') is-invalid @enderror" name="serial_no" value="{{ old('serial_no', $inventory->serial_no) }}" tabindex="6">
                            @error('serial_no')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Part No</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control @error('part_no') is-invalid @enderror" name="part_no" value="{{ old('part_no', $inventory->part_no) }}" tabindex="7">
                            @error('part_no')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Quantity</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity', $inventory->quantity) }}" tabindex="8">
                            @error('quantity')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
                        {{-- @can('admin') --}}
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Status</label>
                          <div class="col-sm-9">
                            <select name="inventory_status" class="form-control @error('inventory_status') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="9">
                              <option value="Good" {{ old('inventory_status', $inventory->inventory_status) == 'Good' ? 'selected' : '' }}>
                                Good
                              </option>
                              <option value="Broken" {{ old('inventory_status', $inventory->inventory_status) == 'Broken' ? 'selected' : '' }}>
                                Broken
                              </option>
                            </select>
                            @error('inventory_status')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
                        {{-- @endcan --}}
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
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Project</label>
                          <div class="col-sm-9">
                            <select name="project_id" class="form-control @error('project_id') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="10">
                              @foreach ($projects as $project)
                              <option value="{{ $project->id }}" {{ old('project_id', $inventory->project_id) == $project->id ? 'selected' : '' }}>
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
                          <label class="col-sm-3 col-form-label">Department</label>
                          <div class="col-sm-9">
                            <select name="department_id" class="form-control @error('department_id') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="11">
                              @foreach ($departments as $department)
                              <option value="{{ $department->id }}" {{ old('department_id', $inventory->department_id) == $department->id ? 'selected' : '' }}>
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
                          <label class="col-sm-3 col-form-label">Location</label>
                          <div class="col-sm-9">
                            {{-- <input type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location', $inventory->location) }}" tabindex="12"> --}}
                            <select name="location_id" class="form-control @error('location_id') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="12">
                              @foreach ($locations as $location)
                              <option value="{{ $location->id }}" {{ old('location_id', $inventory->location_id) == $location->id ? 'selected' : '' }}>
                                {{ $location->location_name }}
                              </option>
                              @endforeach
                            </select>
                            @error('location_id')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
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
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Ref No</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control @error('reference_no') is-invalid @enderror" name="reference_no" value="{{ old('reference_no', $inventory->reference_no) }}" tabindex="13">
                            @error('reference_no')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Ref Date</label>
                          <div class="col-sm-9">
                            <input type="date" class="form-control @error('reference_date') is-invalid @enderror" name="reference_date" value="{{ old('reference_date', $inventory->reference_date) }}" tabindex="14">
                            @error('reference_date')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">PO No</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control @error('po_no') is-invalid @enderror" name="po_no" value="{{ old('po_no', $inventory->po_no) }}" tabindex="15">
                            @error('po_no')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Remarks</label>
                          <div class="col-sm-9">
                            <textarea class="form-control @error('remarks') is-invalid @enderror" rows="3" name="remarks" tabindex="16">{{ old('remarks', $inventory->remarks) }}</textarea>
                            @error('remarks')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">is active?</label>
                          <div class="col-sm-9">
                            <select name="is_active" class="form-control @error('is_active') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="17">
                              <option value="1" {{ old('is_active', $inventory->is_active) == '1' ? 'selected' : '' }}>
                                Yes
                              </option>
                              <option value="0" {{ old('is_active', $inventory->is_active) == '0' ? 'selected' : '' }}>
                                No
                              </option>
                            </select>
                            @error('is_active')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
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
                          <table class="table table-striped table-hover" id="dynamicAddRemove">
                            <thead>
                              <tr>
                                <th style="vertical-align: middle">Component</th>
                                <th style="vertical-align: middle">Description</th>
                                <th style="vertical-align: middle">Remarks</th>
                                <th style="vertical-align: middle">Status</th>
                                <th style="width: 40px"><button type="button" id="dynamic-ar" class="btn btn-outline-primary" tabindex="18"><i class="fas fa-plus"></i></button></th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($specifications as $spec)
                              <tr>
                                <td>{{ $spec->component->component_name }}</td>
                                <td>{{ $spec->specification }}</td>
                                <td>{{ $spec->spec_remarks }}</td>
                                <td>@if ($spec->spec_status == 'Available')
                                  <span class="badge badge-success">{{ $spec->spec_status }}</span>
                                  @elseif ($spec->spec_status == 'Discarded')
                                  <span class="badge badge-secondary">{{ $spec->spec_status }}</span>
                                  @elseif ($spec->spec_status == 'Mutated')
                                  <span class="badge badge-warning">{{ $spec->spec_status }}</span>
                                  @elseif ($spec->spec_status == 'Broken')
                                  <span class="badge badge-danger">{{ $spec->spec_status }}</span>
                                  @endif</td>
                                <td>
                                  <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure to delete this record?')" value="deleteRow{{ $spec->id }}" name="deleteRow{{ $spec->id }}"><i class="fas fa-trash-alt"></i></button>
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
                <div class="card-footer">
                  <button type="submit" class="btn btn-info float-right" tabindex="22">Submit</button>
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
				<select name="component_id[]" class="form-control select2bs4" style="width: 100%;" tabindex="18">
					<?php foreach($components as $component):?>
						<option value="{{ $component->id }}"
							{{ old('component_id[]') == $component->id ? 'selected' : '' }}>
							{{ $component->component_name }}
						</option>
					<?php endforeach;?>
				</select>
			</td>
			<td>
				<input type="text" class="form-control" name="specification[]" required tabindex="19">
			</td>
			<td>
				<input type="text" class="form-control" name="spec_remarks[]" required tabindex="20">
			</td>
			<td>
				<input type="text" class="form-control" name="spec_status[]" required tabindex="21">
			</td>
			<td>
				<button type="button" class="btn btn-outline-danger remove-input-field" tabindex="22"><i class="fas fa-trash-alt"></i></button>
			</td>
		</tr>`;
    $("#dynamicAddRemove").append(tr);
  };

  $(document).on('click', '.remove-input-field', function() {
    $(this).parents('tr').remove();
  });

</script>
@endsection
