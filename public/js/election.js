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
  const checkboxes = document.querySelectorAll('input[type="checkbox"][name="winners_slugs[]"');
  const checkedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);
  const count = checkedCheckboxes.length;

  document.getElementById('election-counter').textContent = count;
}

function watchCardClicking () {
  document
    .querySelectorAll('#election .election-card-image-container-regular')
    .forEach(function (element) {
      element.addEventListener('click', onCardClick);
    });
  document
    .querySelectorAll('#election .list-group-item strong')
    .forEach(function (element) {
      element.addEventListener('click', onCardClick);
    });
}

function onCardClick(event) {
  event.preventDefault();

  const target = event.target;
  const card = target.closest('.card');

  card.querySelector('input[type="checkbox"][name="winners_slugs[]"').click();
}

function watchSubmitAction () {
  document.getElementById('election-vote-submit').addEventListener('click', onSubmitVote);
}

function onSubmitVote() {
  document.getElementById('election').submit();
}