
let btnMappool = document.getElementById("btn-mappool");
btnMappool.addEventListener("click", mappoolDropdown);

function mappoolDropdown() {
  let targetMappool = document.getElementById("target-mappool");
  targetMappool.classList.toggle("js-toggle-mappool");
}