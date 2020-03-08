@extends('web::layouts.grids.12')

@section('title', trans_choice('seat-connector::seat.settings', 0))
@section('page_header', trans_choice('seat-connector::seat.settings', 0))

@section('full')

  <div class="row">

    <div class="col-md-3">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Common</h3>
        </div>
        <div class="panel-body">
          <form method="post" id="seat-connector-setup">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="security-level">Security Level</label>
              <select id="security-level" name="security-level" class="form-control">
                @if(setting('seat-connector.strict', true))
                  <option value="strict" selected>Strict</option>
                @else
                  <option value="strict">Strict</option>
                @endif
                @if(setting('seat-connector.strict', true))
                    <option value="normal">Normal</option>
                @else
                  <option value="normal" selected>Normal</option>
                @endif
              </select>
            </div>
            <div class="form-group">
              <label for="use-ticker">Use ticker ?</label>
              <div class="radio">
                <label>
                  @if(setting('seat-connector.ticker', true))
                    <input type="radio" id="use-ticker" name="use-ticker" value="0" /> No
                  @else
                    <input type="radio" id="use-ticker" name="use-ticker" value="0" checked /> No
                  @endif
                </label>
                <label>
                  @if(setting('seat-connector.ticker', true))
                  <input type="radio" id="use-ticker" name="use-ticker" value="1" checked /> Yes
                  @else
                  <input type="radio" id="use-ticker" name="use-ticker" value="1" /> Yes
                  @endif
                </label>
              </div>
            </div>
            <div class="form-group">
              <label for="prefix-format">Prefix mask</label>
              @if(setting('seat-connector.format', true) == '')
                <input type="text" id="prefix-format" name="prefix-format" class="form-control" value="[%2$s] %1$s" />
              @else
                <input type="text" id="prefix-format" name="prefix-format" class="form-control" value="{{ setting('seat-connector.format', true) }}" />
              @endif
            </div>
          </form>
        </div>
        <div class="panel-footer clearfix">
          <button type="submit" class="btn btn-success pull-right" form="seat-connector-setup">{{ trans('seat-connector::seat.save') }}</button>
        </div>
      </div>

    </div>

    <div class="col-md-9">

      <div class="panel panel-default">

        <div class="panel-heading">
          <h3 class="panel-title">Management</h3>
        </div>

        <div class="panel-body">

          <div class="col-md-6 no-padding">

            <div class="row">

              <div class="col-md-6">
                <button type="button" role="button" id="seat-connector-sets" class="btn btn-success btn-block btn-flat">Update Sets</button>
                <span class="help-block">This will update known Sets from selected Drivers.</span>
              </div>

              <div class="col-md-6">
                <button type="button" role="button" id="seat-connector-terminator" class="btn btn-danger btn-block btn-flat">Reset Everybody</button>
                <span class="help-block">This will remove all Set from every Users on selected Drivers.</span>
              </div>

            </div>

            <div class="row">

              <div class="col-md-6">
                <button type="button" role="button" id="seat-connector-policy" class="btn btn-success btn-block btn-flat">Sync. Users</button>
                <span class="help-block">This will apply Users policy to selected Drivers.</span>
              </div>

              <div class="col-md-6">
                <select id="driver-selector" class="form-control">
                  <option value="">All Drivers</option>
                  @foreach($drivers as $key => $driver)
                  <option value="{{ $key }}">{{ ucfirst($driver->name) }}</option>
                  @endforeach
                </select>
                <span class="help-block">This will determine for which driver action have to be queue.</span>
              </div>

            </div>

          </div>

          <div class="col-md-6">

            <dl class="dl-horizontal">
              <dt>Security Level</dt>
              <dd>Determine how policy must be applied. Using <code>strict</code> will require user have all their linked character with a valid ESI access. Otherwise, they will loose all their access.</dd>

              <dt>Use Ticker</dt>
              <dd>If yes is selected, the Character Corporation Ticker will be added to the name according to <code>prefix mask</code>.</dd>

              <dt>Prefix Mask</dt>
              <dd>This is the pattern which will define how ticker must be put. <code>%s</code> is a Joker. They will be replaced in the following order : <i>ticker</i>, <i>character name</i>.</dd>
            </dl>

          </div>

        </div>

      </div>

    </div>

  </div>

  @foreach($drivers->split(abs($drivers->count() / 3)) as $row)

    <div class="row">

      @foreach($row as $key => $driver)

        <div class="col-md-3">

          <div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa {{ $driver->icon }}"></i>
              <h4 class="box-title">{{ ucfirst($driver->name) }}</h4>
            </div>
            <div class="box-body">
              <form role="form" method="post" id="{{ sprintf('seat-connector-%s', $key) }}" action="{{ route(sprintf('seat-connector.drivers.%s.settings', $key)) }}">
                {{ csrf_field() }}

                @foreach($driver->settings as $field)
                  <div class="form-group">
                    <label for="{{ sprintf('seat-connector-%s-%s', $key, $field->name) }}">{{ trans($field->label) }}</label>
                    <input type="text" name="{{ $field->name }}"
                           id="{{ sprintf('seat-connector-%s-%s', $key, $field->name) }}" class="form-control"
                           value="{{ $field->value }}" />
                  </div>
                @endforeach
              </form>
            </div>
            <div class="box-footer">
              <button type="submit" form="{{ sprintf('seat-connector-%s', $key) }}" class="btn btn-success pull-right">{{ trans('seat-connector::seat.save') }}</button>
            </div>
          </div>

        </div>

      @endforeach

    </div>

  @endforeach

@stop

@push('javascript')
  <script>
    $('#seat-connector-sets, #seat-connector-policy, #seat-connector-terminator').on('click', function (e) {
        var command = '';
        var driver  = $('#driver-selector').val();

        switch (e.target.id) {
            case 'seat-connector-sets':
                command = 'seat-connector:sync:sets';
                break;
            case 'seat-connector-policy':
                command = 'seat-connector:apply:policies';
                break;
            case 'seat-connector-terminator':
                command = 'seat-connector:apply:policies --terminator';
                break;
        }

        $.ajax({
            'method': 'post',
            'url': '{{ route('seat-connector.settings.command') }}',
            'data': {
                'driver': driver,
                'command': command
            },
        });
    });
  </script>
@endpush
