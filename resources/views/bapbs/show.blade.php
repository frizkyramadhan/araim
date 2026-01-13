@extends('layouts.main')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

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
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <strong>{{ $subtitle }}</strong>
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item mr-2">
                                            <a class="btn btn-primary"
                                                href="{{ url('bapbs/' . $bapb->bapb_no . '/edit') }}"><i
                                                    class="fas fa-pen-square"></i>
                                                Edit</a>
                                            <a class="btn btn-success"
                                                href="{{ url('bapbs/' . $bapb->bapb_no . '/print') }}"><i
                                                    class="fas fa-print"></i>
                                                Print</a>
                                            <a class="btn btn-warning text-dark" href="{{ url('bapbs') }}"><i
                                                    class="fas fa-undo-alt"></i>
                                                Back</a>
                                        </li>
                                    </ul>
                                </div>
                            </div><!-- /.card-header -->
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
                                    <div class="col-md-4">
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <h3 class="card-title">BAPB Detail</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                        title="Collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <table width=100% class="table table-sm table-borderless">
                                                    <tr>
                                                        <td style="width: 20%">No</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $bapb->bapb_reg }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Date</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $bapb->bapb_date }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Who Submit</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $bapb->submit_name }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>NIK</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $bapb->submit_nik }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Position</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $bapb->submit_pos }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Who Receive</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $bapb->receive_name }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>NIK</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $bapb->receive_nik }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Position</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $bapb->receive_pos }}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Duration</td>
                                                        <td style="width: 5%">:</td>
                                                        <td><b>{{ $bapb->duration }} Days</b></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card card-warning">
                                            <div class="card-header">
                                                <h3 class="card-title">Asset Detail</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                        title="Collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table width=100% class="table table-sm table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="vertical-align: middle">Inventory No</th>
                                                                <th style="vertical-align: middle">Asset</th>
                                                                <th style="vertical-align: middle">Brand</th>
                                                                <th style="vertical-align: middle">Model</th>
                                                                <th style="vertical-align: middle">S/N</th>
                                                                <th style="vertical-align: middle">Date</th>
                                                                <th class="text-center" style="vertical-align: middle">
                                                                    Inventory Status</th>
                                                                <th class="text-center" style="vertical-align: middle">
                                                                    Transfer Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($bapb_row as $row)
                                                                <tr>
                                                                    <td>{{ $row->inventory_no }}</td>
                                                                    <td>{{ $row->asset_name }}</td>
                                                                    <td>{{ $row->brand_name }}</td>
                                                                    <td>{{ $row->model_asset }}</td>
                                                                    <td>{{ $row->serial_no }}</td>
                                                                    <td>{{ $row->input_date }}</td>
                                                                    <td class="text-center">
                                                                        @if ($row->inventory_status == 'Good')
                                                                            <span class="badge badge-primary">Good</span>
                                                                        @elseif ($row->inventory_status == 'Broken')
                                                                            <span class="badge badge-danger">Broken</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if ($row->transfer_status == 'Available')
                                                                            <span
                                                                                class="badge badge-success">Available</span>
                                                                        @elseif ($row->transfer_status == 'Discarded')
                                                                            <span
                                                                                class="badge badge-secondary">Discarded</span>
                                                                        @elseif ($row->transfer_status == 'Mutated')
                                                                            <span class="badge badge-warning">Mutated</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                                <!-- Upload Document and Send Email Row -->
                                <div class="row">
                                    <!-- Upload Document Card -->
                                    <div class="col-md-6">
                                        <div class="card card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">Upload Signed Document</h3>
                                            </div>
                                            <div class="card-body">
                                                <form id="uploadDocumentForm"
                                                    action="{{ url('bapbs/' . $bapb->bapb_no . '/upload-document') }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('POST')
                                                    <div class="form-group">
                                                        <label>Signed Document</label>
                                                        <input type="file"
                                                            class="form-control @error('signed_document') is-invalid @enderror"
                                                            name="signed_document" accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="form-text text-muted">Upload dokumen hasil
                                                            scan BAPB
                                                            yang sudah ditandatangani (PDF, JPG, PNG - Max
                                                            5MB)</small>
                                                        @error('signed_document')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-upload"></i> Upload Document
                                                    </button>
                                                </form>
                                                <br>
                                                @if ($bapb->signed_document)
                                                    <a href="{{ asset('storage/' . $bapb->signed_document) }}"
                                                        target="_blank" class="btn btn-success">
                                                        <i class="fas fa-file-pdf"></i> View Signed Document
                                                    </a>
                                                    <form
                                                        action="{{ url('bapbs/' . $bapb->bapb_no . '/delete-document') }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this document?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash-alt"></i> Delete Document
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->

                                    <!-- Send Email Card -->
                                    <div class="col-md-6">
                                        <div class="card card-success">
                                            <div class="card-header">
                                                <h3 class="card-title">Send Email Notification</h3>
                                            </div>
                                            <div class="card-body">
                                                <form id="sendEmailForm"
                                                    action="{{ url('bapbs/' . $bapb->bapb_no . '/send-email') }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label>To: <span class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('mail_to') is-invalid @enderror"
                                                            name="mail_to"
                                                            value="support.recruitment@arka.co.id, dessy@arka.co.id"
                                                            placeholder="Enter email addresses separated by comma">
                                                        <small class="form-text text-muted">Enter recipient email addresses
                                                            separated by comma</small>
                                                        @error('mail_to')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label>CC:</label>
                                                        <input type="text"
                                                            class="form-control @error('mail_cc') is-invalid @enderror"
                                                            name="mail_cc" value="rachman.yulikiswanto@arka.co.id"
                                                            placeholder="Enter CC email addresses separated by comma">
                                                        <small class="form-text text-muted">Enter CC email addresses
                                                            separated by comma (optional)</small>
                                                        @error('mail_cc')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <button type="submit" id="sendEmailBtn" class="btn btn-success"
                                                        {{ !$bapb->signed_document ? 'disabled' : '' }}>
                                                        <i class="fas fa-envelope"></i> Send Email Notification
                                                    </button>
                                                    @if (!$bapb->signed_document)
                                                        <small class="form-text text-danger d-block mt-2">
                                                            <i class="fas fa-exclamation-triangle"></i> Please
                                                            upload signed document first before sending email.
                                                        </small>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->
                                </div>
                                <!-- /.row -->
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
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endsection

@section('scripts')
    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Check if document exists on page load
            var hasDocument = {{ $bapb->signed_document ? 'true' : 'false' }};

            // If page reloaded with success message and document exists, enable button
            @if (session('success') && $bapb->signed_document)
                $('#sendEmailBtn').prop('disabled', false).removeClass('disabled');
                $('.form-text.text-danger').remove();
            @endif

            // Intercept form submit for email confirmation
            $('#sendEmailForm').on('submit', function(e) {
                e.preventDefault();

                var form = $(this);
                var mailTo = form.find('input[name="mail_to"]').val().trim();
                var mailCc = form.find('input[name="mail_cc"]').val().trim();

                // Parse emails for display
                var toEmails = mailTo ? mailTo.split(',').map(function(email) {
                    return email.trim();
                }).filter(Boolean) : [];
                var ccEmails = mailCc ? mailCc.split(',').map(function(email) {
                    return email.trim();
                }).filter(Boolean) : [];

                // Build confirmation message
                var message = '<div style="text-align: left;">';
                message += '<strong>To:</strong><br>';
                if (toEmails.length > 0) {
                    message += '<ul style="margin: 5px 0; padding-left: 20px;">';
                    toEmails.forEach(function(email) {
                        message += '<li>' + email + '</li>';
                    });
                    message += '</ul>';
                } else {
                    message += '<span style="color: red;">No recipients specified</span><br>';
                }

                if (ccEmails.length > 0) {
                    message += '<strong>CC:</strong><br>';
                    message += '<ul style="margin: 5px 0; padding-left: 20px;">';
                    ccEmails.forEach(function(email) {
                        message += '<li>' + email + '</li>';
                    });
                    message += '</ul>';
                }
                message += '</div>';

                // Show confirmation dialog
                Swal.fire({
                    title: 'Confirm Email Sending',
                    html: message,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-paper-plane"></i> Yes, Send Email',
                    cancelButtonText: '<i class="fas fa-times"></i> Cancel',
                    reverseButtons: true,
                    width: '600px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Sending Email...',
                            html: 'Please wait while we send the email notification.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Remove event handler and submit form
                        form.off('submit');
                        form[0].submit();
                    }
                });
            });
        });
    </script>
@endsection
