// IMPORTS
import axios from "axios";
import noUiSlider from "nouislider";
import { btnPools } from "./dropdownMappool"
//GLOBAL VARS
let btnMappools = document.querySelectorAll("img[id='btn-mappool']");
let sliderRank = document.getElementById('slider-rank');
const form = document.querySelector("form[id='poolset']")
const add = document.querySelector("form[id='add']")
let pools = document.querySelectorAll("form[id='pool']");
let delete_pools = document.querySelectorAll("button[id='form_delete']");
let add_map = document.querySelectorAll("input[id='form_addmap']")
let events = ['submit', 'change']
let delete_maps = document.querySelectorAll("button[class='d-flex delete-btn']");
let maps_tmp = document.querySelectorAll("input[class='map-link']");
let modes = document.querySelectorAll("select[class='select mode']");
let maps = []
maps_tmp.forEach(f => {
    maps.push([f, f.value])
})
let search_user = document.querySelector("input[class='search-user bg-transparent']")
let add_user = []


// FUNCTIONS
function makePostRequest(input, path){
    return axios.post(path, input)
}

function addMap(addmap){
    // add maps
    addmap.forEach(f => {
        /////////////////////// AJOUTER UNE MAP /////////////////////////////
        events.forEach(event => f.addEventListener(event, function(e){
            e.preventDefault();
            const data = new FormData(f.form)
            let request = makePostRequest(data, '/add_map').then( function(response){
                let result = response.data
                f.value = ""
                if (result[0] === 'false'){
                    f[0].value = f[1]
                }else{
                    // Construction de la map
                        let parent = f.parentElement.parentElement.parentElement.parentElement
                        let div0 = parent.parentElement
                        let first_div = document.createElement("div")
                        first_div.setAttribute('class',"d-flex block-mappool-data")
                        first_div.setAttribute('style',"background-image: url('"+ result.cover +"'); z-index: 2")
                        div0.appendChild(first_div)
                        div0.insertBefore(first_div, f.parentNode.parentNode.parentNode.parentNode)
                        ////////////////////////////////////////////////
                        let div1 = document.createElement("div")
                        div1.setAttribute('class',"d-flex first-line-data")
                        first_div.appendChild(div1)
                        /////////////////////////////
                        let div3 = document.createElement("div")
                        div3.setAttribute('class',"map-link-card")
                        div1.appendChild(div3)
                        ////////////////
                        let p1 = document.createElement("p")
                        p1.setAttribute('class',"map-link-text")
                        p1.textContent = "Map-Link"
                        div3.appendChild(p1)
                        ////////////////
                        let div4 = document.createElement("div")
                        div4.setAttribute('class',"d-flex map-link-edit")
                        div3.appendChild(div4)
                        ////////
                        let input1 = document.createElement("input")
                        input1.setAttribute('class',"map-link")
                        input1.setAttribute('poolid',result.poolid)
                        input1.value = result.url
                        div4.appendChild(input1)
                        /////////////////////////////
                        let select1 = document.createElement("select")
                        select1.setAttribute('class',"select mode")
                        select1.setAttribute('poolid',result.poolid)
                        select1.setAttribute('mapid',result.id)
                        div1.appendChild(select1)
                        //////////////// OPTIONS
                        let mods = ['NM', 'DT','HR','HD']
                        mods.forEach(f => {
                            let option = document.createElement("option")
                            option.setAttribute('value',f)
                            option.label = f
                            select1.appendChild(option)

                        })

                        /////////////////////////////
                        let button1 = document.createElement("button")
                        button1.setAttribute('class',"d-flex delete-btn")
                        button1.setAttribute('value',result.poolid + '_' + result.id)
                        button1.textContent = 'Delete'
                        div1.appendChild(button1)
                        ////////////////////////////////////////////////
                        let div2 = document.createElement("div")
                        div2.setAttribute('class',"d-flex second-line-data")
                        first_div.appendChild(div2)
                        /////////////////////////////
                        let span2 = document.createElement("span")
                        span2.setAttribute('class',"d-none d-md-flex map-title")
                        span2.textContent = result.name
                        div2.appendChild(span2)
                        /////////////////////////////
                        let span3 = document.createElement("span")
                        span3.setAttribute('class',"d-none d-md-flex map-author")
                        span3.textContent = result.creator
                        div2.appendChild(span3)
                        /////////////////////////////
                        ////////////////////////////////////////////////
                        let span1 = document.createElement("span")
                        span1.setAttribute('class',"d-none d-md-flex rating")
                        span1.textContent = ' CS: ' + result.cs + ' AR: ' + result.ar + ' OD: ' + result.accuracy + ' HP: ' + result.drain
                        first_div.appendChild(span1)

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
            console.log(data)
            let request = makePostRequest(data, '/replace_map').then(function (response){
                let request = response.data
                if (request[0] === 'false'){
                    f[0].value = f[1]
                }else{
                    console.log(request)
                    let elt = f[0].parentElement.parentElement.parentElement.parentElement
                    elt.setAttribute('style', 'background-image: url(\'' + request.cover + '\'); z-index: 2')
                    elt.querySelector("div[class='d-flex second-line-data']").querySelector("span[class='d-none d-md-flex map-title']").textContent = request.name
                    elt.querySelector("div[class='d-flex second-line-data']").querySelector("span[class='d-none d-md-flex map-author']").textContent = "mappée par " + request.creator
                    elt.querySelector("span[class='d-none d-md-flex rating']").textContent = "CS: " + request.cs + " AR: " + request.ar + " OD: " + request.accuracy + " HP: " + request.drain
                    f[0].value = request.url
                    elt.querySelector("div[class='d-flex first-line-data']").querySelector("button[class='d-flex delete-btn']").value = request.poolid + '_' + request.id
                    elt.querySelector("div[class='d-flex first-line-data']").querySelector("select[class='select mode']").setAttribute('poolid',request.poolid)
                    elt.querySelector("div[class='d-flex first-line-data']").querySelector("select[class='select mode']").setAttribute('mapid',request.id)




                    let delete_maps = document.querySelectorAll("button[class='d-flex delete-btn']");
                    let maps_tmp = document.querySelectorAll("input[class='map-link']");
                    let modes = document.querySelectorAll("select[class='select mode']");
                    let maps = []
                    maps_tmp.forEach(t => {
                        maps.push([t, t.value])
                    })
                    let add_map = document.querySelectorAll("input[id='form_addmap']")
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
    // ADD MAP
    addMap(add_map)
    //BTN POOLS
    btnPools(btnMappools)
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



    /////////////////////// AJOUTER UN MAPPOOL /////////////////////////////
    add.addEventListener('submit', function(e) {

        e.preventDefault();
        const data = new FormData(add)
        add[0].value = ''


        makePostRequest(data, '/add_mappool').then(function (response){
            let request = response.data
            let section = document.querySelector("section[id='target-mappool']")

            let form1 = document.createElement("form")
            form1.setAttribute('class', 'global-edit-mappool')
            form1.setAttribute('id', 'pool')
            form1.setAttribute('name', 'form')
            form1.setAttribute('method',"post")
            form1.setAttribute('data-pool',request['id'])


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
            img1.setAttribute('data-pool',request['id'])

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
            let button = document.createElement("button")
            button.setAttribute('class',"add-btn")
            button.setAttribute('type',"button")
            button.textContent = 'add'
            div10.appendChild(button)

            let p1 = document.createElement("p")
            p1.setAttribute('class',"map-link-text")
            p1.textContent = 'Map-Link'
            div11.appendChild(p1)
            let div12 = document.createElement("div")
            div12.setAttribute('class',"d-flex map-link-edit")
            div11.appendChild(div12)
            //addmap input
            let i2 = document.createElement("input") //input element, text
            i2.setAttribute('type', 'text')
            i2.setAttribute('placeholder', 'https://...')
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
                let btnMappools = document.querySelectorAll("img[id='btn-mappool']");
                btnPools(btnMappools)
                // ADD MAP
                let add_map = document.querySelectorAll("input[id='form_addmap']")
                addMap(add_map)
        }
        )

    })

    // CONTRIBUTOR MODAL PART //////

    // Search USERS
        /////////////////////// AJOUTER UNE MAP /////////////////////////////
        let modal_events = ['input']
        modal_events.forEach(event => search_user.addEventListener(event, function(e){
            e.preventDefault();
            const data = search_user.getAttribute('data-id') + "§" + search_user.value
            let request = makePostRequest(data, '/search_users').then( function(response){
                let result = response.data
                let oldcontent = document.querySelector("div[class='d-flex search-result-block scrollbar']")
                if (oldcontent != null){
                    oldcontent.remove()
                }


                let div0 = document.querySelector("div[class='contributor-container']")
                let first_div = document.createElement("div")
                first_div.setAttribute('class',"d-flex search-result-block scrollbar")
                div0.appendChild(first_div)

                result.forEach(function(f) {
                    //START
                    let div1 = document.createElement("div")
                    div1.setAttribute('class',"d-flex user-card")
                    div1.setAttribute('style',"background-image: url('/build/constellation.svg')")
                    first_div.appendChild(div1)

                    let img1 = document.createElement("img")
                    img1.setAttribute('class',"contributor-avatar")
                    img1.setAttribute('src', f['thumbnail'])
                    div1.appendChild(img1)

                    let img2 = document.createElement("img")
                    img2.setAttribute('class',"contributor-flag")
                    img2.setAttribute('src', "https://osu.ppy.sh/images/flags/" + f['country'] + ".png" )
                    div1.appendChild(img2)
                    let div2 = document.createElement("div")
                    div2.setAttribute('class',"d-flex contributors-data")
                    div1.appendChild(div2)
                    let span = document.createElement("div")
                    span.setAttribute('class',"contributor-name")
                    span.textContent = f['name']
                    div2.appendChild(span)
                    //END

                    div1.addEventListener('click', function(e){
                        if (add_user.includes(f) === false){
                            e.preventDefault()
                            add_user.push(f)
                            let div_add = document.querySelector("div[class='d-flex block-user-selected invisible-scrollbar']")
                            let div_add2 = document.createElement("div")
                            div_add2.setAttribute('class',"user-selected border-0")
                            div_add2.textContent = f['name']
                            div_add.appendChild(div_add2)

                            let div_add3 = document.createElement("button")
                            div_add3.setAttribute('class',"unselected-btn border-0 bg-transparent")
                            div_add3.setAttribute('type',"button")
                            div_add2.appendChild(div_add3)

                            const xmlns = "http://www.w3.org/2000/svg";
                            let svg = document.createElementNS(xmlns, 'svg');
                            svg.setAttributeNS(null,'class',"delete")
                            svg.setAttributeNS(null,'height',"16")
                            svg.setAttributeNS(null,'width',"16")
                            svg.setAttributeNS(null,'viewbox',"0 0 16 16")
                            svg.setAttributeNS(null,'fill',"none")
                            div_add3.appendChild(svg)
                            let path = document.createElementNS(xmlns, 'path');
                            path.setAttributeNS(null,'fill-rule',"evenodd")
                            path.setAttributeNS(null,'clip-rule',"evenodd")
                            path.setAttributeNS(null,'d',"M7.99935 15.3327C3.94926 15.3327 0.666016 12.0494 0.666016 7.99935C0.666016 3.94926 3.94926 0.666016 7.99935 0.666016C12.0494 0.666016 15.3327 3.94926 15.3327 7.99935C15.3327 12.0494 12.0494 15.3327 7.99935 15.3327ZM7.99988 13.9999C11.3136 13.9999 13.9999 11.3136 13.9999 7.99988C13.9999 4.68617 11.3136 1.99988 7.99988 1.99988C4.68617 1.99988 1.99988 4.68617 1.99988 7.99988C1.99988 11.3136 4.68617 13.9999 7.99988 13.9999ZM4.66602 7.33215V8.66549H11.3327V7.33215H4.66602Z")
                            path.setAttributeNS(null,'fill',"#C60000")
                            svg.appendChild(path)

                            div_add3.addEventListener('click', function(e){
                                e.preventDefault()
                                add_user = add_user.filter(e => e !== f)
                                div_add2.remove()
                            })
                        }


                    })
                    let confirm_add_users = document.querySelector("button[class='add-btnERROR']")
                    confirm_add_users.addEventListener('click', function(e){
                        console.log('test')
                        e.preventDefault();
                        const data = {'id':confirm_add_users.getAttribute('data-id'), 'users': add_user }
                        let request = makePostRequest(data, '/collection/add_contributors').then( function(response){
                            let result = response.data
                        })})
                })

            })
        }))

})





