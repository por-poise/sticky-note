@php
use Illuminate\Support\Facades\Auth;
$title='タスク一覧';
$user=Auth::user();
@endphp

@extends('layouts.main')

@section('head')
  <style>
    .hidden {
      /* visibility: hidden; */
      opacity: 0.001;
      transition: none;
    }
  </style>
@endsection

@section('content')
  <div class="container">
    <h2 class="center-align">{{ $title }}</h2>
    
    <!-- タスク追加ボタン -->
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large red" onclick="addTask()" href="#">
            <i class="large material-icons">add</i>
        </a>
    </div>
    
    <!-- タスクリスト -->
    {{-- <p>10 / 100件</p> --}}
    <table id="task-table" class="striped">
      <thead>
        <tr>
          <th></th>
          <th>タスク名 <i class="material-icons">arrow_downward</i></th>
          <th>カテゴリ <i class="material-icons">arrow_downward</i></th>
          <th>期限日 <i class="material-icons">arrow_downward</i></th>
          <th></th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        @foreach ($tasks as $task)
          <tr class="task" data-task-id="{{ $task->id }}" onmouseenter="toggleButtons({{ $task->id }}, true)" onmouseleave="toggleButtons({{ $task->id }}, false)">
            <td></td>
            <td class="task-name">{{ $task->name }}</td>
            <td class="task-category" data-category-id="{{ $task->category->id }}">{{ $task->category->name }}</td>
            <td class="task-due-date">{{ $task->due_date }}</td>
            <td><button class="btn edit-btn waves-effect waves-light hidden" onclick="editTask({{ $task->id }})"><i class="material-icons">edit</i></button></td>
            <td><button class="btn delete-btn waves-effect waves-light red hidden" onclick="deleteTask({{ $task->id }})"><i class="material-icons">delete</i></button></td>
            <td class="task-description hide">{{ $task->description }}</td>
          </tr>
        @endforeach
      </tbody>

      <template id="task-template">
        <tr class="task" data-task-id="id">
          <td></td>
          <td class="task-name"></td>
          <td class="task-category" data-category-id=""></td>
          <td class="task-due-date"></td>
          <td><button class="btn edit-btn waves-effect waves-light hidden"><i class="material-icons">edit</i></button></td>
          <td><button class="btn delete-btn waves-effect waves-light red hidden"><i class="material-icons">delete</i></button></td>
          <td class="task-description hide"></td>
        </tr>
      </template>
    </table>

    <!-- タスク追加モーダル -->
    <div id="task-modal" class="modal">
      <div class="modal-content">
        <h4 id="task-heading">タスクを追加</h4>
        <div class="row">
          <form class="col s12">
            @csrf
            <input type="hidden" id="task-id" value>
            <div class="row">
              <div class="input-field col s12">
                <input placeholder="タスク名" id="task-name" type="text" class="validate">
                <label for="task-name">タスク名</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <select id="task-category" onchange="toggleCustomCategory(this)">
                  <option value="" disabled selected>選択してください</option>
                  <option value="0">（新規追加）</option>
                  @foreach ($categories as $category) 
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>
                <label for="task-category">カテゴリ</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12" id="custom-category" style="display: none;">
                <input placeholder="カテゴリを入力" id="custom-category-input" type="text" class="validate">
                <label for="custom-category-input">カスタムカテゴリ</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s4">
                <input type="text" class="datepicker" id="due-date">
                <label for="due-date">期限日</label>
              </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                  <textarea placeholder="説明" id="task-description" class="materialize-textarea validate"></textarea>
                  <label for="task-description">タスクの説明</label>
                </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" id="close-modal" onclick="closeModal()" class="waves-effect waves-green">キャンセル</a>
        <a href="#" id="register-task" onclick="registerTask()" class="btn waves-effect waves-green">追加</a>
        <a href="#" id="update-task" onclick="updateTask()" class="btn waves-effect waves-green">更新</a>
      </div>
    </div>

    {{-- TODO: ページネーション実装
    <ul class="pagination center-align">
      <li class="disabled"><a href="#!"><i class="material-icons">chevron-left</i></a></li>
      <li class="active"><a href="#!">1</a></li>
      <li class="waves-effect"><a href="#!">2</a></li>
      <li class="waves-effect"><a href="#!">3</a></li>
      <li class="waves-effect"><a href="#!">4</a></li>
      <li class="waves-effect"><a href="#!">5</a></li>
      <li class="waves-effect"><a href="#!"><i class="material-icons">chevron-right</i></a></li>
    </ul> --}}

    {{-- {{ $tasks->links() }} --}}

  </div>
@endsection

@section('scripts')
  <script src="{{ asset('/js/task.js') }}"></script>
@endsection