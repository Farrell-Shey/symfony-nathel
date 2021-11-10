
console.log('bruh')
    //const axios = require("axios");
    const form = document.querySelector("form[id='poolset']")
    const add = document.querySelector("form[id='add']")
    const pools = document.querySelectorAll("form[id='pool']")
    const button = document.querySelector("button[id='test']")
    const add_map = document.querySelectorAll("form[id='add_map']")


    document.addEventListener('DOMContentLoaded', function() {
    function makePostRequest(input, path){
        return axios.post(path, input)
    }


    /////////////////////// POOLSET DATA /////////////////////////////

    form.addEventListener('submit', function(e) {
    e.preventDefault();
    const data = new FormData(form)


    let request = makePostRequest(data, '/edit_save')
    // EN JS, il reste à faire l'event qui RENVOIE la requête
    // scrollable à true signifie qu'il reste pools à afficher
    const { rank_min, rank_max, title, std,
    mania, taiko, ctb } = request
    const form_title = document.getElementById('form_title')
    const form_rank_min = document.getElementById('form_rank_min')
    const form_rank_max = document.getElementById('form_rank_max')
    const form_range_min = document.getElementById('form_range_min')
    const form_range_max = document.getElementById('form_range_max')
    const form_std = document.getElementById('form_std')
    const form_mania = document.getElementById('form_mania')
    const form_taiko = document.getElementById('form_taiko')
    const form_ctb = document.getElementById('form_ctb')
    const form_image = document.getElementById('form_image')
    form_image.value = ""
    //b.setAttribute("value", "helloButton");
})


    /////////////////////// MAPPOOLS & MAPS DATA /////////////////////////////

    button.addEventListener('click', function(e) {
    e.preventDefault();

    pools.forEach(f => {

    const data = new FormData(f)
    let request = makePostRequest(data, '/save_mappools')

})
})


    add_map.forEach(f => {
    /////////////////////// AJOUTER UNE MAP /////////////////////////////
    f.addEventListener('submit', function(e) {
    e.preventDefault();

    const data = new FormData(f)
    console.log(data)
    let request = makePostRequest(data, '/add_map')

})
})


    /////////////////////// AJOUTER UN MAPPOOL /////////////////////////////
    add.addEventListener('submit', function(e) {
    e.preventDefault();
    const data = new FormData(add)



    makePostRequest(data, 'add_mappool').then(function (response){
    let request = response.data

    let f = document.createElement("form")
    f.setAttribute('method',"post")
    f.setAttribute('id', 'pool')

    let i = document.createElement("input") //input element, text
    i.setAttribute('label', 'Mappool Title')
    i.setAttribute('type', 'text')
    i.setAttribute('value',request['title'])
    i.setAttribute('id','form_title')

    let j = document.createElement("input") //input element, text
    j.setAttribute('type', 'checkbox')
    i.setAttribute('label', 'Delete')
    j.setAttribute('id','form_delete')

    let h = document.createElement("input") //input element, text
    h.setAttribute('type', 'hidden')
    h.setAttribute('value',request['id'])
    h.setAttribute('id','form_id')

    f.appendChild(i)
    f.appendChild(j)
    f.appendChild(h)

    document.getElementsByTagName('main')[0].appendChild(f);
    console.log(f)
})


})
})
