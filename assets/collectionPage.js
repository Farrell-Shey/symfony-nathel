// IMPORTS

// Global vars
import axios from "axios";

let btnMappools = document.querySelectorAll("button[id='dropdown-button']");
let btnCol =document.querySelector("button[class='d-none d-sm-flex following-btn']");
// Functions
function makePostRequest(input, path){
    return axios.post(path, input)
}
//DOM
document.addEventListener('DOMContentLoaded', function() {
    // Toggle dropdown pools
    btnMappools.forEach( f => {
        f.addEventListener('click', () => {
            f.parentElement.parentElement.parentElement.parentElement.querySelector("div[id='flush-collapse']").classList.toggle('show')
        })
        }
    )

    btnCol.addEventListener('click', function (e) {
        e.preventDefault();
        const data = btnCol.getAttribute('data-col');
        let request = makePostRequest(data, '/collection/followall')
    })

})