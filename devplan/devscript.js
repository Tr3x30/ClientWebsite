document.addEventListener('DOMContentLoaded', () => {
    const pages = document.querySelectorAll('.page');

    pages.forEach(page => {
        page.addEventListener('click', () => {
            pages.forEach(p => p.classList.remove('active'));
            page.classList.add('active');
        });
    });
});