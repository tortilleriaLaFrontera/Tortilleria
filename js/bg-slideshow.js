//imagenes
const bgimg = [
    'img/bg/bg1.jpg',
    'img/bg/bg2.jpg',
    'img/bg/bg3.jpg',
    'img/bg/bg4.jpg'
];

function bgrandom() {
    const elmain = document.querySelector('main');

    if (elmain) {
        const randbgimg = bgimg[Math.floor(Math.random() * bgimg.length)];
    

        //aplicar bg
        elmain.style.backgroundImage = `url('${randbgimg}')`;
        elmain.style.backgroundSize = 'cover';
        elmain.style.backgroundPosition = 'center';
    }
}

window.onload = bgrandom;