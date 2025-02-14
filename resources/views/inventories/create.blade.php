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
                    <a class="btn btn-warning text-dark" href="{{ url('inventories') }}"><i class="fas fa-undo-alt"></i>
                      Back</a>
                    @else
                    <a class="btn btn-warning text-dark" href="{{ url('employees/'. $employee_id) }}"><i class="fas fa-undo-alt"></i>
                      Back</a>
                    @endif
                  </li>
                </ul>
              </div>
            </div><!-- /.card-header -->

            <form class="form-horizontal" action="{{ url('inventories') }}" method="POST">
              @csrf
              <input type="hidden" name="id_employee" value="{{ $employee_id }}">
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
                            <input type="text" class="form-control @error('inventory_no') is-invalid @enderror" name="inventory_no" value={{ $inv_no }} readonly>
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
                              <option value="{{ $employee->id }}" {{ old('employee_id', $employee_id) == $employee->id ? 'selected' : '' }}>
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
                            <input type="date" class="form-control @error('input_date') is-invalid @enderror" name="input_date" value="{{ old('input_date') }}" tabindex="2">
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
                              <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
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
                            {{-- <input type="text" class="form-control @error('brand') is-invalid @enderror" name="brand" value="{{ old('brand') }}" tabindex="4"> --}}
                            <select id="brand_id" name="brand_id" class="form-control @error('brand_id') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="4">
                              @foreach ($brands as $brand)
                              <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->brand_name }}
                              </option>
                              @endforeach
                              <option value="newBrand">- Define New -</option>
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
                            <input type="text" class="form-control @error('model_asset') is-invalid @enderror" name="model_asset" value="{{ old('model_asset') }}" tabindex="5">
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
                            <input type="text" class="form-control @error('serial_no') is-invalid @enderror" name="serial_no" value="{{ old('serial_no') }}" tabindex="6">
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
                            <input type="text" class="form-control @error('part_no') is-invalid @enderror" name="part_no" value="{{ old('part_no') }}" tabindex="7">
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
                            <input type="text" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity') }}" tabindex="8">
                            @error('quantity')
                            <div class="error invalid-feedback">
                              {{ $message }}
                            </div>
                            @enderror
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Status</label>
                          <div class="col-sm-9">
                            <select name="inventory_status" class="form-control @error('inventory_status') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="9">
                              <option value="Good" {{ old('inventory_status') == 'Good' ? 'selected' : '' }}>
                                Good
                              </option>
                              <option value="Broken" {{ old('inventory_status') == 'Broken' ? 'selected' : '' }}>
                                Broken
                              </option>
                              <option value="Lost" {{ old('inventory_status') == 'Lost' ? 'selected' : '' }}>
                                Lost
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
                          <label class="col-sm-3 col-form-label">Department</label>
                          <div class="col-sm-9">
                            <select name="department_id" class="form-control @error('department_id') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="11">
                              @foreach ($departments as $department)
                              <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
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
                            {{-- <input type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location') }}" tabindex="12"> --}}
                            <select id="location_id" name="location_id" class="form-control @error('location_id') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="12">
                              @foreach ($locations as $location)
                              <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->location_name }}
                              </option>
                              @endforeach
                              <option value="newLocation">- Define New -</option>
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
                            <input type="text" class="form-control @error('reference_no') is-invalid @enderror" name="reference_no" value="{{ old('reference_no') }}" tabindex="13">
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
                            <input type="date" class="form-control @error('reference_date') is-invalid @enderror" name="reference_date" value="{{ old('reference_date') }}" tabindex="14">
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
                            <input type="text" class="form-control @error('po_no') is-invalid @enderror" name="po_no" value="{{ old('po_no') }}" tabindex="15">
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
                            <textarea class="form-control @error('remarks') is-invalid @enderror" rows="3" name="remarks" tabindex="16">{{ old('remarks') }}</textarea>
                            @error('remarks')
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
                                <th style="width: 40px"><button type="button" id="dynamic-ar" class="btn btn-outline-primary" tabindex="17"><i class="fas fa-plus"></i></button></th>
                              </tr>
                            </thead>
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

<div class="modal fade" id="newBrandModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Brand</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-text="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" action="{{ url('brands/storeFromInventory') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="inputName">Brand</label>
            <input type="text" class="form-control @error('brand_name') is-invalid @enderror" name="brand_name" value="{{ old('brand_name') }}" placeholder="Brand Name">
            @error('brand_name')
            <div class="error invalid-feedback">
              {{ $message }}
            </div>
            @enderror
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="newLocationModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Location</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-text="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" action="{{ url('locations/storeFromInventory') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="inputName">Location</label>
            <input type="text" class="form-control @error('location_name') is-invalid @enderror" name="location_name" value="{{ old('location_name') }}" placeholder="Location Name">
            @error('location_name')
            <div class="error invalid-feedback">
              {{ $message }}
            </div>
            @enderror
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
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

    //brand modal
    $('#brand_id').change(function() {
      var opval = $(this).val();
      if (opval == "newBrand") {
        $('#newBrandModal').modal("show");
      }
    });

    //location modal
    $('#location_id').change(function() {
      var opval = $(this).val();
      if (opval == "newLocation") {
        $('#newLocationModal').modal("show");
      }
    });
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
				<button type="button" class="btn btn-outline-danger remove-input-field" tabindex="21"><i class="fas fa-trash-alt"></i></button>
			</td>
		</tr>`;
    $("#dynamicAddRemove").append(tr);
  };

  $(document).on('click', '.remove-input-field', function() {
    $(this).parents('tr').remove();
  });

</script>
@endsection
