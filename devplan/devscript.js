// Author: Trevor Goff
// Date: Apr 2-19
// Description: JS to switch tabs in devplan.
document.addEventListener('DOMContentLoaded', () => {
    const pages = document.querySelectorAll('.page');
    const sections = document.querySelectorAll('.section');

    // Add a button that activates the current tab. Applies to all pages in the devplan.
    pages.forEach(page => {
        page.addEventListener('click', () => {
            pages.forEach(p => p.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));

            page.classList.add('active');

            const target = page.dataset.target;
            document.getElementById(target).classList.add('active');
        });
    });
});