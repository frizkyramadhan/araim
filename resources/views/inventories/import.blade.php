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
											<a class="btn btn-warning text-dark" href="{{ url('inventories') }}"><i class="fas fa-undo-alt"></i>
												Back</a>
										</li>
									</ul>
								</div>
							</div><!-- /.card-header -->

							<form class="form-horizontal" action="{{ url('inventories/importProcess') }}" enctype="multipart/form-data"
								method="POST">
								@csrf
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
									@endif
									<div class="row">
										<div class="col-md-12">
											<div class="card card-success">
												<div class="card-header">
													<h3 class="card-title">Import File</h3>
													<div class="card-tools">
														<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
															<i class="fas fa-minus"></i>
														</button>
													</div>
												</div>
												<div class="card-body">
													<div class="form-group row">
														<label class="col-sm-3 col-form-label">Example</label>
														<div class="col-sm-9">
															<a class="btn btn-success" href="{{ url('inventories/export') }}"><i class="fas fa-file-excel"></i>
																Download</a>
														</div>
													</div>
													<div class="form-group row">
														<label class="col-sm-3 col-form-label">Import
														</label>
														<div class="col-sm-9">
															<div class="input-group">
																<div class="custom-file">
																	<input type="file" id="exampleInputFile" name="import_file">
																</div>
															</div>
														</div>
													</div>
												</div>
												<!-- /.card-body -->
											</div>
											<!-- /.card -->
										</div>
									</div>
									<div class="card-footer">
										<button type="submit" class="btn btn-info float-right" tabindex="22">Submit</button>
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
