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
                    <a class="btn btn-warning" href="{{ url('/') }}"><i class="fas fa-undo-alt"></i>
                      Back</a>
                  </li>
                </ul>
              </div>
            </div><!-- /.card-header -->
            <div class="card-body">
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
                          <label class="form-control-label">Log Name</label>
                          <input type="text" class="form-control" name="log_name" id="log_name" value="{{ request('log_name') }}">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class="form-control-label">Description</label>
                          <input type="text" class="form-control" name="description" id="description" value="{{ request('description') }}">
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class="form-control-label">Properties</label>
                          <input type="text" class="form-control" name="properties" id="properties" value="{{ request('properties') }}">
                          </select>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label class="form-control-label">Causer</label>
                          <input type="text" class="form-control" name="name" id="name" value="{{ request('name') }}">
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
                      <th>No</th>
                      <th>Log Date</th>
                      <th>Log Name</th>
                      <th>Description</th>
                      <th>Properties</th>
                      <th>Causer</th>
                      <th class="text-center">Action</th>
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
{{-- <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script> --}}
<!-- Page specific script -->
{{-- <script>
  $(function() {
    $("#example1").DataTable({
      "responsive": true
      , "lengthChange": false
      , "autoWidth": false
      , "buttons": ["copy", "csv", "excel", "print"]
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
      , buttons: ["copy", "csv", "excel"]
      , processing: true
      , serverSide: true
      , ajax: {
        url: "{{ route('dashboard.getLogs') }}"
        , data: function(d) {
          d.date1 = $('#date1').val()
            , d.date2 = $('#date2').val()
            , d.log_name = $('#log_name').val()
            , d.description = $('#description').val()
            , d.properties = $('#properties').val()
            , d.name = $('#name').val()
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
        data: "created_at"
        , name: "created_at"
        , orderable: false
      , }, {
        data: "log_name"
        , name: "log_name"
        , orderable: false
      , }, {
        data: "description"
        , name: "description"
        , orderable: false
      , }, {
        data: "properties"
        , name: "properties"
        , orderable: false
      , }, {
        data: "name"
        , name: "name"
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
        '#date1, #date2, #log_name, #description, #name, #properties'
      )
      .keyup(function() {
        table.draw();
      });
    $('#date1, #date2, #log_name, #description, #name, #properties').change(function() {
      table.draw();
    });
    $('#btn-reset').click(function() {
      $(
          '#date1, #date2, #log_name, #description, #name, #properties'
        )
        .val('');
      $('#date1, #date2, #log_name, #description, #name, #properties').change();
    });
  });

</script>
@endsection
