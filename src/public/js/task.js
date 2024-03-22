let modal;

const STATUS = {
  0: '未処理',
  1: '処理中',
  2: '処理済み',
  3: '完了'
}

document.addEventListener('DOMContentLoaded', function() {
  // モーダルの初期化
  const $modal = document.querySelector('#task-modal');
  modal = M.Modal.init($modal, {
    dismissible: true,
  });

  // タスクドロップダウンの初期化
  const $taskCategory = document.querySelector('#task-category');
  M.FormSelect.init($taskCategory);
  
  // カラードロップダウンの初期化
  const $colors = document.querySelector('#task-color');
  for (color of COLORS) {
    const $colorOption = document.createElement('option');
    $colorOption.value = color.code;
    $colorOption.innerHTML = color.text;
    $colors.appendChild($colorOption);
  }
  M.FormSelect.init($colors);
  const $colorList = document.querySelectorAll('ul:has(~ #task-color) li');
  for (const $colorItem of $colorList) {
    const $colorText = $colorItem.querySelector('span');
    let color = COLORS.filter(c => c.text === $colorText.innerHTML);
    if (!color.length || color.code === 'none') {
      continue;
    }
    color = color[0];
    $colorText.classList.add(`${color.textColor}-text`);
    $colorItem.classList.add(color.code);
  }

  // ステータスドロップダウンの初期化
  const $status = document.querySelector('#task-status');
  M.FormSelect.init($status);

  // カレンダーの初期化
  const $datepickers = document.querySelectorAll('.datepicker');
  M.Datepicker.init($datepickers, {
    format: 'yyyy-mm-dd', // 日付のフォーマット
    autoClose: true, // 日付選択後に自動で閉じる
  });


});

function toggleCustomCategory($select) {
  const value = $select.value;
  const $customCategory = document.querySelector('#task-custom-category');
  const $wrapperDiv = document.querySelector('div#custom-category');
  if(value === '0') { // 「新規追加」が選択された場合
    $wrapperDiv.style.display = 'block';
  } else {
    $customCategory.value = '';
    $wrapperDiv.style.display = 'none';
  }
}

function reflectColor($select) {
  const code = $select.value;
  const $input = document.querySelector('input:has(~ #task-color)');
  for (const color of COLORS) {
    $input.classList.remove(color.code);
    $input.classList.remove(`${color.textColor}-text`);
  }
  const color = COLORS.filter(c => c.code === code)[0];
  if (code === '' || code === 'none') {
    return;
  }
  $input.classList.add(color.code);
  $input.classList.add(`${color.textColor}-text`);
}

function addTask() {
  modal.open();
  document.querySelector('#task-heading').innerHTML = 'タスクを追加';
  document.querySelector('#task-id').value = '';
  document.querySelector('#task-name').value = '';
  document.querySelector('#task-custom-category').value = '';
  const $taskCategory = document.querySelector('#task-category');
  $taskCategory.value = '';
  const $dueDate = document.querySelector('#task-due-date');
  $dueDate.value = '';
  const $color = document.querySelector('#task-color');
  $color.value = '';
  const $status = document.querySelector('#task-status');
  $color.value = 0;
  const $description = document.querySelector('#task-description');
  $description.value = '';
  document.querySelector('#register-task').style.display = 'inline-block';
  document.querySelector('#update-task').style.display = 'none';
  
  triggerEvent($taskCategory, 'change');
  triggerEvent($dueDate, 'change');
  triggerEvent($color, 'change');
  triggerEvent($status, 'change');
  triggerEvent($description, 'change');
}

function editTask(id) {
  fetch(`/tasks/${id}/edit`, {
    method: 'get',
    headers: { 'Content-Type': 'application/json' },
  }).then(response => {
    return response.json();
  }).then(response => {
    if (response.status === 'ok') {
      const task = response.task;
      const $row = document.querySelector(`.task[data-task-id="${id}"]`);

      modal.open();
      document.querySelector('#task-heading').innerHTML = 'タスクを編集';
      document.querySelector('#task-id').value = task.id;
      document.querySelector('#task-name').value = task.name;
      document.querySelector('#task-custom-category').value = '';
      
      const $taskCategory = document.querySelector('#task-category');
      $taskCategory.value = task.category_id;
      
      const $dueDate = document.querySelector('#task-due-date');
      $dueDate.value = task.due_date;
      
      const $color = document.querySelector('#task-color');
      $color.value = task.color;

      const $status = document.querySelector('#task-status');
      $status.value = task.status;

      const $description = document.querySelector('#task-description');
      $description.value = task.description;

      document.querySelector('#register-task').style.display = 'none';
      document.querySelector('#update-task').style.display = 'inline-block';
      
      triggerEvent($taskCategory, 'change');
      triggerEvent($dueDate, 'change');
      triggerEvent($color, 'change');
      triggerEvent($status, 'change');
      triggerEvent($description, 'change');
    } else {
      alert('エラーが発生したためタスクの編集ができません。¥n' + response.message);
    }
  }).catch(err => {
    alert('エラーが発生したためタスクの編集ができません。¥n' + err);
  })
}

function registerTask() {
  const data = {
    _token: document.querySelector('[name="_token"]').value,
    name: document.querySelector('#task-name').value,
    categoryId: document.querySelector('#task-category').value, 
    categoryName: document.querySelector('#task-custom-category').value,
    dueDate: document.querySelector('#task-due-date').value,
    color: document.querySelector('#task-color').value,
    status: document.querySelector('#task-status').value,
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
      $category.innerHTML = response.task.category_name;
      $content.querySelector('.task-due-date').innerHTML = response.task.due_date;
      $taskRow.querySelector('.task-status').innerHTML = STATUS[response.task.status];
      $content.querySelector('.edit-btn').addEventListener('click', () => editTask(response.task.id));
      $content.querySelector('.delete-btn').addEventListener('click', () => deleteTask(response.task.id));
      document.querySelector('#task-table tbody').append($taskRow);
      // カテゴリ追加
      if (data.categoryId === 0) {
        const $taskCategory = document.querySelector('#task-category');
        const $option = document.createElement('option');
        $option.value = response.task.category_id;
        $option.innerHTML = response.task.category_name;
        $taskCategory.appendChild($option);
      }
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
    categoryName: document.querySelector('#task-custom-category').value,
    dueDate: document.querySelector('#task-due-date').value,
    color: document.querySelector('#task-color').value,
    status: document.querySelector('#task-status').value,
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
      $category.innerHTML = response.task.category_name;
      $taskRow.querySelector('.task-due-date').innerHTML = response.task.due_date;
      $taskRow.querySelector('.task-status').innerHTML = STATUS[response.task.status];

      // カテゴリ追加
      if (data.categoryId === 0) {
        const $taskCategory = document.querySelector('#task-category');
        const $option = document.createElement('option');
        $option.value = response.task.category_id;
        $option.innerHTML = response.task.category_name;
        $taskCategory.appendChild($option);
      }
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

function triggerEvent($element, eventName) {
  const event = new Event(eventName, { bubbles: true, cancelable: true });
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
