
//let btnMappools = document.querySelectorAll("div[data-id='target-mappool']");
let btnMappools = document.querySelectorAll("img[id='btn-mappool']");
btnMappools.forEach(btnMappool => {

  btnMappool.addEventListener("click", function(e){
    let pool = btnMappool.getAttribute('data-pool')
    let targetMappool = document.querySelector("form[data-pool='"+pool+"']");
    //let targetMappool = document.getElementById("target-mappool");
    targetMappool.classList.toggle("js-toggle-mappool");
  });
})


