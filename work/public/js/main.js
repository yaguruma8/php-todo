'use strict';

{
  const token = document.querySelector('main').dataset.token;

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
    lis.forEach(li => {
      if (li.firstElementChild.checked) {
        li.remove();
      }
    })
  });
}
