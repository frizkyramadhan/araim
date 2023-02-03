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
              <div class="tab-content p-0">
                <table id="example1" class="table table-sm table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Log Name</th>
                      <th>Description</th>
                      <th>Properties</th>
                      <th>Causer</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($logs as $log)
                    <tr>
                      <td>{{ $log->log_name }}</td>
                      <td>
                        @if($log->description == 'created')
                        <span class="badge badge-info">{{ $log->description }}</span>
                        @elseif($log->description == 'updated')
                        <span class="badge badge-success">{{ $log->description }}</span>
                        @elseif($log->description == 'deleted')
                        <span class="badge badge-danger">{{ $log->description }}</span>
                        @endif
                      </td>
                      <td>
                        @if($log->description == 'updated')
                        @foreach ($log->properties as $property)
                        Inventory No: <b>{{ $property['inventory_no'] ?? '-' }}</b><br>
                        Employee: {{ $property['employee.fullname'] ?? '-' }}<br>
                        Project: {{ $property['project.project_code'] ?? '-' }}<br>
                        Department: {{ $property['department.dept_name'] ?? '-' }}<br>
                        Asset: {{ $property['asset.asset_name'] ?? '-' }}<br>
                        Quantity: {{ $property['quantity'] ?? '-' }}<br>
                        Inventory Status: {{ $property['inventory_status'] ?? '-' }}<br>
                        Transfer Status: {{ $property['transfer_status'] ?? '-' }}<br><br>
                        @endforeach
                        @else
                        @foreach ($log->properties as $property)
                        Inventory No: <b>{{ $property['inventory_no'] ?? '-' }}</b><br>
                        Employee: {{ $property['employee.fullname'] ?? '-' }}<br>
                        Project: {{ $property['project.project_code'] ?? '-' }}<br>
                        Department: {{ $property['department.dept_name'] ?? '-' }}<br>
                        Asset: {{ $property['asset.asset_name'] ?? '-' }}<br>
                        Quantity: {{ $property['quantity'] ?? '-' }}<br>
                        Inventory Status: {{ $property['inventory_status'] ?? '-' }}<br>
                        Transfer Status: {{ $property['transfer_status'] ?? '-' }}<br>
                        @endforeach
                        @endif
                      </td>
                      <td>{{ $log->name ?? '' }}</td>
                      <td class="text-center"><a title="Detail" class="btn btn-sm btn-icon btn-success" href="{{ url('inventories/' . $log->subject_id) }}" target="blank"><i class="fas fa-info-circle"></i> Detail</a></td>
                    </tr>
                    @endforeach
                  </tbody>
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
<script>
  $(function() {
    $("#example1").DataTable({
      "responsive": true
      , "lengthChange": false
      , "autoWidth": false
      , "buttons": ["copy", "csv", "excel", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });

</script>
@endsection
