@php($title='ログイン')
@extends('layouts.main')

@section('content')
  <div class="container">
    <h2 class="center-align">{{ $title }}</h2>
    @include('layouts.common.form_errors')

    <div class="row">
      <form class="col s12" action="/login" method="post">
        @csrf
        
        <div class="row">
          <div class="input-field col s12">
            <input id="email" type="email" name="email" class="validate" required value="{{ old('email') }}">
            <label for="email">メールアドレス</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <input id="password" type="password"  name="password" class="validate" required value="{{ old('password') }}">
            <label for="password">パスワード</label>
            <p>パスワードをお忘れの方は<a href="/forgot_password">こちら</a></p>
          </div>
        </div>
        <!-- ログイン情報を記憶するチェックボックス -->
        <div class="row">
          <p class="col s12">
            <label>
              <input type="checkbox" id="remember" name="remember" />
              <span>ログイン情報を記憶する</span>
            </label>
          </p>
        </div>
        <div class="row">
          <div class="col s12 center-align">
            <button class="btn waves-effect waves-light" type="submit" name="action">ログイン
              <i class="material-icons right">send</i>
            </button>
          </div>
        </div>
        <div class="row">
          <div class="col s12 center-align">
            <p>アカウントをお持ちでない方は<a href="/register">こちら</a></p>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection