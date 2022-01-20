// IMPORTS
import axios from "axios";
// Global vars
let delete_collections = document.querySelectorAll("a[class='btn-delete']");
let accept_buttons = document.querySelectorAll("button[class='btn btn-success']");
let decline_buttons = document.querySelectorAll("button[class='btn btn-danger']");
// Functions
function makePostRequest(input, path){
    return axios.post(path, input)
}
function eventInvitations(accepts, declines) {
    // Accepts
    accepts.forEach(f => {
        f.addEventListener('click', function (e) {
            e.preventDefault();
            f.parentElement.parentElement.parentElement.remove();
            const data = f.getAttribute('data-id');
            let request = makePostRequest(data, '/collection/accept_invitation')
        })
    })
    // Declines
    declines.forEach(f => {
        f.addEventListener('click', function (e) {
            e.preventDefault();
            f.parentElement.parentElement.parentElement.remove();
            const data = f.getAttribute('data-id');
            let request = makePostRequest(data, '/collection/decline_invitation')
        })
    })
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
    eventInvitations(accept_buttons, decline_buttons)
})