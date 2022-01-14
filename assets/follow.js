// IMPORTS
import axios from "axios";
// Global vars
let follows = document.querySelectorAll("span[class='mappool-name invisible-scrollbar']");

// Functions
function makePostRequest(input, path){
    return axios.post(path, input)
}

function eventFollow(follows) {

    // follow pool
    follows.forEach(f => {
        let j1 = f.querySelector("a[class='show no-transition']")
        j1.addEventListener('click', function (e) {
            e.preventDefault();
            const data = j1.getAttribute('data-pool');
            let request = makePostRequest(data, '/follow_pool')
        })

        let j2 = f.querySelector("a[class='collapse no-transition']")
        j2.addEventListener('click', function (e) {
            e.preventDefault();
            const data = j2.getAttribute('data-pool');
            let request = makePostRequest(data, '/unfollow_pool')
        })
    })

}
//DOM
document.addEventListener('DOMContentLoaded', function() {
    eventFollow(follows)
})