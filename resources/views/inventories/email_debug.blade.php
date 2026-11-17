@extends('layouts.main')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('inventories') }}">Inventories</a></li>
                        <li class="breadcrumb-item active">Email Debug</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <section class="col-lg-12">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Production CC Settings</strong></h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                CC list for automatic emails on inventory <strong>create</strong> and
                                <strong>transfer</strong> (TO remains <code>employees.email</code>).
                                Manage it here — no need to edit <code>.env</code>.
                            </p>
                            <form method="POST" action="{{ route('inventories.emailDebug.saveCc') }}">
                                @csrf
                                <div class="form-group mb-2">
                                    <label for="inventory_mail_cc">CC Emails</label>
                                    <textarea name="inventory_mail_cc" id="inventory_mail_cc" rows="3"
                                        class="form-control @error('inventory_mail_cc') is-invalid @enderror"
                                        placeholder="cc1@arka.co.id, cc2@arka.co.id">{{ old('inventory_mail_cc', $defaultCc) }}</textarea>
                                    <small class="form-text text-muted">
                                        Comma or newline separated. Leave empty to send without CC.
                                        @if ($ccFromSavedSettings)
                                            <span class="text-success">Using saved admin settings.</span>
                                        @else
                                            <span class="text-warning">Showing env/config fallback until you save.</span>
                                        @endif
                                    </small>
                                    @error('inventory_mail_cc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save CC Settings
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title"><strong>{{ $subtitle }}</strong></h3>
                            <div class="card-tools">
                                <a class="btn btn-warning text-dark btn-sm" href="{{ url('inventories') }}">
                                    <i class="fas fa-undo-alt"></i> Back
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">
                                Preview the inventory notification HTML in the browser, or send a test message to a
                                custom address. Test send uses the Custom TO/CC fields below (does not change production
                                CC settings).
                            </p>

                            <form id="emailDebugForm" method="GET" action="{{ route('inventories.emailDebug.preview') }}"
                                target="_blank">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email Type <span class="text-danger">*</span></label>
                                            <select name="type" id="type" class="form-control" required>
                                                <option value="created"
                                                    {{ old('type', request('type')) == 'created' ? 'selected' : '' }}>
                                                    Inventory Created
                                                </option>
                                                <option value="transferred"
                                                    {{ old('type', request('type')) == 'transferred' ? 'selected' : '' }}>
                                                    Inventory Transferred
                                                </option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Inventory (content source) <span class="text-danger">*</span></label>
                                            <select name="inventory_id" id="inventory_id"
                                                class="form-control select2" required style="width: 100%;">
                                                <option value="">-- Select inventory --</option>
                                                @foreach ($inventories as $inv)
                                                    <option value="{{ $inv->id }}"
                                                        {{ (string) old('inventory_id', request('inventory_id')) === (string) $inv->id ? 'selected' : '' }}>
                                                        #{{ $inv->id }} — {{ $inv->inventory_no }}
                                                        @if ($inv->asset)
                                                            ({{ $inv->asset->asset_name }})
                                                        @endif
                                                        @if ($inv->employee)
                                                            — {{ $inv->employee->fullname }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Uses real inventory data for the email
                                                body.</small>
                                        </div>

                                        <div class="form-group" id="fromInventoryGroup" style="display: none;">
                                            <label>From Inventory (optional, for transfer context)</label>
                                            <select name="from_inventory_id" id="from_inventory_id"
                                                class="form-control select2" style="width: 100%;">
                                                <option value="">-- None --</option>
                                                @foreach ($inventories as $inv)
                                                    <option value="{{ $inv->id }}"
                                                        {{ (string) old('from_inventory_id', request('from_inventory_id')) === (string) $inv->id ? 'selected' : '' }}>
                                                        #{{ $inv->id }} — {{ $inv->inventory_no }}
                                                        @if ($inv->employee)
                                                            — {{ $inv->employee->fullname }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Custom TO <span class="text-danger">*</span>
                                                <small class="text-muted">(required for send test)</small></label>
                                            <input type="text" name="mail_to" id="mail_to"
                                                class="form-control @error('mail_to') is-invalid @enderror"
                                                value="{{ old('mail_to') }}"
                                                placeholder="you@arka.co.id, another@arka.co.id">
                                            <small class="form-text text-muted">Comma-separated for multiple
                                                recipients.</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Custom CC <small class="text-muted">(test send only)</small></label>
                                            <input type="text" name="mail_cc" id="mail_cc"
                                                class="form-control @error('mail_cc') is-invalid @enderror"
                                                value="{{ old('mail_cc', $defaultCc) }}"
                                                placeholder="cc1@arka.co.id, cc2@arka.co.id">
                                            <small class="form-text text-muted">Prefills from production CC settings
                                                above; change freely for this test only.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <button type="submit" class="btn btn-info" form="emailDebugForm">
                                        <i class="fas fa-eye"></i> Preview in browser
                                    </button>
                                    <button type="submit" class="btn btn-success" form="emailDebugSendForm"
                                        onclick="return syncSendForm();">
                                        <i class="fas fa-paper-plane"></i> Send test email
                                    </button>
                                </div>
                            </form>

                            <form id="emailDebugSendForm" method="POST"
                                action="{{ route('inventories.emailDebug.send') }}" class="d-none">
                                @csrf
                                <input type="hidden" name="type" id="send_type">
                                <input type="hidden" name="inventory_id" id="send_inventory_id">
                                <input type="hidden" name="from_inventory_id" id="send_from_inventory_id">
                                <input type="hidden" name="mail_to" id="send_mail_to">
                                <input type="hidden" name="mail_cc" id="send_mail_cc">
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        function toggleFromInventory() {
            var type = document.getElementById('type').value;
            document.getElementById('fromInventoryGroup').style.display = (type === 'transferred') ? 'block' : 'none';
        }

        function syncSendForm() {
            var mailTo = document.getElementById('mail_to').value.trim();
            if (!mailTo) {
                alert('Custom TO is required to send a test email.');
                return false;
            }
            if (!document.getElementById('inventory_id').value) {
                alert('Please select an inventory record.');
                return false;
            }

            document.getElementById('send_type').value = document.getElementById('type').value;
            document.getElementById('send_inventory_id').value = document.getElementById('inventory_id').value;
            document.getElementById('send_from_inventory_id').value = document.getElementById('from_inventory_id').value;
            document.getElementById('send_mail_to').value = mailTo;
            document.getElementById('send_mail_cc').value = document.getElementById('mail_cc').value;
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleFromInventory();
            document.getElementById('type').addEventListener('change', toggleFromInventory);

            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('.select2').select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });
            }
        });
    </script>
@endsection
