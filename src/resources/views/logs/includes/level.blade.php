@switch($row->level)
  @case('debug')
  <span class="badge badge-default p-2 d-block">
    <i class="fas fa-bug"></i> {{ ucfirst($row->level) }}
  </span>
  @break
  @case('info')
  <span class="badge badge-info p-2 d-block">
    <i class="fas fa-info"></i> {{ ucfirst($row->level) }}
  </span>
  @break
  @case('notice')
  <span class="badge badge-success p-2 d-block">
    <i class="fas fa-question-circle"></i> {{ ucfirst($row->level) }}
  </span>
  @break
  @case('warning')
  <span class="badge badge-warning p-2 d-block">
    <i class="fas fa-exclamation"></i> {{ ucfirst($row->level) }}
  </span>
  @break
  @case('error')
  <span class="badge badge-danger p-2 d-block">
    <i class="fas fa-exclamation-circle"></i> {{ ucfirst($row->level) }}
  </span>
  @break
  @case('critical')
  <span class="badge bg-maroon p-2 d-block">
    <i class="fab fa-free-code-camp"></i> {{ ucfirst($row->level) }}
  </span>
  @break
  @case('alert')
  <span class="badge bg-orange p-2 d-block">
    <i class="fas fa-exclamation-triangle"></i> {{ ucfirst($row->level) }}
  </span>
  @break
  @case('emergency')
  <span class="badge bg-pink p-2 d-block">
    <i class="fas fa-life-ring"></i> {{ ucfirst($row->level) }}
  </span>
  @break
@endswitch
