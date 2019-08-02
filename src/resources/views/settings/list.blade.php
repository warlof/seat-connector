@extends('web::layouts.grids.12')

@section('title', trans_choice('seat-connector::seat.settings', 0))
@section('page_header', trans_choice('seat-connector::seat.settings', 0))

@section('full')

  @foreach($drivers->split(abs($drivers->count() / 3)) as $row)

    @foreach($row as $key => $driver)

      <div class="col-md-3">

        <div class="box box-solid">
          <div class="box-header with-border">
            <i class="fa {{ $driver->icon }}"></i>
            <h4 class="box-title">{{ ucfirst($driver->name) }}</h4>
          </div>
          <div class="box-body">
            <form role="form" method="post" id="{{ sprintf('seat-connector-%s', $key) }}">
              {{ csrf_field() }}

              @foreach($driver->settings as $field)
                <div class="form-group">
                  <label for="{{ sprintf('seat-connector-%s-%s', $key, $field->name) }}">{{ trans($field->label) }}</label>
                  <input type="text" name="{{ sprintf('seat-connector-%s-%s', $key, $field->name) }}" id="{{ sprintf('seat-connector-%s-%s', $key, $field->name) }}" class="form-control" />
                </div>
              @endforeach
            </form>
          </div>
          <div class="box-footer">
            <button type="submit" form="{{ sprintf('seat-connector-%s', $key) }}" class="btn btn-success pull-right">{{ trans('seat-connector::seat.post') }}</button>
          </div>
        </div>

      </div>

    @endforeach

  @endforeach

@stop