let modal;

document.addEventListener('DOMContentLoaded', function() {
  // モーダルの初期化
  const $modal = document.querySelector('#task-modal');
  modal = M.Modal.init($modal, {
    dismissible: true,
  });

  // ドロップダウン（Select）の初期化
  var $selects = document.querySelectorAll('select');
  M.FormSelect.init($selects);

  // カレンダー（DatePicker）の初期化
  var $datepickers = document.querySelectorAll('.datepicker');
  M.Datepicker.init($datepickers, {
    format: 'yyyy-mm-dd', // 日付のフォーマット
    autoClose: true, // 日付選択後に自動で閉じる
  });


});

function toggleCustomCategory($select) {
  var value = $select.value;
  var $customCategory = document.querySelector('#custom-category');
  if(value === '0') { // 「その他」が選択された場合
    $customCategory.style.display = 'block';
  } else {
    $customCategory.value = '';
    $customCategory.style.display = 'none';
  }
}

function addTask() {
  modal.open();
  document.querySelector('#task-heading').innerHTML = 'タスクを追加';
  document.querySelector('#task-id').value = '';
  document.querySelector('#task-name').value = '';
  document.querySelector('#custom-category-input').value = '';
  const $taskCategory = document.querySelector('#task-category');
  $taskCategory.value = '';
  const $dueDate = document.querySelector('#due-date');
  $dueDate.value = '';
  document.querySelector('#task-description').value = '';
  document.querySelector('#register-task').style.display = 'inline-block';
  document.querySelector('#update-task').style.display = 'none';
  
  triggerEvent($taskCategory);
  triggerEvent($dueDate);
}

function editTask(id) {
  modal.open();
  const $row = document.querySelector(`.task[data-task-id="${id}"]`);

  document.querySelector('#task-heading').innerHTML = 'タスクを編集';
  document.querySelector('#task-id').value = id;
  document.querySelector('#task-name').value = $row.querySelector('.task-name').innerHTML;
  document.querySelector('#custom-category-input').value = '';
  const $taskCategory = document.querySelector('#task-category');
  $taskCategory.value = $row.querySelector('.task-category').dataset['categoryId'];
  const $dueDate = document.querySelector('#due-date');
  $dueDate.value = $row.querySelector('.task-due-date').innerHTML;
  document.querySelector('#task-description').value = $row.querySelector('.task-description').innerHTML;;
  document.querySelector('#register-task').style.display = 'none';
  document.querySelector('#update-task').style.display = 'inline-block';
  
  triggerEvent($taskCategory);
  triggerEvent($dueDate);
}

function registerTask() {
  const data = {
    _token: document.querySelector('[name="_token"]').value,
    name: document.querySelector('#task-name').value,
    categoryId: document.querySelector('#task-category').value, 
    categoryName: document.querySelector('#custom-category-input').value,
    dueDate: document.querySelector('#due-date').value,
    description: document.querySelector('#task-description').value
  }

  fetch('/tasks', {
    method: 'post',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  }).then(response => {
    return response.json();
  }).then(response => {
    if (response.status === 'ok') {
      modal.close();
      // 表に反映
      const $template = document.querySelector("template").content;
      const $content = $template.cloneNode(true);
      const $taskRow = $content.querySelector('tr');
      $taskRow.dataset['taskId'] = response.task.id;
      $taskRow.addEventListener('mouseenter', () => toggleButtons(response.task.id, true));
      $taskRow.addEventListener('mouseleave', () => toggleButtons(response.task.id, false));
      $content.querySelector('.task-name').innerHTML = response.task.name;
      $category = $content.querySelector('.task-category');
      $category.dataset['categoryId'] = response.task.category_id;
      $category.innerHTML = response.task.category_name;
      $content.querySelector('.task-due-date').innerHTML = response.task.due_date;
      $content.querySelector('.edit-btn').addEventListener('click', () => editTask(response.task.id));
      $content.querySelector('.delete-btn').addEventListener('click', () => deleteTask(response.task.id));
      document.querySelector('#task-table tbody').append($taskRow);
    } else {
      alert('更新に失敗しました。¥n' + body.message);  
    }
  }).catch(err => {
    alert('登録に失敗しました。¥n' + err);
  });
}

function closeModal() {
  modal.close();
}

function updateTask() {
  const data = {
    _token: document.querySelector('[name="_token"]').value,
    id: document.querySelector('#task-id').value,
    name: document.querySelector('#task-name').value,
    categoryId: document.querySelector('#task-category').value, 
    categoryName: document.querySelector('#custom-category-input').value,
    dueDate: document.querySelector('#due-date').value,
    description: document.querySelector('#task-description').value
  }

  fetch(`/tasks/${data.id}`, {
    method: 'put',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  }).then(response => {
    return response.json()
  }).then(response => {
    if (response.status === 'ok') {
      modal.close();
      
      // 表に反映
      const $taskRow = document.querySelector(`tr[data-task-id="${response.task.id}"]`);
      $taskRow.dataset['taskId'] = response.task.id;
      $taskRow.querySelector('.task-name').innerHTML = response.task.name;
      $category = $taskRow.querySelector('.task-category');
      $category.dataset['categoryId'] = response.task.category_id;
      $category.innerHTML = response.task.category_name;
      $taskRow.querySelector('.task-due-date').innerHTML = response.task.due_date;
    } else {
      alert('更新に失敗しました。¥n' + body.message);  
    }
  }).catch(err => {
    alert('更新に失敗しました。¥n' + err);
  });
}

function deleteTask(id) {
  if (!confirm('タスクを削除してもよろしいですか?')) {
    return;
  }

  const data = {
    _token: document.querySelector('[name="_token"]').value,
    id: id,
  }

  fetch(`/tasks/${id}`, {
    method: 'delete',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  }).then(response => {
    return response.json()
  }).then(response => {
    if (response.status === 'ok') {
      modal.close();
      document.querySelector(`tr[data-task-id="${id}"]`).remove();
    } else {
      alert('削除に失敗しました。¥n' + body.message);  
    }
  }).catch(err => {
    alert('削除に失敗しました。¥n' + err);
  });
}

function triggerEvent($element) {
  const event = new Event('change', { bubbles: true, cancelable: true });
  $element.dispatchEvent(event);
}

function toggleButtons(id, isShow) {
  document.querySelectorAll(`tr[data-task-id="${id}"] .btn`).forEach($btn => {
    if (isShow) {
      $btn.classList.remove('hidden');
    } else {
      $btn.classList.add('hidden');
    }
  });
}
