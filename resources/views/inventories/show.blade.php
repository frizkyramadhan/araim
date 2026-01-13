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
                                            @if ($inventory->transfer_status != 'Mutated')
                                                <a class="btn btn-danger"
                                                    href="{{ url('inventories/transfer/' . $inventory->id) }}"><i
                                                        class="fas fa-exchange-alt"></i>
                                                    Transfer</a>
                                                @if ($employee_id == null)
                                                    <a class="btn btn-primary"
                                                        href="{{ url('inventories/edit/' . $inventory->id) }}"><i
                                                            class="fas fa-pen-square"></i>
                                                        Edit</a>
                                                @else
                                                    <a class="btn btn-primary"
                                                        href="{{ url('inventories/edit/' . $inventory->id . '/' . $employee_id) }}"><i
                                                            class="fas fa-pen-square"></i>
                                                        Edit</a>
                                                @endif
                                            @endif
                                            @if ($employee_id == null)
                                                <a class="btn btn-warning text-dark" href="{{ url('inventories') }}"><i
                                                        class="fas fa-undo-alt"></i>
                                                    Back</a>
                                            @else
                                                <a class="btn btn-warning text-dark"
                                                    href="{{ url('employees/' . $employee_id) }}"><i
                                                        class="fas fa-undo-alt"></i>
                                                    Back</a>
                                            @endif
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
                                @elseif (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                {{-- @dd($inventory->project) --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <h3 class="card-title">PIC</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                        title="Collapse">
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
                                                        <td><b>{{ $inventory->fullname }}</b> -
                                                            <b>{{ $inventory->nik }}</b>
                                                        </td>
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
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                        title="Collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <table width=100% class="table table-borderless">
                                                    <tr>
                                                        <td style="width: 20%">Asset</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $inventory->asset_name }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Brand</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $inventory->brand_name ?? '-' }}</b></td>
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
                                                                @elseif ($inventory->inventory_status == 'Lost')
                                                                    <span class="badge badge-dark">Lost</span>
                                                                @endif
                                                            </b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 20%">Transfer Status</td>
                                                        <td style="width: 5%">:</td>
                                                        <td>
                                                            <b>
                                                                <b>
                                                                    @if ($inventory->transfer_status == 'Available')
                                                                        <span class="badge badge-success">Available</span>
                                                                    @elseif ($inventory->transfer_status == 'Discarded')
                                                                        <span class="badge badge-secondary">Discarded</span>
                                                                    @elseif ($inventory->transfer_status == 'Mutated')
                                                                        <span class="badge badge-warning">Mutated</span>
                                                                    @endif
                                                                </b>
                                                            </b>
                                                        </td>
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
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                        title="Collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <table width=100% class="table table-borderless">
                                                    <tr>
                                                        <td style="width: 20%">Project</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $inventory->project_code ?? '-' }}</b> -
                                                            <b>{{ $inventory->project_name ?? '-' }}</b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Department</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $inventory->dept_name ?? '-' }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Location</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $inventory->location_name ?? '-' }}</b></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card card-danger">
                                            <div class="card-header">
                                                <h3 class="card-title">References</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool"
                                                        data-card-widget="collapse" title="Collapse">
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
                                                    <tr>
                                                        <td>is active?</td>
                                                        <td style="width: 5%">:</td>
                                                        <td>
                                                            <b>
                                                                @if ($inventory->is_active == 1)
                                                                    <span class="badge badge-success">Yes</span>
                                                                @else
                                                                    <span class="badge badge-danger">No</span>
                                                                @endif
                                                            </b>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <div class="card card-secondary">
                                            <div class="card-header">
                                                <h3 class="card-title">QRCode</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool"
                                                        data-card-widget="collapse" title="Collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body text-center">
                                                <table width=100% class="table table-borderless table-sm"
                                                    style="padding: 0%">
                                                    @if ($inventory->qrcode)
                                                        <tr>
                                                            <td>
                                                                <img width="150px"
                                                                    src="{{ asset('storage/qrcode/' . $inventory->qrcode) }}">
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td>
                                                            @if ($inventory->qrcode)
                                                                <a href="{{ url('inventories/print_qrcode/' . $inventory->id) }}"
                                                                    target="_blank" class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-print"></i> Print
                                                                </a>
                                                                <a href="{{ url('inventories/delete_qrcode/' . $inventory->id) }}"
                                                                    class="btn btn-sm btn-danger"
                                                                    onclick="return confirm('Are you sure to delete this qrcode?')">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </a>
                                                            @elseif (!$inventory->qrcode)
                                                                <a href="{{ url('inventories/qrcode/' . $inventory->id) }}"
                                                                    class="btn btn-sm btn-secondary"><i
                                                                        class="fas fa-qrcode"></i> Generate</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">Specification</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool"
                                                        data-card-widget="collapse" title="Collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table width=100% class="table table-striped table-hover"
                                                        id="dynamicAddRemove">
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
                                                                    <td>{{ $spec->component->component_name }}</td>
                                                                    <td>{{ $spec->specification }}</td>
                                                                    <td>{{ $spec->spec_remarks }}</td>
                                                                    <td>
                                                                        @if ($spec->spec_status == 'Available')
                                                                            <span
                                                                                class="badge badge-success">{{ $spec->spec_status }}</span>
                                                                        @elseif ($spec->spec_status == 'Discarded')
                                                                            <span
                                                                                class="badge badge-secondary">{{ $spec->spec_status }}</span>
                                                                        @elseif ($spec->spec_status == 'Mutated')
                                                                            <span
                                                                                class="badge badge-warning">{{ $spec->spec_status }}</span>
                                                                        @elseif ($spec->spec_status == 'Broken')
                                                                            <span
                                                                                class="badge badge-danger">{{ $spec->spec_status }}</span>
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
                                    <div class="col-md-6">
                                        <div class="card card-light">
                                            <div class="card-header">
                                                <h3 class="card-title">Repair History from IT WO</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool"
                                                        data-card-widget="collapse" title="Collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive" id="repair-history">

                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    @can('admin')
                                        <div class="col-6">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h4 class="card-title">Document BAST</h4>
                                                </div>
                                                <div class="card-body">
                                                    @if ($basts->count() > 0)
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No BAST</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($basts as $bast)
                                                                        <tr>
                                                                            <td><b>{{ $bast->bast_reg }}</b></td>
                                                                            <td>
                                                                                <a href="{{ url('basts/' . $bast->bast_no) }}"
                                                                                    class="btn btn-sm btn-info"
                                                                                    target="_blank">
                                                                                    <i class="fas fa-eye"></i> Detail
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @else
                                                        <div class="text-center">
                                                            <p class="text-muted">Tidak ada dokumen BAST untuk inventory
                                                                ini.
                                                            </p>
                                                            <a href="{{ url('basts/create?inventory_id=' . $inventory->id) }}"
                                                                class="btn btn-primary">
                                                                <i class="fas fa-plus"></i> Add BAST
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card card-warning">
                                                <div class="card-header">
                                                    <h4 class="card-title">Document BAPB</h4>
                                                </div>
                                                <div class="card-body">
                                                    @if ($bapbs->count() > 0)
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No BAPB</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($bapbs as $bapb)
                                                                        <tr>
                                                                            <td><b>{{ $bapb->bapb_reg }}</b></td>
                                                                            <td>
                                                                                <a href="{{ url('bapbs/' . $bapb->bapb_no) }}"
                                                                                    class="btn btn-sm btn-info"
                                                                                    target="_blank">
                                                                                    <i class="fas fa-eye"></i> Detail
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @else
                                                        <div class="text-center">
                                                            <p class="text-muted">Tidak ada dokumen BAPB untuk inventory
                                                                ini.
                                                            </p>
                                                            <a href="{{ url('bapbs/create?inventory_id=' . $inventory->id) }}"
                                                                class="btn btn-primary">
                                                                <i class="fas fa-plus"></i> Add BAPB
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                    <div class="col-12">
                                        <div class="card card-dark">
                                            <div class="card-header">
                                                <h4 class="card-title">Images</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="container">
                                                    <div class="row justify-content-between">
                                                        <div class="col-6">
                                                            <div class="row">
                                                                <form class="form-horizontal"
                                                                    action="{{ url('inventories/addImages/' . $inventory->id) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <div>
                                                                                <input type="file" name="filename[]"
                                                                                    multiple>
                                                                            </div>
                                                                            <div class="input-group-append">
                                                                                <button type="submit"
                                                                                    class="btn btn-sm btn-primary">Submit</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 text-right">
                                                            <a href="{{ url('inventories/deleteImages/' . $inventory->inventory_no) }}"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Are you sure you want to delete all images?');"><i
                                                                    class="fas fa-trash"></i> Delete All</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    @foreach ($images as $image)
                                                        <div class="col-sm-2 text-center">
                                                            <a href="{{ asset('images/' . $image->inventory_no . '/' . $image->filename) }}"
                                                                data-toggle="lightbox"
                                                                data-title="{{ $image->filename }}"
                                                                data-gallery="gallery">
                                                                <img src="{{ asset('images/' . $image->inventory_no . '/' . $image->filename) }}"
                                                                    class="img-fluid mb-2"
                                                                    alt="{{ $image->filename }}" />
                                                            </a>
                                                            <a href="{{ url('inventories/deleteImage/' . $image->id) }}"
                                                                class="btn btn-danger btn-sm mb-2"
                                                                onclick="return confirm('Are you sure you want to delete this image?');"><i
                                                                    class="fas fa-trash"></i> Delete</a>
                                                        </div>
                                                    @endforeach
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

@section('scripts')
    <!-- Ekko Lightbox -->
    <script src="{{ asset('assets/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
    <script>
        $(function() {
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                    alwaysShowClose: true
                });
            });
        })
    </script>

    <script>
        $(document).ready(function() {
            $('#repair-history').html('');
            var id = `{{ $inventory->id }}`;
            $.ajax({
                url: 'http://192.168.32.37/arka-rest-server/api/repairv2',
                type: 'get',
                datatype: 'json',
                data: {
                    'arka-key': 'arka123',
                    'id': id
                },
                success: function(result) {
                    if (result.status == true) {
                        let repair = result.data;
                        console.log(repair);
                        var trHTML = "";
                        trHTML +=
                            `<div class="table-responsive">
              <table width=100% class="table table-striped table-hover">
                <tr>
                  <th>Date</th>
                  <th>Component</th>
                  <th>Damage</th>
                  <th>Cost</th>
                </tr>`;
                        $.each(repair, function(i, data) {
                            trHTML +=
                                `<tr>
                <td>` + data.date_repair + `</td>
                <td>` + data.component_name + ` ` + data.specification + `</td>
                <td>` + data.damage + `</td>
                <td><div align="right">` + data.cost + `</div></td>
              </tr>`;
                        });

                        // get sum of cost damage across all objects in array
                        var total = repair.reduce(function(prev, cur) {
                            return prev + parseInt(cur.cost);
                        }, 0);
                        trHTML += `<tr>
                      <td colspan="3"><div align="right"><b>Total</b></div></td>
                      <td><div align="right"><b>` + total + `</b></td>
					          </tr>`;
                        trHTML += `</table></div>`;
                        $('#repair-history').append(trHTML);
                    } else {
                        $('#repair-history').html(`
				<div>
					<h4 class="text-center">` + result.message + `</h4>
				</div>`);
                    }
                }
            });
        });
    </script>
@endsection
