document.addEventListener("DOMContentLoaded", () => {

    const container = document.getElementById("comment");

    /* =========================
       SPONSOR DATA
    ========================= */

    const sponsors = [
        {
            name: "Sable Metals",
            desc: "Metal supplier supporting our robotics team.",
            img: "images/Sable_Metal.jpg",
            link: "https://sablemetal.com/"
        },
        {
            name: "Putney's Auto Tech",
            desc: "Local auto repair shop supporting our team.",
            img: "images/PUTNEYS.png",
            link: "https://putneys.ca/"
        },
        {
            name: "CIC",
            desc: "Columbia International College supporter.",
            img: "images/CIC.png",
            link: "https://www.cic-totalcare.com/"
        },
        {
            name: "Team 5406 Celt-X",
            desc: "Robotics collaboration team.",
            img: "images/Celt-X.png",
            link: "https://www.5406.ca/"
        },
        {
            name: "Team 3161 Titans",
            desc: "Robotics partner team.",
            img: "images/team3161_logo.jpg",
            link: "https://team3161.ca/"
        },
        {
            name: "Urewheels",
            desc: "Equipment sponsor.",
            img: "images/UreWheels.png",
            link: "https://www.urewheels.com/"
        },
        {
            name: "Zero Badminton",
            desc: "Community supporter.",
            img: "images/zero_logo.jpg",
            link: "https://www.youtube.com/@2lov_hong"
        }
    ];


    /* =========================
       MOUSE PARALLAX
    ========================= */

    let targetX = 0;
    let targetY = 0;
    let currentX = 0;
    let currentY = 0;

    document.addEventListener("mousemove", e => {

        targetX = (e.clientX / window.innerWidth - 0.5) * 200;
        targetY = (e.clientY / window.innerHeight - 0.5) * 200;

    });

    function animate() {

        currentX += (targetX - currentX) * 0.05;
        currentY += (targetY - currentY) * 0.05;

        container.style.transform =
            `translate(${currentX}px, ${currentY}px)`;

        requestAnimationFrame(animate);
    }


    /* =========================
       CREATE BALLS
    ========================= */

    const TOTAL = 40;

    for (let i = 0; i < TOTAL; i++) {

        const s = sponsors[Math.floor(Math.random() * sponsors.length)];

        const ball = document.createElement("div");
        ball.className = "ball";

        ball.innerHTML = `
<div class="inner">
    <div class="front">
        <img src="${s.img}">
    </div>

    <div class="back">
        <h3>${s.name}</h3>
        <p>${s.desc}</p>
        <a href="${s.link}" target="_blank">Visit</a>
    </div>
</div>
`;

        ball.style.top = Math.random() * 90 + "%";
        ball.style.left = Math.random() * 90 + "%";

        /* CLICK EVENT */

        ball.addEventListener("click", () => {

            document.querySelectorAll(".ball").forEach(b => {
                b.classList.remove("active");
                b.style.zIndex = 1;
            });

            ball.classList.add("active");
            ball.style.zIndex = 100;

        });

        container.appendChild(ball);
    }

    /* START ANIMATION */

    animate();

});