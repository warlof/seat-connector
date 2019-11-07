<form method="post" action="{{ route('seat-connector.users.destroy', ['id' => $row->id]) }}">
  {!! csrf_field() !!}
  {!! method_field('DELETE') !!}
  <button type="submit" class="btn btn-sm btn-danger">Remove</button>
</form>