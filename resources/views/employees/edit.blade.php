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
											<a class="btn btn-warning" href="{{ url('employees') }}"><i class="fas fa-undo-alt"></i>
												Back</a>
										</li>
									</ul>
								</div>
							</div><!-- /.card-header -->

							<form class="form-horizontal" action="{{ url('employees/' . $employee->id) }}" method="POST">
								@method('PATCH')
								@csrf
								<div class="card-body">
									<div class="tab-content p-0">
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">NIK</label>
											<div class="col-sm-10">
												<input type="text" class="form-control @error('nik') is-invalid @enderror" name="nik"
													value="{{ old('nik', $employee->nik) }}" placeholder="NIK">
												@error('nik')
													<div class="error invalid-feedback">
														{{ $message }}
													</div>
												@enderror
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Full Name</label>
											<div class="col-sm-10">
												<input type="text" class="form-control @error('fullname') is-invalid @enderror" name="fullname"
													value="{{ old('fullname', $employee->fullname) }}" placeholder="Full Name">
												@error('fullname')
													<div class="error invalid-feedback">
														{{ $message }}
													</div>
												@enderror
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Position</label>
											<div class="col-sm-10">
												<select name="position_id" class="form-control @error('position_id') is-invalid @enderror">
													@foreach ($positions as $position)
														<option value="{{ $position->id }}"
															{{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
															{{ $position->position_name }}
														</option>
													@endforeach
												</select>
												@error('position_id')
													<div class="invalid-feedback">
														{{ $message }}
													</div>
												@enderror
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Project</label>
											<div class="col-sm-10">
												<select name="project_id" class="form-control @error('project_id') is-invalid @enderror">
													@foreach ($projects as $project)
														<option value="{{ $project->id }}"
															{{ old('project_id', $employee->project_id) == $project->id ? 'selected' : '' }}>
															{{ $project->project_name }}
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
											<label class="col-sm-2 col-form-label">Email</label>
											<div class="col-sm-10">
												<input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
													value="{{ old('email', $employee->email) }}" placeholder="Email">
												@error('email')
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
