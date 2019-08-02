@switch($row->level)
  @case('debug')
  <span class="label label-default">{{ ucfirst($row->level) }}</span>
  @break
  @case('info')
  <span class="label label-info">{{ ucfirst($row->level) }}</span>
  @break
  @case('notice')
  <span class="label label-success">{{ ucfirst($row->level) }}</span>
  @break
  @case('warning')
  <span class="label label-warning">{{ ucfirst($row->level) }}</span>
  @break
  @case('error')
  <span class="label label-danger">{{ ucfirst($row->level) }}</span>
  @break
  @case('critical')
  <span class="label label-danger"><i class="fa fa-free-code-camp"></i> {{ ucfirst($row->level) }}</span>
  @break
  @case('alert')
  <span class="label label-warning"><i class="fa fa-warning"></i> {{ ucfirst($row->level) }}</span>
  @break
  @case('emergency')
  <span class="label label-danger"><i class="fa fa-life-ring"></i> {{ ucfirst($row->level) }}</span>
  @break
@endswitch
