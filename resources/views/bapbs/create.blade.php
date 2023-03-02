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
                    <a class="btn btn-warning" href="{{ url('bapbs') }}"><i class="fas fa-undo-alt"></i>
                      Back</a>
                  </li>
                </ul>
              </div>
            </div><!-- /.card-header -->

            <form class="form-horizontal" action="{{ url('bapbs') }}" method="POST">
              @csrf
              <div class="card-body">
                <div class="tab-content p-0">
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">BAPB No</label>
                    <div class="col-sm-10">
                      <input type="hidden" class="form-control @error('bapb_no') is-invalid @enderror" name="bapb_no" placeholder="BAPB No." value="{{ $bapb_no }}" required readonly>
                      <input type="text" class="form-control @error('bapb_reg') is-invalid @enderror" name="bapb_reg" placeholder="BAPB No." value="{{ $bapb_no }}/BAPB/ITY/{{ $month }}/{{ $year }}" required readonly>
                      @error('bapb_reg')
                      <div class="error invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Date</label>
                    <div class="col-sm-10">
                      <input type="date" class="form-control @error('bapb_date') is-invalid @enderror" name="bapb_date" value="{{ old('bapb_date') }}">
                      @error('bapb_date')
                      <div class="error invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Who Submit</label>
                    <div class="col-sm-10">
                      <select name="bapb_submit" class="form-control @error('bapb_submit') is-invalid @enderror select2bs4" style="width: 100%;">
                        <option value="">-- Select Employee --</option>
                        @foreach ($submits as $submit)
                        <option value="{{ $submit->id }}" {{ old('bapb_submit') == $submit->id ? 'selected' : '' }}>
                          {{ $submit->fullname }} - {{ $submit->nik }}
                        </option>
                        @endforeach
                      </select>
                      @error('bapb_submit')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Who Receive</label>
                    <div class="col-sm-10">
                      <select id="bapb_receive" name="bapb_receive" class="form-control @error('bapb_receive') is-invalid @enderror select2bs4" style="width: 100%;">
                        <option value="">-- Select Employee --</option>
                        @foreach ($receives as $receive)
                        <option value="{{ $receive->id }}" {{ old('bapb_receive') == $receive->id ? 'selected' : '' }}>
                          {{ $receive->fullname }} - {{ $receive->nik }}
                        </option>
                        @endforeach
                      </select>
                      @error('bapb_receive')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>

                  <div class="card-header">
                    <h3 class="card-title">List of Inventory</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body p-0">
                    @if (session('error'))
                    <div class="alert alert-error alert-dismissible show fade">
                      <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                          <span>&times;</span>
                        </button>
                        {{ session('error') }}
                      </div>
                    </div>
                    @endif
                    <div class="table-responsive">
                      <table id="inventories" class="table table-sm table-striped">
                        <thead>
                          <tr>
                            <th class="align-middle" style="width: 10px">#</th>
                            <th class="align-middle">Inventory No</th>
                            <th class="align-middle">Asset</th>
                            <th class="align-middle">Brand</th>
                            <th class="align-middle">Model</th>
                            <th class="align-middle">S/N</th>
                            <th class="align-middle">Input Date</th>
                            <th class="align-middle">Inventory Status</th>
                            <th class="align-middle">Transfer Status</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Duration</label>
                    <div class="col-sm-10">
                      <input type="text" placeholder="Days" class="form-control @error('duration') is-invalid @enderror" name="duration" value="{{ old('duration') }}">
                      @error('duration')
                      <div class="error invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>
                </div>
              </div><!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" class="btn btn-info">Submit</button>
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

  //  get inventory base on bapb_receive
  $('#bapb_receive').on('change', function() {
    var bapb_receive = $(this).val();
    $.ajax({
      url: "{{ route('bapbs.getInventories') }}"
      , type: "GET"
      , data: {
        employee_id: bapb_receive
      }
      , success: function(inventories) {
        console.log(inventories);
        // clear tbody
        $('#inventories tbody').html('');
        // foreach inventories to inventory
        $.each(inventories, function(index, inventory) {
          var row = '<tr>';
          row += '<td><input type="checkbox" name="inventory_id[]" id="' + inventory.id + '" value="' + inventory.id +
            '"></td>';
          row += '<td>' + inventory.inventory_no + '</td>';
          row += '<td>' + inventory.asset_name + '</td>';
          row += '<td>' + inventory.brand_name + '</td>';
          row += '<td>' + inventory.model_asset + '</td>';
          row += '<td>' + inventory.serial_no + '</td>';
          row += '<td>' + inventory.input_date + '</td>';
          row += '<td>' + inventory.inventory_status + '</td>';
          row += '<td>' + inventory.transfer_status + '</td>';
          row += '</tr>';
          $('#inventories tbody').append(row);
          console.log(row);
        });
      }
    });
  });

</script>
@endsection
