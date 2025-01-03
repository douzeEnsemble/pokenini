function watchWinnerCheckboxes () {
  document
    .querySelectorAll('input[type="checkbox"][name="winners_slugs[]"')
    .forEach(function (element) {
      element.addEventListener('change', onChangeWinnerCheckbox);
    });
}

function onChangeWinnerCheckbox(event) {
  event.preventDefault();

  const target = event.target;
  
  changeCardBorder(target);
  updateElectionCounter(target);
}

function changeCardBorder(target) {
  const card = target.closest('.card');

  if (target.checked) {
    card.classList.add('border-primary');
  } else {
    card.classList.remove('border-primary');
  }
}

function updateElectionCounter() {
  const checkboxes = document.querySelectorAll('input[name="winners_slugs[]"]');
  const checkedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);
  const count = checkedCheckboxes.length;

  document.getElementById('election-counter').textContent = count;
}

function watchSubmitAction () {
  document.getElementById('election-vote-submit').addEventListener('click', onSubmitVote);
}

function onSubmitVote() {
  document.getElementById('election').submit();
}