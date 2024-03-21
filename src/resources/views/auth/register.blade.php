@php($title='新規登録')
@extends('layouts.main')

@section('content')
  <div class="container">
    <h2 class="center-align">{{ $title }}</h2>
    @include('layouts.common.form_errors')

    <div class="row">
      <form class="col s12" action="/register" method="post">
        @csrf
        
        <div class="row">
          <div class="input-field col s12">
            <input id="name" type="text" name="name" class="validate" required value="{{ old('name') }}">
            <label for="name">ニックネーム</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <input id="email" type="email" name="email" class="validate" required value="{{ old('email') }}">
            <label for="email">メールアドレス</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <input id="password" type="password" name="password" class="validate" required value="{{ old('password') }}">
            <label for="password">パスワード</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <input id="password_confirmation" type="password" name="password_confirmation" class="validate" required value="{{ old('password_confirmation') }}">
            <label for="password_confirmation">パスワード（確認）</label>
          </div>
        </div>
        <div class="row">
          <div class="col s12 center-align">
            <button class="btn waves-effect waves-light" type="submit" name="action">新規登録
              <i class="material-icons right">send</i>
            </button>
          </div>
        </div>
        <div class="row">
          <div class="col s12 center-align">
            <p>アカウントをお持ちの方は<a href="/login">こちら</a></p>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection