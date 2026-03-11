function sizeLogoToNav() {
    const nav = document.getElementById('flexnav');
    const logoImg = document.querySelector('#header #logo img');

    if (!nav || !logoImg) return;

    const navHeight = nav.getBoundingClientRect().height;
    logoImg.style.height = `${navHeight}px`;
    logoImg.style.width = 'auto';
}

window.addEventListener('resize', sizeLogoToNav);
window.addEventListener('load', sizeLogoToNav);
