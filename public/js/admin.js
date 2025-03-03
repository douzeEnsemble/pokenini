function watchActionLogToggles() {
    document.querySelectorAll(".admin-item-current .admin-item-toggle").forEach(function (element) {
      element.addEventListener("click", onToggleCurrent);
    });
    document.querySelectorAll(".admin-item-last .admin-item-toggle").forEach(function (element) {
      element.addEventListener("click", onToggleLast);
    });
}
  
function onToggleCurrent(event) {
    event.preventDefault();

    const target = event.target;

    const currentItem = target.closest('.admin-item-current');
    const lastItem = currentItem.parentNode.querySelector('.admin-item-last');
    const adminItem = target.closest('.admin-item');
  
    currentItem.setAttribute('hidden', true);
    lastItem.removeAttribute('hidden');

    adminItem.querySelectorAll('.icon-square').forEach(icon => {
      icon.classList.replace(
        'bg-'+adminItem.getAttribute('data-current-list-group-item'), 
        'bg-'+adminItem.getAttribute('data-last-list-group-item'),
      );
    });
    adminItem.querySelectorAll('.border-top').forEach(icon => {
      icon.classList.replace(
        'border-'+adminItem.getAttribute('data-current-list-group-item'), 
        'border-'+adminItem.getAttribute('data-last-list-group-item'),
      );
    });
}
function onToggleLast(event) {
  event.preventDefault();

  const target = event.target;

  const lastItem = target.closest('.admin-item-last');
  const currentItem = lastItem.parentNode.querySelector('.admin-item-current');
  const adminItem = target.closest('.admin-item');

  currentItem.removeAttribute('hidden');
  lastItem.setAttribute('hidden', true);

  adminItem.querySelectorAll('.icon-square').forEach(icon => {
    icon.classList.replace(
      'bg-'+adminItem.getAttribute('data-last-list-group-item'),
      'bg-'+adminItem.getAttribute('data-current-list-group-item'), 
    );
  });
  adminItem.querySelectorAll('.border-top').forEach(icon => {
    icon.classList.replace(
      'border-'+adminItem.getAttribute('data-last-list-group-item'),
      'border-'+adminItem.getAttribute('data-current-list-group-item'), 
    );
  });
}