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
											<a class="btn btn-warning" href="{{ url('projects') }}"><i class="fas fa-undo-alt"></i>
												Back</a>
										</li>
									</ul>
								</div>
							</div><!-- /.card-header -->

							<form class="form-horizontal" action="{{ url('projects') }}" method="POST">
								@csrf
								<div class="card-body">
									<div class="tab-content p-0">
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Project Code</label>
											<div class="col-sm-10">
												<input type="text" class="form-control @error('project_code') is-invalid @enderror" name="project_code"
													value="{{ old('project_code') }}" placeholder="Project Code">
												@error('project_code')
													<div class="error invalid-feedback">
														{{ $message }}
													</div>
												@enderror
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Project Name</label>
											<div class="col-sm-10">
												<input type="text" class="form-control @error('project_name') is-invalid @enderror" name="project_name"
													value="{{ old('project_name') }}" placeholder="Project Code">
												@error('project_name')
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
