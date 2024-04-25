<nav id="header" class="light-blue lighten-1" role="navigation">
  <div class="nav-wrapper container"><a id="logo-container" href="/" class="brand-logo">引っ越しタスクリスト</a>
    <ul class="right hide-on-med-and-down">
      @guest
        <li><a href="/login">ログイン</a></li>
        <li><a href="/login">新規登録</a></li>
      @endguest
      @auth
        <li><a href="/mypage">マイページ</a></li>
        <li><a href="/tasks">タスク管理</a></li>
        <li><a href="/tasks/calendar">カレンダー</a></li>
        <li><a href="/logout">ログアウト</a></li>
      @endauth
    </ul>

    <ul id="nav-mobile" class="sidenav">
      <li><a href="/login">ログイン</a></li>
      <li><a href="/mypage">マイページ</a></li>
      <li><a href="/tasks">タスク管理</a></li>
      <li><a href="/calendar">カレンダー</a></li>
      <li><a href="/logout">ログアウト</a></li>
    </ul>
    <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
  </div>
</nav>