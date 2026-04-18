document.addEventListener('DOMContentLoaded', () => {
    const pages = document.querySelectorAll('.page');
    const sections = document.querySelectorAll('.section');

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