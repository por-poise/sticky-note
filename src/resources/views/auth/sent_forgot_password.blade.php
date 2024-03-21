@php($title='パスワード再設定')
@extends('layouts.main')

@section('content')
  <div class="container">
    <h2 class="center-align">{{ $title }}</h2>
    @include('layouts.common.form_errors')

    <div class="row">
      <div class="row">
        <div class="col s12 center-align">
          <p>パスワード再設定用のメールをお送りしました。</p>
          <p>メールに記載のリンクに従ってパスワードを再設定してください。</p>
          <p><a href="/login">ログインに戻る</a></p>
        </div>
      </div>
    </div>
  </div>
@endsection