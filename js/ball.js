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
}