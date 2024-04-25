const STATUS = {
  0: '未処理',
  1: '処理中',
  2: '処理済み',
  3: '完了'
}

document.addEventListener('DOMContentLoaded', function() {
  // ドロップダウン（Select）の初期化
  var selects = document.querySelectorAll('select');
  M.FormSelect.init(selects);

  // カレンダー（DatePicker）の初期化
  var datepickers = document.querySelectorAll('.datepicker');
  M.Datepicker.init(datepickers, {
    format: 'yyyy-mm-dd', // 日付のフォーマット
    autoClose: true, // 日付選択後に自動で閉じる
  });
});