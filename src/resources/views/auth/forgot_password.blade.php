@php($title='パスワード再設定')
@extends('layouts.main')

@section('content')
  <div class="container">
    <h2 class="center-align">{{ $title }}</h2>
    @include('layouts.common.form_errors')

    <div class="row">
      <form class="col s12" action="/forgot_password" method="post">
        @csrf
        
        <div class="row">
          <div class="input-field col s12">
            <input id="email" type="email" name="email" class="validate" required value="{{ old('email') }}">
            <label for="email">メールアドレス</label>
          </div>
        </div>
        <div class="row">
          <div class="col s12 center-align">
            <button class="btn waves-effect waves-light" type="submit" name="action">送信
              <i class="material-icons right">send</i>
            </button>
          </div>
        </div>
        <div class="row">
          <div class="col s12 center-align">
            <p><a href="/login">ログインに戻る</a></p>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection