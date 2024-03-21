<!DOCTYPE html>
<html>
  @include('layouts.common.head')

  <body>
    @include('layouts.common.header')

    @yield('content')

    @include('layouts.common.scripts')
    
    @yield('scripts')
  </body>
</html>