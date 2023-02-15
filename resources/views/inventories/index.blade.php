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
          <div class="card card-dark">
            <div class="card-header">
              <h3 class="card-title">
                <strong>{{ $subtitle }}</strong>
              </h3>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item mr-2">
                    @cannot('user')
                    <a class="btn btn-success" href="{{ url('inventories/import') }}"><i class="fas fa-upload"></i>
                      Import</a>
                    @endcannot
                    <a class="btn btn-warning text-dark" href="{{ url('inventories/create') }}"><i class="fas fa-plus"></i>
                      Add</a>
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
              @elseif (session('error'))
              <div class="alert alert-error alert-dismissible show fade">
                <div class="alert-body">
                  <button class="close" data-dismiss="alert">
                    <span>&times;</span>
                  </button>
                  {{ session('error') }}
                </div>
              </div>
              @endif
              <div class="card card-primary">
                <div class="card-header">
                  <h4 class="card-title w-100">
                    <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                      <i class="fas fa-filter"></i> Filter
                    </a>
                  </h4>
                </div>
                <div id="collapseOne" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                    <div class="row form-group">
                      <div class="col-3">
                        <div class="form-group">
                          <label class=" form-control-label">From</label>
                          <input type="date" class="form-control" name="date1" id="date1" value="{{ request('date1') }}">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class=" form-control-label">To</label>
                          <input type="date" class="form-control" name="date2" id="date2" value="{{ request('date2') }}">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class="form-control-label">Inventory No</label>
                          <input type="text" class="form-control" name="inventory_no" id="inventory_no" value="{{ request('inventory_no') }}">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class="form-control-label">Asset</label>
                          <select name="asset_name" class="form-control select2bs4" id="asset_name" style="width: 100%;">
                            <option value="">- All -</option>
                            @foreach ($assets as $asset => $data)
                            <option value="{{ $data->asset_name }}" {{ request('asset_name') == $data->asset_name ? 'selected' : '' }}>
                              {{ $data->asset_name }}
                            </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class="form-control-label">Brand</label>
                          {{-- <input type="text" class="form-control" name="brand" id="brand" value="{{ request('brand') }}"> --}}
                          <select name="brand_name" class="form-control select2bs4" id="brand_name" style="width: 100%;">
                            <option value="">- All -</option>
                            @foreach ($brands as $brand => $data)
                            <option value="{{ $data->brand_name }}" {{ request('brand_name') == $data->brand_name ? 'selected' : '' }}>
                              {{ $data->brand_name }}
                            </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class="form-control-label">Model</label>
                          <input type="text" class="form-control" name="model_asset" id="model_asset" value="{{ request('model_asset') }}">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class=" form-control-label">Serial No</label>
                          <input type="text" class="form-control" name="serial_no" id="serial_no" value="{{ request('serial_no') }}">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class=" form-control-label">PIC</label>
                          <input type="text" class="form-control" name="fullname" id="fullname" value="{{ request('fullname') }}">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class=" form-control-label">Project Asset</label>
                          <select name="project_code" class="form-control select2bs4" id="project_code" style="width: 100%;">
                            <option value="">- All -</option>
                            @foreach ($projects as $project => $data)
                            <option value="{{ $data->project_code }}" {{ request('project_code') == $data->project_code ? 'selected' : '' }}>
                              {{ $data->project_code }} - {{ $data->project_name }}
                            </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class=" form-control-label">Department</label>
                          <select name="dept_name" class="form-control select2bs4" id="dept_name" style="width: 100%;">
                            <option value="">- All -</option>
                            @foreach ($departments as $department => $data)
                            <option value="{{ $data->dept_name }}" {{ request('dept_name') == $data->dept_name ? 'selected' : '' }}>
                              {{ $data->dept_name }}
                            </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class=" form-control-label">Inventory Status</label>
                          <select name="inventory_status" class="form-control" id="inventory_status">
                            <option value="">- All -</option>
                            <option value="Good">Good</option>
                            <option value="Broken">Broken</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class=" form-control-label">Transfer Status</label>
                          <select name="transfer_status" class="form-control" id="transfer_status">
                            <option value="">- All -</option>
                            <option value="Available">Available</option>
                            <option value="Mutated">Mutated</option>
                            <option value="Discarded">Discarded</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class=" form-control-label">&nbsp;</label>
                          <button id="btn-reset" type="button" class="btn btn-danger btn-block">Reset</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table id="example1" width="100%" class="table table-sm table-bordered table-striped">
                  <thead>
                    <tr>
                      <th class="align-middle text-center">No</th>
                      <th class="align-middle">Inventory No</th>
                      <th class="align-middle">Date</th>
                      <th class="align-middle">Asset</th>
                      <th class="align-middle">Brand</th>
                      <th class="align-middle">Model</th>
                      <th class="align-middle">S/N</th>
                      <th class="align-middle">PIC</th>
                      <th class="text-center">Project Asset</th>
                      <th class="align-middle text-center">Location</th>
                      <th class="align-middle text-center">Qty</th>
                      <th class="align-middle text-center">Inventory Status</th>
                      <th class="align-middle text-center">Transfer Status</th>
                      <th class="align-middle text-center">Action</th>
                    </tr>
                  </thead>
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

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
{{-- <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script> --}}
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- Page specific script -->
{{-- <script>
	 	$(function() {
	  		$("#example1").DataTable({
	   	"responsive": true,
			"lengthChange": false,
			"autoWidth": false,
			"buttons": ["copy", "csv", "excel", "pdf", "print"]
	  	}).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
	});
	</script> --}}
<script>
  $(function() {
    var table = $("#example1").DataTable({
      responsive: true
      , autoWidth: true
      , lengthChange: true
      , lengthMenu: [
        [10, 25, 50, 100, -1]
        , ['10', '25', '50', '100', 'Show all']
      ]
      , dom: 'lBrtpi'
      , buttons: ["copy", "csv", "excel", "print", "colvis"]
      , processing: true
      , serverSide: true
      , ajax: {
        url: "{{ route('inventories.getInventories') }}"
        , data: function(d) {
          d.date1 = $('#date1').val()
            , d.date2 = $('#date2').val()
            , d.inventory_no = $('#inventory_no').val()
            , d.asset_name = $('#asset_name').val()
            , d.brand_name = $('#brand_name').val()
            , d.model_asset = $('#model_asset').val()
            , d.serial_no = $('#serial_no').val()
            , d.fullname = $('#fullname').val()
            , d.project_code = $('#project_code').val()
            , d.dept_name = $('#dept_name').val()
            , d.inventory_status = $('#inventory_status').val()
            , d.transfer_status = $('#transfer_status').val()
            , d.search = $("input[type=search][aria-controls=example1]").val()
          console.log(d);
        }
      }
      , columns: [{
        data: 'DT_RowIndex'
        , orderable: false
        , searchable: false
        , className: 'text-center'
      }, {
        data: "inventory_no"
        , name: "inventory_no"
        , orderable: false
      , }, {
        data: "input_date"
        , name: "input_date"
        , orderable: false
      , }, {
        data: "asset_name"
        , name: "asset_name"
        , orderable: false
      , }, {
        data: "brand_name"
        , name: "brand_name"
        , orderable: false
      , }, {
        data: "model_asset"
        , name: "model_asset"
        , orderable: false
      , }, {
        data: "serial_no"
        , name: "serial_no"
        , orderable: false
      , }, {
        data: "fullname"
        , name: "fullname"
        , orderable: false
      , }, {
        data: "project_code"
        , name: "project_code"
        , orderable: false
        , className: 'text-center'
      }, {
        data: "location"
        , name: "location"
        , orderable: false
      , }, {
        data: "quantity"
        , name: "quantity"
        , orderable: false
        , className: 'text-center'
      }, {
        data: "inventory_status"
        , name: "inventory_status"
        , className: "text-center"
        , orderable: false
      , }, {
        data: "transfer_status"
        , name: "transfer_status"
        , className: "text-center"
        , orderable: false
      , }, {
        data: "action"
        , name: "action"
        , orderable: false
        , searchable: false
        , className: "text-center"
      }]
      , fixedColumns: true
    , })
    $(
        '#date1, #date2, #asset_name, #project_code, #inventory_no, #brand_name, #model_asset, #serial_no, #fullname, #dept_name, #inventory_status, #transfer_status'
      )
      .keyup(function() {
        table.draw();
      });
    $('#date1, #date2, #asset_name, #brand_name, #project_code, #dept_name, #inventory_status, #transfer_status').change(function() {
      table.draw();
    });
    $('#btn-reset').click(function() {
      $(
          '#date1, #date2, #asset_name, #project_code, #inventory_no, #brand_name, #model_asset, #serial_no, #fullname, #dept_name, #inventory_status, #transfer_status'
        )
        .val('');
      $('#date1, #date2, #asset_name, #project_code, #dept_name, #brand_name, #inventory_status, #transfer_status').change();
    });
  });

</script>

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

</script>
@endsection
