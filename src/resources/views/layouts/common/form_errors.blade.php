@if ($errors->any())
<div class="row">
  <div class="col s12">
    <div class="card-panel red lighten-4">
      <ul>
        @foreach ($errors->all() as $error)
          <li class="red-text text-darken-4">
            {{ $error }}
          </li>
        @endforeach
    </div>
  </div>
</div>
@endif