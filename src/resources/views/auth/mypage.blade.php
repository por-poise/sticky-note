@php
use Illuminate\Support\Facades\Auth;
$title='マイページ';
$user=Auth::user();
@endphp

@extends('layouts.main')

@section('content')
  <div class="container">
    <div class="row center">
      <div class="col s12 center-align">
        <h2>{{$title}}</h2>
      </div>
    </div>
    <div class="row center">
      <table class="striped">
        <tr>
            <th>会員ID</th>
            <td>{{$user->id}}</td>
            <td>
              <a class="waves-effect waves-light btn disabled red">退会</a>
            </td>
        </tr>
        <tr>
          <th>ニックネーム</th>
          <td>{{$user->name}}</td>
          <td>
            <a class="waves-effect waves-light btn disabled">変更</a>
          </td>
        </tr>
        <tr>
          <th>メールアドレス</th>
          <td>{{$user->email}}</td>
          <td>
            <a class="waves-effect waves-light btn disabled">変更</a>
          </td>
        </tr>
        <tr>
            <th>パスワード</th>
            <td>********</td>
            <td>
              <a class="waves-effect waves-light btn disabled">変更</a>
            </td>
        </tr>
    </div>
  </div>
@endsection
