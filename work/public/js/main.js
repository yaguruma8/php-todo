'use strict';

{
  const toggleBoxes = document.querySelectorAll('.toggle');
  toggleBoxes.forEach((toggleBox) => {
    toggleBox.addEventListener('change', () => {
      fetch('?action=toggle', {
        method: 'POST',
        body: new URLSearchParams({
          id: toggleBox.dataset.id,
          token: toggleBox.dataset.token,
        }),
      });
      toggleBox.nextElementSibling.classList.toggle('done');
      // toggleBox.parentNode.submit();
    });
  });

  const deleteButtons = document.querySelectorAll('.delete');
  deleteButtons.forEach((deleteButton) => {
    deleteButton.addEventListener('click', () => {
      if (!confirm('削除しますか？')) {
        return;
      }
      deleteButton.parentNode.submit();
    });
  });

  const purgeButton = document.querySelector('.purge');
  purgeButton.addEventListener('click', () => {
    if (!confirm('完了済todoをまとめて削除しますか？')) {
      return;
    }
    purgeButton.parentNode.submit();
  });
}
