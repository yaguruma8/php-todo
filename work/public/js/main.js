'use strict';

{
  const toggleBoxes = document.querySelectorAll('.toggle');
  toggleBoxes.forEach((toggleBox) => {
    toggleBox.addEventListener('change', () => {
      toggleBox.parentNode.submit();
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
  
}
