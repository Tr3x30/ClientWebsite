const container = document.getElementById('comment');

const sponsors = [
    {
        name: "Sable Metals",
        desc: "Sable Metals is a company that provides metal materials for our team, and they are also a local company in our city.",
        img: "images/Sable Metal.jpg",
        link: "https://sablemetal.com/"
    },
    {
        name: "Putney's Auto Tech Center",
        desc: "Putney's Auto Tech Center is a local auto repair shop that has been supporting our team for several years.",
        img: "images/cropped-PUTNEYS-AUTO-TECH-CENTRE-2-300x300.png",
        link: "https://putneys.ca/"
    },
    {
        name: "CIC",
        desc: "Columbia International College is an educational institution that has supported our team with resources and opportunities.",
        img: "images/CIC.png",
        link: "https://www.cic-totalcare.com/"
    },
    {
        name: "Team: 5406 Celt-X",
        desc: "Team 5406:Celt-X is a robotics team that has collaborated with us on various projects.",
        img: "images/Celt-X.png",
        link: "https://www.5406.ca/"
    },
    {
        name: "Team:3161: Tronic Titans",
        desc: "Team 3161:Tronic Titans is a robotics team that has supported our team with various initiatives.",
        img: "images/team3161_logo.jpg",
        link: "https://team3161.ca/"
    },
    {
        name: "Urewheels",
        desc: "Urewheels is a company that has provided us with essential equipment and support.",
        img: "images/UreWheels.png",
        link: "https://www.urewheels.com/"
    },
    {
        name: "Zero Badminton",
        desc: "Prove a web of 9062",
        img: "images/zero_logo.jpg",
        link: "https://www.youtube.com/@2lov_hong"
    }
];


let targetX = 0;
let targetY = 0;
let currentX = 0;
let currentY = 0;

document.addEventListener('mousemove', (e) => {
    targetX = (e.clientX / window.innerWidth - 0.5) * 300;
    targetY = (e.clientY / window.innerHeight - 0.5) * 300;


});

function animate() {
    currentX += (targetX - currentX) * 0.05;
    currentY += (targetY - currentY) * 0.05;

    container.style.transform = 
        `translate(${currentX}px, ${currentY}px)`;

    requestAnimationFrame(animate);
}

animate();

const TOTAL = 60;

for (let i = 0; i < TOTAL; i++) {
    const s = sponsors[Math.floor(Math.random() * sponsors.length)];

    const ball = document.createElement('div');
    ball.classList.add('ball');

    ball.innerHTML = `
        <div class = "inner">
            <div class = "front">
                <img src = "${s.img}">
            </div> 
        
            <div class = "back">
                <h3>${s.name}</h3>
                <p>${s.desc}</p>
                <a href="${s.link}" target="_blank">Visit</a>
            </div>
    </div>

    `;

    ball.style.top = Math.random() * 200 + "%";
    ball.style.left = Math.random() * 200 + "%";

    ball.addEventListener('click', () => {

        document.querySelectorAll(".ball").forEach(b => {
            b.classList.remove("active");
            b.style.zIndex = 1;
        });

        ball.classList.add('active');
        ball.style.zIndex = 100;
    });

    container.appendChild(ball);
}