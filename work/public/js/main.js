'use strict';

{
  const token = document.querySelector('main').dataset.token;
  const input = document.querySelector('.add > input[name="title"]');

  input.focus();

  const addForm = document.querySelector('.add');
  addForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const title = input.value;
    if (!title.trim()) {
      input.value = '';
      return;
    }
    try {
      const res = await fetch('?action=add', {
        method: 'POST',
        body: new URLSearchParams({
          title,
          token,
        }),
      });
      if (!res.ok) {
        throw new Error('response is not ok.');
      }
      const json = await res.json();
      addTodo(json.id, title);
    } catch (err) {
      console.error(err.message);
    }
    input.value = '';
    input.focus();
  });

  function addTodo(id, title) {
    const li = element`
    <li data-id="${id}">
      <input type="checkbox" class="toggle">
      <span>${title}</span>
      <span class="delete">X</span>
    </li>
    `;
    const ul = document.querySelector('ul');
    ul.insertBefore(li, ul.firstChild);
  }

  const ul = document.querySelector('ul');
  ul.addEventListener('click', (e) => {
    if (e.target.classList.contains('toggle')) {
      toggleTodo(e.target);
    }
    if (e.target.classList.contains('delete')) {
      deleteTodo(e.target);
    }
    e.stopPropagation();
  });

  function toggleTodo(target) {
    const id = target.parentNode.dataset.id;
    // checkedの情報を送る
    const checkFlag = target.checked;
    fetch('?action=toggle', {
      method: 'POST',
      body: new URLSearchParams({
        id,
        token,
        checkFlag,
      }),
    })
      .then((res) => {
        if (!res.ok) {
          throw new Error('todo deleted already');
        }
      })
      .catch((err) => {
        console.error(err.message);
        window.alert(
          '該当のtodoはすでに削除されています。画面を最新のデータに更新します。'
        );
        location.reload();
      });
  }

  function deleteTodo(target) {
    if (!confirm('削除しますか？')) {
      return;
    }
    const id = target.parentNode.dataset.id;
    fetch('?action=delete', {
      method: 'POST',
      body: new URLSearchParams({
        id,
        token,
      }),
    });
    target.parentNode.remove();
  }

  const purgeButton = document.querySelector('.purge');
  purgeButton.addEventListener('click', () => {
    // フロント側のチェック済みidの配列を作成
    const lis = document.querySelectorAll('li');
    let appCheckedIds = [];
    for (const li of lis) {
      if (li.firstElementChild.checked) {
        appCheckedIds.push(li.dataset.id);
      }
    }
    // チェック済みidの数が0なら何もしない
    if (!appCheckedIds.length) {
      return;
    }

    if (!confirm('are you sure?')) {
      return;
    }
    fetch('?action=purge', {
      method: 'POST',
      body: new URLSearchParams({
        token,
        appCheckedIds,
      }),
    })
      .then((res) => {
        if (!res.ok) {
          throw new Error('failed to purge');
        }
      })
      .catch((err) => {
        console.error(err.message);
        alert(
          'データが更新されているためパージに失敗しました。画面を更新してください。'
        );
        location.reload();
      });
    // チェック済みtodoを削除する
    // データに差異があればこの後のcatchのリロードで整合性をとる
    lis.forEach((li) => {
      if (li.firstElementChild.checked) {
        li.remove();
      }
    });
  });

  // Utils
  function escapeSpecialChars(str) {
    const regSet = [
      [/&/g, '&amp;'],
      [/</g, '&lt;'],
      [/>/g, '&gt;'],
      [/"/g, '&quot;'],
      [/'/g, '&#039;'],
    ];
    return regSet.reduce(
      (escapeStr, [reg, entity]) => escapeStr.replace(reg, entity),
      str
    );
  }

  function element(strings, ...values) {
    const htmlString = strings.reduce((result, currentStr, i) => {
      return result + escapeSpecialChars(String(values[i - 1])) + currentStr;
    });

    const temp = document.createElement('template');
    temp.innerHTML = htmlString;
    return temp.content.firstElementChild;
  }
}
