@extends('layouts.main')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $subtitle }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $total_inv->sum }}</h3>
                            <p>Total Inventories</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        {{-- <a href="{{ url('inventories') }}" class="small-box-footer">More info <i
								class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $good_inv->sum }}</h3>

                            <p>Good Inventories</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check"></i>
                        </div>
                        {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $broken_inv->sum }}</h3>

                            <p>Broken Inventories</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-times"></i>
                        </div>
                        {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $total_emp }}</h3>

                            <p>Total Employees</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-7 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-boxes mr-1"></i>
                                Available Inventory by Assets
                            </h3>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <div class="table-responsive">
                                    <table id="example1" class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Assets</th>
                                                <th class="text-right pr-5">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($asset_sum as $row)
                                                <tr>
                                                    <td><a
                                                            href="{{ url('dashboard/summary/' . $row->id) }}">{{ $row->asset_name }}</a>
                                                    </td>
                                                    <td class="text-right pr-5">{{ $row->asset_sum }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th style="text-align:right">Total:</th>
                                                <th style="text-align:right"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </section>
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-5 connectedSortable">
                    <!-- DONUT CHART -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Available Inventory by Project</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <select onchange="updateChartAsset(this)" class="custom-select form-control-border">
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->project_code }} -
                                            {{ $project->project_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <canvas id="myChart"
                                style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Activity Logs</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <a href="{{ url('dashboard/logs') }}" class="btn btn-block btn-warning"><b>Check Activity
                                        Log!</b></a>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </section>
                <!-- right col -->
            </div>
            <!-- /.row (main row) -->
            @can('admin')
                <!-- IT Equipment Without BAST Section -->
                <div class="row">
                    <section class="col-lg-12">
                        <div class="card card-danger">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    IT Equipment Without BAST
                                </h3>
                            </div>
                            <div class="card-body">
                                <div id="accordionBast">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                <a class="d-block w-100" data-toggle="collapse" href="#collapseBastFilter">
                                                    <i class="fas fa-filter"></i> Filter
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseBastFilter" class="collapse" data-parent="#accordionBast">
                                            <div class="card-body">
                                                <div class="row form-group">
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">From</label>
                                                            <input type="date" class="form-control" name="bast_date1"
                                                                id="bast_date1">
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">To</label>
                                                            <input type="date" class="form-control" name="bast_date2"
                                                                id="bast_date2">
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">Inventory No</label>
                                                            <input type="text" class="form-control"
                                                                name="bast_inventory_no" id="bast_inventory_no">
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">Asset</label>
                                                            <select name="bast_asset_name" class="form-control select2bs4"
                                                                id="bast_asset_name" style="width: 100%;">
                                                                <option value="">- All -</option>
                                                                @foreach ($assets as $asset)
                                                                    <option value="{{ $asset->asset_name }}">
                                                                        {{ $asset->asset_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">Brand</label>
                                                            <select name="bast_brand_name" class="form-control select2bs4"
                                                                id="bast_brand_name" style="width: 100%;">
                                                                <option value="">- All -</option>
                                                                @foreach ($brands as $brand)
                                                                    <option value="{{ $brand->brand_name }}">
                                                                        {{ $brand->brand_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">Model</label>
                                                            <input type="text" class="form-control"
                                                                name="bast_model_asset" id="bast_model_asset">
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">Serial No</label>
                                                            <input type="text" class="form-control" name="bast_serial_no"
                                                                id="bast_serial_no">
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">PIC</label>
                                                            <input type="text" class="form-control" name="bast_fullname"
                                                                id="bast_fullname">
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">Project</label>
                                                            <select name="bast_project_code" class="form-control select2bs4"
                                                                id="bast_project_code" style="width: 100%;">
                                                                <option value="">- All -</option>
                                                                @foreach ($projects as $project)
                                                                    <option value="{{ $project->project_code }}">
                                                                        {{ $project->project_code }} -
                                                                        {{ $project->project_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">Department</label>
                                                            <select name="bast_dept_name" class="form-control select2bs4"
                                                                id="bast_dept_name" style="width: 100%;">
                                                                <option value="">- All -</option>
                                                                @foreach ($departments as $department)
                                                                    <option value="{{ $department->dept_name }}">
                                                                        {{ $department->dept_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">&nbsp;</label>
                                                            <button id="btn-reset-bast" type="button"
                                                                class="btn btn-danger btn-block">Reset</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="inventoriesWithoutBastTable"
                                            class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Inventory No</th>
                                                    <th>Date</th>
                                                    <th>Asset</th>
                                                    <th>Brand</th>
                                                    <th>Model</th>
                                                    <th>Serial No</th>
                                                    <th>Employee</th>
                                                    <th>Project</th>
                                                    <th>Department</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                    </section>
                </div>
                <!-- /.row -->
            @endcan
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Page specific script -->

    <!-- Select2 -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements (excluding BAST filters which are initialized after DataTable)
            $('.select2bs4').not('#bast_asset_name, #bast_brand_name, #bast_project_code, #bast_dept_name')
                .select2({
                    theme: 'bootstrap4'
                })

            // Focus on search field when Select2 is opened
            $(document).on('select2:open', function(e) {
                var field = document.querySelector('.select2-search__field');
                if (field) {
                    setTimeout(function() {
                        field.focus();
                    }, 100);
                }
            });
        })
    </script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i ===
                            'number' ? i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(1)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    pageTotal = api
                        .column(1, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer
                    $(api.column(1).footer()).html(pageTotal + ' (' + total + ' total)');
                }
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            @if (auth()->check() && auth()->user()->can('admin'))
                // Initialize IT Equipment Without BAST DataTable
                (function() {
                    if (!$("#inventoriesWithoutBastTable").length) return;

                    // Initialize DataTable
                    const bastTable = $("#inventoriesWithoutBastTable").DataTable({
                        responsive: true,
                        autoWidth: true,
                        lengthChange: true,
                        lengthMenu: [
                            [10, 25, 50, 100, -1],
                            ['10', '25', '50', '100', 'Show all']
                        ],
                        dom: 'lBrtpi',
                        buttons: ["copy", "csv", "excel", "print", "colvis"],
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('dashboard.getInventoriesWithoutBast') }}",
                            data: function(d) {
                                d.date1 = $('#bast_date1').val();
                                d.date2 = $('#bast_date2').val();
                                d.inventory_no = $('#bast_inventory_no').val();
                                d.asset_name = $('#bast_asset_name').val();
                                d.brand_name = $('#bast_brand_name').val();
                                d.model_asset = $('#bast_model_asset').val();
                                d.serial_no = $('#bast_serial_no').val();
                                d.fullname = $('#bast_fullname').val();
                                d.project_code = $('#bast_project_code').val();
                                d.dept_name = $('#bast_dept_name').val();
                            }
                        },
                        columns: [{
                                data: "DT_RowIndex",
                                orderable: false,
                                searchable: false,
                                className: "text-center"
                            },
                            {
                                data: "inventory_no",
                                name: "inventory_no",
                                orderable: false
                            },
                            {
                                data: "input_date",
                                name: "input_date",
                                orderable: false
                            },
                            {
                                data: "asset_name",
                                name: "asset_name",
                                orderable: false
                            },
                            {
                                data: "brand_name",
                                name: "brand_name",
                                orderable: false
                            },
                            {
                                data: "model_asset",
                                name: "model_asset",
                                orderable: false
                            },
                            {
                                data: "serial_no",
                                name: "serial_no",
                                orderable: false
                            },
                            {
                                data: "fullname",
                                name: "fullname",
                                orderable: false
                            },
                            {
                                data: "project_code",
                                name: "project_code",
                                orderable: false
                            },
                            {
                                data: "dept_name",
                                name: "dept_name",
                                orderable: false
                            },
                            {
                                data: "action",
                                name: "action",
                                orderable: false,
                                searchable: false,
                                className: "text-center"
                            }
                        ]
                    });

                    // Append buttons to wrapper
                    bastTable.buttons().container().appendTo(
                        '#inventoriesWithoutBastTable_wrapper .col-md-6:eq(0)');

                    // Initialize Select2 dropdowns
                    const select2Elements = ['#bast_asset_name', '#bast_brand_name', '#bast_project_code',
                        '#bast_dept_name'
                    ];
                    select2Elements.forEach(function(selector) {
                        const $element = $(selector);
                        if (!$element.hasClass('select2-hidden-accessible')) {
                            $element.select2({
                                theme: 'bootstrap4'
                            });
                        }
                    });

                    // Helper function to redraw table
                    const redrawTable = function() {
                        bastTable.draw();
                    };

                    // Attach filter events - Text inputs
                    $('#bast_inventory_no, #bast_model_asset, #bast_serial_no, #bast_fullname')
                        .on('keyup', redrawTable);

                    // Attach filter events - Date inputs
                    $('#bast_date1, #bast_date2')
                        .on('change', redrawTable);

                    // Attach filter events - Select2 dropdowns
                    $(select2Elements.join(', '))
                        .on('select2:select select2:unselect select2:clear', redrawTable);

                    // Reset button handler
                    $('#btn-reset-bast').on('click', function() {
                        // Reset text and date inputs
                        $('#bast_date1, #bast_date2, #bast_inventory_no, #bast_model_asset, #bast_serial_no, #bast_fullname')
                            .val('');

                        // Reset Select2 dropdowns
                        $(select2Elements.join(', ')).val(null).trigger('change');

                        // Redraw table
                        redrawTable();
                    });
                })();
            @endif
        });
    </script>

    <!-- ChartJS -->
    <script src="{{ asset('assets/plugins/chart.js/dist/chart.js') }}"></script>
    <script>
        // setup
        const assetValues = [
            @foreach ($projectAssets as $asset)
                {
                    x: {
                        {{ $asset->id }}: '{{ $asset->asset_name }}'
                    },
                    y: {
                        {{ $asset->id }}: {{ $asset->count }},
                    }
                },
            @endforeach
        ]
        console.log(assetValues)

        var backgroundcolor = [];
        var bordercolor = [];
        for (i = 0; i < assetValues.length; i++) {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            backgroundcolor.push('rgba(' + r + ', ' + g + ', ' + b + ', 0.7)');
            bordercolor.push('rgba(' + r + ', ' + g + ', ' + b + ', 1)');
        }

        const data = {
            datasets: [{
                label: 'Inventory by Project',
                data: assetValues,
                backgroundColor: backgroundcolor,
                borderColor: bordercolor,
                borderWidth: 1,
                parsing: {
                    xAxisKey: 'x.1',
                    yAxisKey: 'y.1'
                }
            }]
        };

        // config
        const config = {
            type: 'bar',
            data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // render init block
        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );

        function updateChartAsset(option) {
            myChart.data.datasets[0].parsing.xAxisKey = `x.${option.value}`;
            myChart.data.datasets[0].parsing.yAxisKey = `y.${option.value}`;
            myChart.update();
        }
    </script>
@endsection
