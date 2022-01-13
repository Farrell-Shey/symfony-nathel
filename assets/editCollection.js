// IMPORTS
import axios from "axios";
import noUiSlider from "nouislider";
//GLOBAL VARS
let sliderRank = document.getElementById('slider-rank');
const form = document.querySelector("form[id='poolset']")
const add = document.querySelector("form[id='add']")
let pools = document.querySelectorAll("form[id='pool']");
let delete_pools = document.querySelectorAll("button[id='form_delete']");
const add_map = document.querySelectorAll("input[id='form_addmap']")
let events = ['submit', 'change']
let delete_maps = document.querySelectorAll("button[class='d-flex delete-btn']");
let maps_tmp = document.querySelectorAll("input[class='map-link']");
let modes = document.querySelectorAll("select[class='select mode']");
let maps = []
maps_tmp.forEach(f => {
    maps.push([f, f.value])
})
// FUNCTIONS
function makePostRequest(input, path){
    return axios.post(path, input)
}

function eventMaps(delete_maps, maps, modes){

    // delete pool
    delete_maps.forEach(f => {
        f.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('test')
            f.parentElement.parentElement.remove();
            const data = f.value;
            let request = makePostRequest(data, '/delete_map')
        })
    })

    modes.forEach(f => {
        /////////////////////// Refresh le MODE /////////////////////////////
        events.forEach(event => f.addEventListener(event, function(e){
            e.preventDefault();
            let pool_id = f.getAttribute('poolid');
            let map_id = f.getAttribute('mapid');
            let value = f.value
            const data = pool_id + '§' + value + '§' + map_id
            let request = makePostRequest(data, '/refresh_mode')

        }))
      })

    maps.forEach(f => {
        /////////////////////// Refresh UNE MAP /////////////////////////////
        events.forEach(event => f[0].addEventListener(event, function(e){
            e.preventDefault();
            let id = f[0].getAttribute('poolid');
            let link = f[0].value;
            let value = f[1];

            const data = id + '§' + link + '§' + value;
            let request = makePostRequest(data, '/replace_map').then(function (response){
                let request = response.data
                if (request[0] === 'false'){
                    f[0].value = f[1]
                }else{
                    let elt = f[0].parentElement.parentElement.parentElement.parentElement
                    elt.setAttribute('style', 'background-image: url(\'' + request.cover + '\'); z-index: 2')
                    elt.querySelector("div[class='d-flex second-line-data']").querySelector("span[class='d-none d-md-flex map-title']").textContent = request.name
                    elt.querySelector("div[class='d-flex second-line-data']").querySelector("span[class='d-none d-md-flex map-author']").textContent = "mappée par " + request.creator
                    elt.querySelector("span[class='d-none d-md-flex rating']").textContent = "CS: " + request.cs + " AR: " + request.ar + " OD: " + request.accuracy + " HP: " + request.drain
                    f[0].value = request.url



                    let delete_maps = document.querySelectorAll("button[class='d-flex delete-btn']");
                    let maps_tmp = document.querySelectorAll("input[class='map-link']");
                    let modes = document.querySelectorAll("select[class='select mode']");
                    let maps = []
                    maps_tmp.forEach(t => {
                        maps.push([t, t.value])
                    })
                    eventMaps(delete_maps, maps, modes)
                }

            })

        }))

    })

}

function eventPools(pools, delete_pools){
    //Save pool title

    pools.forEach(f => {
        events.forEach(event => f.querySelector("div[class='d-flex block-edit-mappool']")
            .querySelector("div[class='d-flex block-mappool-title filter']")
            .querySelector("div[class='d-flex mx-auto global-card-title']")
            .addEventListener(event, function(e){
                e.preventDefault();
                const data = new FormData(f)
                let request = makePostRequest(data, '/save_mappools')

        }))

    })
    // delete pool
    delete_pools.forEach(f => {
        f.addEventListener('click', function(e) {

            e.preventDefault();
            const data = {'delete':true, 'id' : f.form.querySelector("input[id='form_id']").value};
            let request = makePostRequest(data, '/delete_pool')
            f.form.remove()
        })
    })
}

//DOM
document.addEventListener('DOMContentLoaded', function() {
    /////////////////////// POOLSET DATA /////////////////////////////

    sliderRank.noUiSlider.on('update', function() {


        const data = new FormData(form)
        let request = makePostRequest(data, '/collection_edit_save')
    })


    events.forEach(event => form.addEventListener(event, function(e){
        e.preventDefault();
        const data = new FormData(form)
        let request = makePostRequest(data, '/collection_edit_save').then( function (response) {
            if (response.data['image'] != null){
                let url = "background-image: url('/"+ response.data['image'] +"')"
                document.querySelector("div[class='bg-collection']").setAttribute('style', url)
            }

        })
    }))


    /////////////////////// MAPPOOLS & MAPS DATA /////////////////////////////

    //Save pool title
    eventPools(pools, delete_pools)

    // maps changes
    eventMaps(delete_maps, maps, modes)

    add_map.forEach(f => {
        /////////////////////// AJOUTER UNE MAP /////////////////////////////
        events.forEach(event => f.addEventListener(event, function(e){
            e.preventDefault();
            const data = new FormData(f.form)
            let request = makePostRequest(data, '/add_map').then( function(response){
                f.value = ""
            })
        }))
    })


    /////////////////////// AJOUTER UN MAPPOOL /////////////////////////////
    add.addEventListener('submit', function(e) {

        e.preventDefault();
        const data = new FormData(add)
        add[0].value = ''


        makePostRequest(data, '/add_mappool').then(function (response){
            let request = response.data
            let section = document.querySelector("section[id='target-mappool']")

            let form1 = document.createElement("form")
            form1.setAttribute('id', 'pool')
            form1.setAttribute('name', 'form')
            form1.setAttribute('method',"post")


            section.insertBefore(form1, add)

            let div1 = document.createElement("div")
            div1.setAttribute('class',"d-flex block-edit-mappool")
            form1.appendChild(div1)
            let div2 = document.createElement("div")
            div2.setAttribute('class',"d-flex block-mappool-title filter")
            div2.setAttribute('style',"background-image: url('https://assets.ppy.sh/beatmaps/847415/covers/cover.jpg')")

            div1.appendChild(div2)
            let div3 = document.createElement("div")
            div3.setAttribute('class',"block-btn")
            div2.appendChild(div3)
            //delete button
            let b1 = document.createElement("button") //input element, text
            b1.setAttribute('type', 'button')
            b1.textContent = 'Delete'
            b1.setAttribute('id','form_delete')
            b1.setAttribute('name','form[delete]')
            b1.setAttribute('class', 'delete-btn')
            b1.setAttribute('name', 'form[delete]')

            div3.appendChild(b1)
            let div4 = document.createElement("div")
            div4.setAttribute('class',"d-flex mx-auto global-card-title")
            div2.appendChild(div4)
            let span1 = document.createElement("span")
            span1.setAttribute('class',"mappool-title")
            span1.textContent = 'Mappool title'
            div4.appendChild(span1)
            let div5 = document.createElement("div")
            div5.setAttribute('class',"d-flex mappool-title-edit")
            div4.appendChild(div5)
            //title input
            let i1 = document.createElement("input") //input element, text

            i1.setAttribute('type', 'text')
            i1.setAttribute('value',request['title'])
            i1.setAttribute('id','form_title')
            i1.setAttribute('name','form[title]')

            i1.setAttribute('class','mappool-input-title')
            form1.appendChild(i1)
            div5.appendChild(i1)

            let div7 = document.createElement("div")
            div7.setAttribute('class',"d-flex mx-auto dropdown-block")
            div2.appendChild(div7)
            let img1 = document.createElement("img")
            img1.setAttribute('class',"dropdown")
            img1.setAttribute('id',"btn-mappool")
            img1.setAttribute('src',"/build/dropdown-down.svg")
            div7.appendChild(img1)


            let div8 = document.createElement("div")
            div8.setAttribute('class',"d-flex block-dropdown-data")
            div1.appendChild(div8)
            let div9 = document.createElement("div")
            div9.setAttribute('class',"d-flex block-mappool-data")
            div9.setAttribute('style',"background-image: url('https://assets.ppy.sh/beatmaps/847415/covers/cover.jpg'); z-index: 1")
            div8.appendChild(div9)
            let div10 = document.createElement("div")
            div10.setAttribute('class',"d-flex first-line-data")
            div9.appendChild(div10)
            let div11 = document.createElement("div")
            div11.setAttribute('class',"map-link-card")
            div10.appendChild(div11)
            let p1 = document.createElement("p")
            p1.setAttribute('class',"map-link-text")
            p1.setAttribute('value',"Map-link")
            div11.appendChild(p1)
            let div12 = document.createElement("div")
            div12.setAttribute('class',"d-flex map-link-edit")
            div11.appendChild(div12)
            //addmap input
            let i2 = document.createElement("input") //input element, text
            i2.setAttribute('label', 'Map Link')
            i2.setAttribute('type', 'text')
            i2.setAttribute('placeholder', 'https://...')
            i2.setAttribute('value',"https://...")
            i2.setAttribute('name','form[addmap]')
            i2.setAttribute('id','form_addmap')
            i2.setAttribute('class','map-link')
            div12.appendChild(i2)
            // ID hidden
            let h = document.createElement("input") //input element, text
            h.setAttribute('type', 'hidden')
            h.setAttribute('value',request['id'])
            h.setAttribute('name','form[id]')
            h.setAttribute('id','form_id')
            div12.appendChild(h)



        }).then( (e) => {
                pools = document.querySelectorAll("form[id='pool']")
                delete_pools = document.querySelectorAll("button[id='form_delete']")
                eventPools(pools, delete_pools)
        }
        )

    })
})





