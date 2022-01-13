// IMPORTS
import axios from "axios";
// Global vars
let delete_collections = document.querySelectorAll("a[class='btn-delete']");
// Functions
function makePostRequest(input, path){
    return axios.post(path, input)
}
function eventMaps(delete_collections) {

    // delete pool
    delete_collections.forEach(f => {
        f.addEventListener('click', function (e) {
            e.preventDefault();
            f.parentElement.parentElement.remove();
            const data = f.getAttribute('data-pool');
            let request = makePostRequest(data, '/collection/delete_collection')
        })
    })
}
//DOM
document.addEventListener('DOMContentLoaded', function() {
    eventMaps(delete_collections)
})