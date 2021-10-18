'use strict';

{
  const token = document.querySelector('main').dataset.token;
  const input = document.querySelector('.add > input[name="title"]');

  input.focus();

  const addForm = document.querySelector('.add');
  addForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    const title = input.value;
    if (!title.trim()) {
      input.value = '';
      return;
    }
    const res = await fetch('?action=add', {
      method: 'POST',
      body: new URLSearchParams({
        title,
        token,
      }),
    });
    const json = await res.json();
    addTodo(json.id, title);
    input.value = '';
    input.focus();
    console.log('finish!');
  });

  function addTodo(id, title) {
    const li =element`
    <li data-id="${id}">
      <input type="checkbox" class="toggle">
      <span>${title}</span>
      <span class="delete">X</span>
    </li>
    `;
    const ul = document.querySelector('ul');
    ul.insertBefore(li, ul.firstChild);
  }

  const toggleBoxes = document.querySelectorAll('.toggle');
  toggleBoxes.forEach((toggleBox) => {
    toggleBox.addEventListener('change', () => {
      const id = toggleBox.parentNode.dataset.id;
      fetch('?action=toggle', {
        method: 'POST',
        body: new URLSearchParams({
          id,
          token,
        }),
      });
      // toggleBox.nextElementSibling.classList.toggle('done');
    });
  });

  const deleteButtons = document.querySelectorAll('.delete');
  deleteButtons.forEach((deleteButton) => {
    deleteButton.addEventListener('click', () => {
      if (!confirm('削除しますか？')) {
        return;
      }
      const id = deleteButton.parentNode.dataset.id;
      fetch('?action=delete', {
        method: 'POST',
        body: new URLSearchParams({
          id,
          token,
        }),
      });
      deleteButton.parentNode.remove();
    });
  });

  const purgeButton = document.querySelector('.purge');
  purgeButton.addEventListener('click', () => {
    if (!confirm('完了済todoをまとめて削除しますか？')) {
      return;
    }
    fetch('?action=purge', {
      method: 'POST',
      body: new URLSearchParams({
        token,
      }),
    });

    const lis = document.querySelectorAll('li');
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
    let escapeStr = str;
    for (const [reg, entity] of regSet) {
      escapeStr = escapeStr.replace(reg, entity);
    }
    return escapeStr;
  }

  function element(strings, ...values) {
    const htmlString = strings.reduce((result, currentStr, i) => {
      return result + escapeSpecialChars(String(values[i - 1])) + currentStr
    });
    const temp = document.createElement('template');
    temp.innerHTML = htmlString;
    
    return temp.content.firstElementChild;
  }

}
