@extends('layouts.main')

@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <h2 class="text-center display-4">{{ $subtitle }}</h2>
    <div class="row mb-3">
      <div class="col-md-8 offset-md-2">
        <form action="{{ url()->current() }}" method="get">
          <div class="input-group">
            <input id="search" name="search" type="search" class="form-control form-control-lg" placeholder="Type your Inventory Number or Serial Number here" value="{{ request('search') }}" autofocus>
            <div class="input-group-append">
              <button type="submit" class="btn btn-lg btn-default">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    @if ($request->search)
    @if ($trackings->count() > 0)
    @foreach ($trackings as $tracking)
    <div id="accordion{{ $tracking->id }}">
      <div class="row">
        <div class="col-md-10 offset-md-1 mb-3">
          <div class="list-group">
            <div class="list-group-item">
              <div class="row">
                <div class="col px-2">
                  <div data-toggle="collapse" href="#collapseOne{{ $tracking->id }}">
                    <div class="row">
                      <div class="col-md-10">
                        <h4>{{ $tracking->inventory_no }} - {{ $tracking->asset_name }}</h4>
                        <p class="mb-0">{{ $tracking->nik }} - {{ $tracking->fullname }} |
                          {{ $tracking->p_code_emp }} - {{ $tracking->p_name_emp }}</p>
                        <p class="mb-0">{{ $tracking->brand_name }} - {{ $tracking->model_asset }} |
                          {{ $tracking->serial_no }} - {{ $tracking->part_no }} | {{ $tracking->remarks }} | Qty :
                          {{ $tracking->quantity }}</p>
                      </div>
                      <div class="col-md-2">
                        <div>{{ date('d-M-Y', strtotime($tracking->input_date)) }}</div>
                        <div>
                          @if ($tracking->inventory_status == 'Good')
                          <span class="badge badge-primary">{{ $tracking->inventory_status }}</span>
                          @elseif ($tracking->inventory_status == 'Broken')
                          <span class="badge badge-danger">{{ $tracking->inventory_status }}</span>
                          @endif
                        </div>
                        <div>
                          @if ($tracking->transfer_status == 'Available')
                          <span class="badge badge-success">{{ $tracking->transfer_status }}</span>
                          @elseif ($tracking->transfer_status == 'Mutated')
                          <span class="badge badge-warning">{{ $tracking->transfer_status }}</span>
                          @elseif ($tracking->transfer_status == 'Discarded')
                          <span class="badge badge-danger">{{ $tracking->transfer_status }}</span>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="float-right text-sm text-danger">Click to see the detail</div>
                    <div class="text-sm text-danger">&nbsp;</div>
                  </div>
                  <div id="collapseOne{{ $tracking->id }}" class="collapse" data-parent="#accordion{{ $tracking->id }}">
                    <div>
                      <hr>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="table-responsive">
                            <table class="table table-sm table-borderless" width="100%">
                              <tr>
                                <th colspan="3" class="bg-warning">Asset Location</th>
                              </tr>
                              <tr>
                                <td width="30%">Project</td>
                                <td width="5%">:</td>
                                <td>{{ $tracking->p_code_asset }} - {{ $tracking->p_name_asset }}</td>
                              </tr>
                              <tr>
                                <td>Department</td>
                                <td>:</td>
                                <td>{{ $tracking->dept_name }}</td>
                              </tr>
                              <tr>
                                <td>Location</td>
                                <td>:</td>
                                <td>{{ $tracking->location_name }}</td>
                              </tr>
                            </table>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="table-responsive">
                            <table class="table table-sm table-borderless" width="100%">
                              <tr>
                                <th colspan="3" class="bg-danger">References</th>
                              </tr>
                              <tr>
                                <td width="30%">Reference No</td>
                                <td width="5%">:</td>
                                <td>{{ $tracking->reference_no }}</td>
                              </tr>
                              <tr>
                                <td>Reference Date</td>
                                <td>:</td>
                                <td>{{ $tracking->reference_date }}</td>
                              </tr>
                              <tr>
                                <td>PO No</td>
                                <td>:</td>
                                <td>{{ $tracking->po_no }}</td>
                              </tr>
                            </table>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="table-responsive">
                            @php
                            $results = DB::select("SELECT specifications.*, components.component_name FROM specifications JOIN components ON specifications.component_id = components.id WHERE inventory_id = '$tracking->id' ORDER BY id ASC");
                            @endphp
                            <table class="table table-sm table-borderless" width="100%">
                              <tr>
                                <th colspan="4" class="bg-info">Specification</th>
                              </tr>
                              <tr>
                                <th>Component</th>
                                <th>Description</th>
                                <th>Remarks</th>
                                <th>Status</th>
                              </tr>
                              @foreach ($results as $spec)
                              <tr>
                                <td>{{ $spec->component_name }}</td>
                                <td>{{ $spec->specification }}</td>
                                <td>{{ $spec->spec_remarks }}</td>
                                <td>
                                  @if ($spec->spec_status == 'Available')
                                  <span class="badge badge-success">{{ $spec->spec_status }}</span>
                                  @elseif ($spec->spec_status == 'Mutated')
                                  <span class="badge badge-warning">{{ $spec->spec_status }}</span>
                                  @elseif ($spec->spec_status == 'Discarded')
                                  <span class="badge badge-danger">{{ $spec->spec_status }}</span>
                                  @endif
                                </td>
                              </tr>
                              @endforeach
                            </table>
                          </div>
                        </div>
                        @if ($tracking->transfer_status == 'Available')
                        <div class="card-footer col-md-12">
                          <a href="{{ url('inventories/' . $tracking->id . '/edit') }}" class="btn btn-primary"><i class="fas fa-pen-square"></i> Edit</a>
                          <a href="{{ url('inventories/transfer/' . $tracking->id) }}" class="btn btn-danger float-right"><i class="fas fa-exchange-alt"></i> Transfer</a>
                        </div>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
    @else
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <blockquote class="quote-danger">
          <h1>No Data Available</h1>
          Please try another <cite>inventory number</cite> or <cite>serial number</cite>
        </blockquote>
      </div>
    </div>
    @endif
    @endif
  </div>
</section>
<!-- /.content -->
@endsection
