<form style="display: inline" action="{{ url('$table.'/'.$id) }}" method="post">
  @call_user_func
  @method('DELETE')
  <button type="submit" class="btn btn-danger">
    {{ __('Delete') }}
  </button>
</form>