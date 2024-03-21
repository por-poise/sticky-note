@php($title='トップページ')
@extends('layouts.main')

@section('content')
  <div class="container">
    <h2 class="header center-align">ようこそ、タスク管理アプリへ</h2>
    <div class="row center">
      <h5 class="header col s12 light">あなたのタスクを簡単に管理しましょう</h5>
    </div>
    <div class="row center">
      <p>
        <a href="/register" id="download-button" class="btn-large waves-effect waves-light teal lighten-1">始める</a>
      </p>
      <p>
        すでにアカウントをお持ちの方は<a href="/login">こちら</a>
      </p>      
    </div>
  </div>
@endsection
