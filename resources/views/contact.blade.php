@extends('layouts.main')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Contact us</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
          <li class="breadcrumb-item active">Contact us</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-body row">
      <div class="col-5 text-center d-flex align-items-center justify-content-center">
        <div class="">
          <h2><strong>IT</strong> Department</h2>
          <p class="lead mb-5">PT. Arkananta Apta Pratista<br>
            Jl. MT. Haryono No 131-133, Graha Indah<br>
            Balikpapan Utara, Balikpapan
          </p>
        </div>
      </div>
      <div class="col-7">
        <div class="form-group">
          <label for="inputName">Eko</label>
          <div class="col-5">Ext. 210 - Balikpapan</div>
        </div>
        <div class="form-group">
          <label for="inputEmail">Hendik</label>
          <div class="col-5">Ext. 152 - Balikpapan</div>
        </div>
        <div class="form-group">
          <label for="inputSubject">Yanto</label>
          <div class="col-5">Ext. 151 - Balikpapan</div>
        </div>
        <div class="form-group">
          <label for="inputMessage">Frizky / Rilla</label>
          <div class="col-5">Ext. 155 - Balikpapan</div>
        </div>
        <div class="form-group">
          <label for="inputMessage">Erick</label>
          <div class="col-5">Ext. 105 - Jakarta</div>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->
@endsection
