@include('layouts.partials.header')

<body class="hold-transition register-page pace-primary">
  <div class="register-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="{{ url('/register') }}" class="h1"><b>{{ $subtitle }}</b></a>
      </div>
      <div class="card-body">
        <form action="{{ url('register') }}" method="post">
          @csrf
          <div class="input-group mb-3">
            <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Full name" name="name" value="{{ old('name') }}">
            @error('name')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
            @enderror
          </div>
          <div class="input-group mb-3">
            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}">
            @error('email')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
            @enderror
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password">
            @error('password')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
            @enderror
          </div>
          <div class="input-group mb-3">
            <select name="project_id" class="form-control @error('project_id') is-invalid @enderror select2bs4" style="width: 100%;">
              <option value="">Select Project</option>
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
          <input type="hidden" class="form-control" name="level" value="user">
          <input type="hidden" class="form-control" name="user_status" value="0">
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <div class="mt-5 text-muted text-center">
          Already have an account? <a href="{{ url('login') }}"><b>Login!</b></a>
        </div>
      </div>
      <div class="card-footer text-muted text-center">
        Copyright &copy; IT Department 2022
      </div>
      <!-- /.form-box -->
    </div><!-- /.card -->
  </div>
  <!-- /.register-box -->

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
  @endsection

  @include('layouts.partials.scripts')
