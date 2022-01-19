// IMPORTS
import axios from "axios";
// Global vars
let updateScore = document.querySelector("a[class='score-btn btn']");
let loading = document.querySelector("div[id='loader']");
// Functions
function makePostRequest(input, path){
    return axios.post(path, input)
}



//DOM
document.addEventListener('DOMContentLoaded', function() {
        updateScore.addEventListener('click', function (e) {
            e.preventDefault()
            const data = {}
            loading.classList.toggle('d-none')
            let request = makePostRequest(data, '/save_scores').then(function(response){
                loading.classList.toggle('d-none')
            })
        })
})