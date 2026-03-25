const balls = document.querySelectorAll('.ball');

document.addEventListener('mousemove', (e) => {
    const x = e.clientX / window.innerWidth - 0.5;
    const y = e.clientY / window.innerHeight - 0.5;

    balls.forEach((ball, index) => {
        const speed = (index + 1) * 20;

        // movement
        ball.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
    });
});

const title = document.getElementById('title');
const desc = document.getElementById('desc');

balls.forEach(ball => {
    ball.addEventListener('click', () => {

        // reset all
        balls.forEach(b => {
            b.classList.remove('active');
            b.classList.add('inactive');
        });

        // activate clicked one
        ball.classList.add('active');
        ball.classList.remove('inactive');

        // update info
        title.textContent = ball.dataset.name;
        desc.textContent = ball.dataset.desc;
    });
});