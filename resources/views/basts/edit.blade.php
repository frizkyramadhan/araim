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
                    <a class="btn btn-warning" href="{{ url('basts') }}"><i class="fas fa-undo-alt"></i>
                      Back</a>
                  </li>
                </ul>
              </div>
            </div><!-- /.card-header -->

            <form class="form-horizontal" action="{{ url('basts/' . $bast->bast_no) }}" method="POST">
              @method('PATCH')
              @csrf
              <div class="card-body">
                <div class="tab-content p-0">
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">BAST No</label>
                    <div class="col-sm-10">
                      <input type="hidden" class="form-control @error('bast_no') is-invalid @enderror" name="bast_no" placeholder="BAST No." value="{{ $bast->bast_no }}" required readonly>
                      <input type="text" class="form-control @error('bast_reg') is-invalid @enderror" name="bast_reg" placeholder="BAST No." value="{{ $bast->bast_reg }}" required readonly>
                      @error('bast_reg')
                      <div class="error invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Date</label>
                    <div class="col-sm-10">
                      <input type="date" class="form-control @error('bast_date') is-invalid @enderror" name="bast_date" value="{{ old('bast_date', $bast->bast_date) }}">
                      @error('bast_date')
                      <div class="error invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Who Submit</label>
                    <div class="col-sm-10">
                      <select name="bast_submit" class="form-control @error('bast_submit') is-invalid @enderror select2bs4" style="width: 100%;">
                        <option value="">-- Select Employee --</option>
                        @foreach ($submits as $submit)
                        <option value="{{ $submit->id }}" {{ old('bast_submit', $bast->bast_submit) == $submit->id ? 'selected' : '' }}>
                          {{ $submit->fullname }} - {{ $submit->nik }}
                        </option>
                        @endforeach
                      </select>
                      @error('bast_submit')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Who Receive</label>
                    <div class="col-sm-10">
                      <input type="hidden" name="bast_receive" value="{{ $bast->bast_receive }}">
                      <select id="bast_receive" class="form-control @error('bast_receive') is-invalid @enderror select2bs4" style="width: 100%;" disabled>
                        <option value="">-- Select Employee --</option>
                        @foreach ($receives as $receive)
                        <option value="{{ $receive->id }}" {{ old('bast_receive', $bast->bast_receive) == $receive->id ? 'selected' : '' }}>
                          {{ $receive->fullname }} - {{ $receive->nik }}
                        </option>
                        @endforeach
                      </select>
                      @error('bast_receive')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>

                  <div class="card-header">
                    <h3 class="card-title">Handed Over Inventory</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body p-0">
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
                          @foreach ($bast_row as $rows)
                          <tr>
                            <td>
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this item?')" value="deleteRow{{ $rows->bast_id }}" name="deleteRow{{ $rows->bast_id }}"><i class="fas fa-trash-alt"></i></button>
                            </td>
                            <td>{{ $rows->inventory_no }}</td>
                            <td>{{ $rows->asset_name }}</td>
                            <td>{{ $rows->brand_name }}</td>
                            <td>{{ $rows->model_asset }}</td>
                            <td>{{ $rows->serial_no }}</td>
                            <td>{{ $rows->input_date }}</td>
                            <td>{{ $rows->inventory_status }}</td>
                            <td>{{ $rows->transfer_status }}</td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-header">
                    <h3 class="card-title">List of Inventory</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body p-0">
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
                          @foreach ($inventories as $rows)
                          <tr>
                            <td>
                              <input type="checkbox" name="inventory_id[]" id="{{ $rows->id }}" value="{{ $rows->id }}">
                            </td>
                            <td>{{ $rows->inventory_no }}</td>
                            <td>{{ $rows->asset_name }}</td>
                            <td>{{ $rows->brand_name }}</td>
                            <td>{{ $rows->model_asset }}</td>
                            <td>{{ $rows->serial_no }}</td>
                            <td>{{ $rows->input_date }}</td>
                            <td>{{ $rows->inventory_status }}</td>
                            <td>{{ $rows->transfer_status }}</td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- /.card-body -->
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

  //  get inventory base on bast_receive
  $('#bast_receive').on('change', function() {
    var bast_receive = $(this).val();
    $.ajax({
      url: "{{ route('basts.getInventories') }}"
      , type: "GET"
      , data: {
        employee_id: bast_receive
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
