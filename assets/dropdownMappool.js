
//let btnMappools = document.querySelectorAll("div[data-id='target-mappool']");

export function btnPools(btnMappools){
  console.log(btnMappools)
  btnMappools.forEach(btnMappool => {
    const old_element = btnMappool
    const new_element = old_element.cloneNode(true);
    old_element.parentNode.replaceChild(new_element, old_element);

    new_element.addEventListener("click", function(e){
      console.log('yoo')
      let pool = new_element.getAttribute('data-pool')
      let targetMappool = document.querySelector("form[data-pool='"+pool+"']");
      console.log(pool)
      console.log(targetMappool)
      //let targetMappool = document.getElementById("target-mappool");
      targetMappool.classList.toggle("js-toggle-mappool");
    });
  })
}



