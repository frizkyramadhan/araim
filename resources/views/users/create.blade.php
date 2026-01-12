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
    <form class="form-horizontal" action="{{ url('users') }}" method="POST">
      @csrf
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-8">
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
                      <a class="btn btn-warning" href="{{ url('users') }}"><i class="fas fa-undo-alt"></i>
                        Back</a>
                    </li>
                  </ul>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">User Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="User Name">
                      @error('name')
                      <div class="error invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email">
                      @error('email')
                      <div class="error invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" placeholder="Password">
                      @error('password')
                      <div class="error invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Level</label>
                    <div class="col-sm-10">
                      <select name="level" class="form-control @error('level') is-invalid @enderror select2bs4">
                        <option value="user" {{ old('level') == 'user' ? 'selected' : '' }}>User
                        </option>
                        <option value="superuser" {{ old('level') == 'superuser' ? 'selected' : '' }}>Superuser
                        </option>
                        <option value="admin" {{ old('level') == 'admin' ? 'selected' : '' }}>
                          Administrator</option>
                      </select>
                      @error('level')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Project</label>
                    <div class="col-sm-10">
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
                    <label class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                      <select name="user_status" class="form-control @error('user_status') is-invalid @enderror select2bs4">
                        <option value="1" {{ old('user_status') == '1' ? 'selected' : '' }}>
                          Active</option>
                        <option value="0" {{ old('user_status') == '0' ? 'selected' : '' }}>Inactive
                        </option>
                      </select>
                      @error('user_status')
                      <div class="invalid-feedback">
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
            </div>
          </div>
          <!-- /.card -->
        </section>
        <section class="col-md-4">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Assign Categories</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <!-- Checkbox Pilih Semua -->
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                    <label for="selectAll" class="custom-control-label">
                      Select All
                    </label>
                  </div>
                  <hr>
                  @foreach ($categories as $category)
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="categoryCheckbox{{ $category->id }}" name="categories[]" value="{{ $category->id }}">
                    <label for="categoryCheckbox{{ $category->id }}" class="custom-control-label">
                      {{ $category->category_name }}
                    </label>
                  </div>
                  @endforeach
                  @error('categories')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- right col -->
      </div>
    </form>
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

</script>

<script>
  $(document).ready(function() {
    // Event listener untuk checkbox "Pilih Semua"
    $('#selectAll').on('change', function() {
      $('input[name="categories[]"]').prop('checked', $(this).is(':checked'));
    });

    // Event listener untuk setiap checkbox kategori
    const $categories = $('input[name="categories[]"]');
    $categories.on('change', function() {
      $('#selectAll').prop('checked', $categories.length === $categories.filter(':checked').length);
    });
  });

</script>
@endsection
