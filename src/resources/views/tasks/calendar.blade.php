@php
use Illuminate\Support\Facades\Auth;
use Carbon\CarbonImmutable;
use App\Helpers\CalendarHelper;

$title='カレンダー';
$user=Auth::user();
@endphp

@extends('layouts.main')

@section('head')
  <style>
    .row .col.s17 {
      width: 14.2857142857%;
      margin-left: auto;
      left: auto;

    }
    .calendar-container {
      margin-top: 20px;
    }

    .calendar-header {
      font-weight: bold;
      text-align: center;
    }

    .calendar-row {
      margin: 0;
      display: flex;
    }

    .date-box {
      border: 1px solid #ddd;
      min-height: 100px;
      text-align: center;
      padding: 5px;
    }

    .task-list {
      text-align: left;
      margin-top: 10px;
    }

    .task-item {
      padding: 2px 5px;
      border-radius: 2px;
      font-size: 12px;
      margin-bottom: 5px;
      display: inline-block;
    }
  </style>
@endsection

@section('content')
  <div class="container">
    <h2 class="center-align">{{ $title }}</h2>
    <div class="row">
      <div class="input-field col s12">
        <select id="task_category">
          <option value="202401">2024年1月</option>
          <option value="202402">2024年2月</option>
          <option value="202403" selected>2024年3月</option>
        </select>
        <label for="task_category">基準月</label>
      </div>
    </div>
    <div class="row calendar-container">
      <!-- カレンダーのヘッダ -->
      <div class="col s12">
        <div class="row">
          <div class="col s12 calendar-header">
            <div class="col s17">日</div>
            <div class="col s17">月</div>
            <div class="col s17">火</div>
            <div class="col s17">水</div>
            <div class="col s17">木</div>
            <div class="col s17">金</div>
            <div class="col s17">土</div>
          </div>
        </div>
      </div>
      <!-- カレンダーの日付とタスク -->
      <div class="col s12">
        @php
          $maxWeek = (int)((int)$endDate->format('d') + $startDate->dayOfWeek()) / 7 + 1; // (月末日の日にち + 月初日の曜日) / 7 = 月の週の数
        @endphp
        @for ($week = 1; $week <= 6; $week++)
          @if ($week >= $maxWeek)
            {{-- 今月の日が1日もない週は非表示 --}}
            @break
          @endif
          <div class="row calendar-row">
            @for ($day = 1; $day <= 7; $day++)
              @php
                $days = ($week - 1) * 7 + $day - $startDate->dayOfWeek();
                $date = $startDate->addDays($days - 1);
                $tasksOfDate = [];
                if (array_key_exists($date->format('Y-m-d'), $tasks)) {
                  $tasksOfDate = $tasks[$date->format('Y-m-d')];
                };

                // 今月以外の日をマーク
                $bgColorClass = '';
                $dateTextClass = '';
                if ($date < $startDate || $endDate < $date) {
                  $bgColorClass = 'grey lighten-4';
                  $dateTextClass = 'grey-text';
                } else if ($date->dayOfWeek() === 0) {
                  $bgColorClass = 'red lighten-5';
                  $dateTextClass = 'red-text';
                } else if ($date->dayOfWeek() === 6) {
                  $bgColorClass = 'blue lighten-5';
                  $dateTextClass = 'blue-text';
                }
              @endphp
              <div class="col s17 date-box {{ $bgColorClass }}">
                <div class="{{ $dateTextClass }}">{{ $date->day }}</div>
                <div class="task-list">
                  @foreach ($tasksOfDate as $task)
                    <div class="task-item">{{ $task->name }}</div>
                  @endforeach
                </div>
              </div>
            @endfor
          </div>
        @endfor
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('/js/colors.js') }}"></script>
  <script src="{{ asset('/js/calendar.js') }}"></script>
@endsection