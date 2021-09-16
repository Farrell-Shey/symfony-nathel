/*
* yarn add nouislider
* yarn add wnumb
* */

import wNumb from "wnumb";
import noUiSlider from "nouislider";

let sliderRank = document.getElementById('slider-rank');

let sliderRatting = document.getElementById('slider-ratting');

noUiSlider.create(sliderRank, {
    start: [1, 500000],
    connect: true,
    direction: 'rtl',
    range: {
        'min': [1],
        '15%': [1000, 1000],
        '35%': [10000, 10000],
        '70%': [100000, 100000],
        'max': 500000
    },
    pips: {
        density: 3
    },
    tooltips: [true, true],
    format: wNumb({
        decimals: 0,
        thousand: ' ',
        prefix: '#'
    })
});

// noUiSlider.create(sliderRatting, {
//     start: [4, 7],
//     connect: true,
//     range: {
//         'min': 0,
//         'max': 10
//     },
//     tooltips: [true, true],
//     format: wNumb({
//         decimals: 1,
//         suffix: ' ★'
//     })
// });

const shows = document.querySelectorAll('.modal-show');



function delay(n) {
    return new Promise(function (resolve) {
        setTimeout(resolve, n * 1000);
    });
}

async function show() {

    let popUp = document.getElementById('pop-up');
    let body = document.getElementsByTagName("body")

    if (popUp.classList.contains('d-none')) {

        popUp.classList.toggle('d-none')
        await delay(.1);
        popUp.classList.toggle('show')
    } else {
        popUp.classList.toggle('show')

        await delay(.5);
        popUp.classList.toggle('d-none')
    }

}

shows.forEach(el => el.addEventListener('click', show));
sliderRank = document.getElementById('slider_rank');
