@php($title='パスワード再設定')
@extends('layouts.main')

@section('content')
  <div class="container">
    <h2 class="center-align">{{ $title }}</h2>
    @include('layouts.common.form_errors')

    <div class="row">
      <form class="col s12" action="/reset_password" method="post">
        @csrf
        
        <input type="hidden" name="email" value="{{request()->input('email')}}">
        <input type="hidden" name="token" value="{{request()->input('token')}}">
        <div class="row">
          <div class="input-field col s12">
            <input id="password" type="password"  name="password" class="validate" required value="{{ old('password') }}">
            <label for="password">パスワード</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <input id="password_confirmation" type="password"  name="password_confirmation" class="validate" required value="{{ old('password') }}">
            <label for="password_confirmation">パスワード（再確認）</label>
          </div>
        </div>
        <div class="row">
          <div class="col s12 center-align">
            <button class="btn waves-effect waves-light" type="submit" name="action">送信
              <i class="material-icons right">send</i>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection