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
          <div class="card card-danger">
            <div class="card-header">
              <h3 class="card-title">
                <strong>{{ $subtitle }}</strong>
              </h3>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item mr-2">
                    <a class="btn btn-primary" href="{{ url('inventories/' . $inventory->id . '/edit') }}"><i class="fas fa-pen-square"></i>
                      Edit</a>
                    <a class="btn btn-warning text-dark" href="{{ url('inventories/' . $inventory->id) }}"><i class="fas fa-undo-alt"></i>
                      Back</a>
                  </li>
                </ul>
              </div>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="card card-secondary">
                    <div class="card-header">
                      <h3 class="card-title">New Data</h3>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                      </div>
                    </div>
                    <form class="form-horizontal" action="{{ url('inventories/transferProcess/' . $inventory->id) }}" method="POST">
                      @method('PATCH')
                      @csrf
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="table-responsive">
                              <table width=100% class="table table-borderless">
                                <tr>
                                  <td style="vertical-align: middle">PIC</td>
                                  <td style="width: 5%; vertical-align: middle">:</td>
                                  <td>
                                    <select name="employee_id" class="form-control @error('employee_id') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="1">
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
                                  </td>
                                </tr>
                                <tr>
                                  <td style="vertical-align: middle">Date</td>
                                  <td style="width: 5%; vertical-align: middle">:</td>
                                  <td>
                                    <input type="date" class="form-control @error('input_date') is-invalid @enderror" name="input_date" value="{{ old('input_date') }}" tabindex="2">
                                    @error('input_date')
                                    <div class="error invalid-feedback">
                                      {{ $message }}
                                    </div>
                                    @enderror
                                  </td>
                                </tr>
                                <tr>
                                  <td style="vertical-align: middle">Remarks</td>
                                  <td style="vertical-align: middle; width: 5%">:</td>
                                  <td>
                                    <textarea class="form-control @error('remarks') is-invalid @enderror" rows="3" name="remarks" tabindex="3">{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                    <div class="error invalid-feedback">
                                      {{ $message }}
                                    </div>
                                    @enderror
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="table-responsive">
                              <table width=100% class="table table-borderless">
                                <tr>
                                  <td style="vertical-align: middle">Project</td>
                                  <td style="width: 5%; vertical-align: middle">:</td>
                                  <td>
                                    <select name="project_id" class="form-control @error('project_id') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="4">
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
                                  </td>
                                </tr>
                                <tr>
                                  <td style="vertical-align: middle">Department</td>
                                  <td style="width: 5%; vertical-align: middle">:</td>
                                  <td>
                                    <select name="department_id" class="form-control @error('department_id') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="5">
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
                                  </td>
                                </tr>
                                <tr>
                                  <td style="vertical-align: middle">Location</td>
                                  <td style="vertical-align: middle; width: 5%">:</td>
                                  <td>
                                    {{-- <input type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location') }}" tabindex="6"> --}}
                                    <select name="location_id" class="form-control @error('location_id') is-invalid @enderror select2bs4" style="width: 100%;" tabindex="5">
                                      @foreach ($locations as $location)
                                      <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->location_name }}
                                      </option>
                                      @endforeach
                                    </select>
                                    @error('location_id')
                                    <div class="error invalid-feedback">
                                      {{ $message }}
                                    </div>
                                    @enderror
                                  </td>
                                </tr>
                                <tr>
                                  <td style="vertical-align: middle">Quantity</td>
                                  <td style="vertical-align: middle; width: 5%">:</td>
                                  <td>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity', $inventory->quantity) }}" min="1" max="{{ $inventory->quantity }}" tabindex="7" required>
                                    @error('quantity')
                                    <div class="error invalid-feedback">
                                      {{ $message }}
                                    </div>
                                    @enderror
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </div>
                        </div>

                        <input type="hidden" name="inventory_no" value="{{ $inventory->inventory_no }}">
                        <input type="hidden" name="asset_id" value="{{ $inventory->asset_id }}">
                        <input type="hidden" name="brand" value="{{ $inventory->brand }}">
                        <input type="hidden" name="brand_id" value="{{ $inventory->brand_id }}">
                        <input type="hidden" name="model_asset" value="{{ $inventory->model_asset }}">
                        <input type="hidden" name="serial_no" value="{{ $inventory->serial_no }}">
                        <input type="hidden" name="part_no" value="{{ $inventory->part_no }}">
                        <input type="hidden" name="inventory_status" value="{{ $inventory->inventory_status }}">
                        <input type="hidden" name="reference_no" value="{{ $inventory->reference_no }}">
                        <input type="hidden" name="reference_date" value="{{ $inventory->reference_date }}">
                        <input type="hidden" name="po_no" value="{{ $inventory->po_no }}">
                        @foreach ($specifications as $spec)
                        <input type="hidden" name="component_id[{{ $loop->iteration }}]" value="{{ $spec->component_id }}">
                        <input type="hidden" name="specification[{{ $loop->iteration }}]" value="{{ $spec->specification }}">
                        <input type="hidden" name="spec_remarks[{{ $loop->iteration }}]" value="{{ $spec->spec_remarks }}">
                        <input type="hidden" name="spec_status[{{ $loop->iteration }}]" value="{{ $spec->spec_status }}">
                        @endforeach
                      </div>
                      <div class="card-footer">
                        <button type="submit" class="btn btn-info float-right" tabindex="7" onclick="return confirm('Are you sure want to transfer this inventory?')">Submit</button>
                      </div>
                    </form>
                  </div>
                  <!-- /.card -->
                </div>
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
                          <td>PIC</td>
                          <td style="width: 5%">:</td>
                          <td><b>{{ $inventory->employee->fullname }}</b> - <b>{{ $inventory->employee->nik }}</b></td>
                        </tr>
                        <tr>
                          <td>Date</td>
                          <td style="width: 5%">:</td>
                          <td><b>{{ $inventory->input_date }}</b></td>
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
                              @endif
                            </b></td>
                        </tr>
                        <tr>
                          <td>Transfer Status</td>
                          <td style="width: 5%">:</td>
                          <td><b>
                              @if ($inventory->transfer_status == 'Available')
                              <span class="badge badge-success">Available</span>
                              @elseif ($inventory->transfer_status == 'Mutated')
                              <span class="badge badge-warning">Mutated</span>
                              @elseif ($inventory->transfer_status == 'Discarded')
                              <span class="badge badge-secondary">Discarded</span>
                              @endif
                            </b></td>
                        </tr>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
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
                          <td><b>{{ $inventory->project->project_code ?? '-' }}</b> -
                            <b>{{ $inventory->project->project_name ?? '-' }}</b>
                          </td>
                        </tr>
                        <tr>
                          <td>Department</td>
                          <td style="width: 5%">:</td>
                          <td><b>{{ $inventory->department->dept_name ?? '-' }}</b></td>
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
                              <th style="vertical-align: middle">Status</th>
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
                              <td>
                                @if ($spec->spec_status == 'Available')
                                <span class="badge badge-success">{{ $spec->spec_status }}</span>
                                @elseif ($spec->spec_status == 'Discarded')
                                <span class="badge badge-secondary">{{ $spec->spec_status }}</span>
                                @elseif ($spec->spec_status == 'Mutated')
                                <span class="badge badge-warning">{{ $spec->spec_status }}</span>
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
  });

</script>
@endsection
