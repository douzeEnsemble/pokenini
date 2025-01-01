function watchToggleShinyMode() {
  document
    .querySelectorAll(".election-card-icon-shiny")
    .forEach(function (element) {
      element.addEventListener("click", onDisplayShinyImageMode);
    });
  document
    .querySelectorAll(".election-card-icon-regular")
    .forEach(function (element) {
      element.addEventListener("click", onDisplayRegularImageMode);
    });
}

function onDisplayShinyImageMode(event) {
  event.preventDefault();

  const target = event.target;
  const cardBody = target.closest(".card-body");

  cardBody.querySelector('.election-card-image-container-regular').setAttribute("hidden", true);
  cardBody.querySelector('.election-card-image-container-shiny').removeAttribute('hidden');
  cardBody.querySelector('.election-card-icon-regular').classList.remove('active');
  cardBody.querySelector('.election-card-icon-shiny').classList.add('active');
}

function onDisplayRegularImageMode(event) {
  event.preventDefault();

  const target = event.target;
  const cardBody = target.closest(".card-body");

  cardBody.querySelector('.election-card-image-container-regular').removeAttribute('hidden');
  cardBody.querySelector('.election-card-image-container-shiny').setAttribute("hidden", true);
  cardBody.querySelector('.election-card-icon-regular').classList.add('active');
  cardBody.querySelector('.election-card-icon-shiny').classList.remove('active');
}