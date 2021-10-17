
let btn = document.getElementById("btn-contributor");
btn.addEventListener("click", contributorDropdown);

function contributorDropdown() {
  let target = document.getElementById("target-contributor");
  target.classList.toggle("js-toggle");
}