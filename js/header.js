// Author: Trevor Goff
// Date: Mar 11-Apr 19
// Description: Resize image in header to take up same vertical space as the buttons.

// Calculate vertical space and set image to that size.F
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
