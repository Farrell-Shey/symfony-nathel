document.addEventListener("DOMContentLoaded", function (e) { })
dropdown.addEventListener("click", contributorToggle);

function contributorToggle() {
  var dropdown = document.getElementById("contributors");
  var up = document.getElementById("dropdown");
  dropdown.classList.toggle("toggle-fit-content");
  up.classList.toggle('btn-rotate');
}