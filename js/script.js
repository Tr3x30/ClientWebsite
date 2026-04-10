/* =========================
   CANVAS SETUP
========================= */

const canvas = document.getElementById("animationCanvas");
const ctx = canvas.getContext("2d");

function resizeCanvas() {
    canvas.width = canvas.clientWidth;
    canvas.height = canvas.clientWidth * 0.5; // 2:1 ratio
}

resizeCanvas();
window.addEventListener("resize", resizeCanvas);


/* =========================
   CLASSES
========================= */

class Ball {
    constructor(x, y, xSpeed, ySpeed) {
        this.x = x;
        this.y = y;
        this.xSpeed = xSpeed;
        this.ySpeed = ySpeed;
        this.radius = 5;
        this.red = 255;
        this.green = 0;
        this.blue = 0;
    }

    move() {
        this.x += this.xSpeed;
        this.y += this.ySpeed;
    }

    draw() {
        ctx.fillStyle = `rgb(${this.red},${this.green},${this.blue})`;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        ctx.fill();
    }
}

class Text {
    constructor(text, color, x, y) {
        this.text = text;
        this.color = color;
        this.x = x;
        this.y = y;
    }

    draw() {
        ctx.fillStyle = this.color;
        ctx.font = "bold 48px Arial";
        ctx.textAlign = "center";
        ctx.fillText(this.text, this.x, this.y);
    }
}


/* =========================
   UTIL FUNCTION
========================= */

function randR(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}


/* =========================
   MAIN PROGRAM
========================= */

window.addEventListener("load", () => {

    const mouseRadius = 50;
    let mouseX = -100;
    let mouseY = -100;

    /* ---------- BALLS ---------- */
    let balls = [];

    function createBalls() {
        balls = [];

        for (let i = 0; i < 300; i++) {
            balls.push(
                new Ball(
                    randR(20, canvas.width - 20),
                    randR(20, canvas.height - 20),
                    randR(-10, 10) / 10,
                    randR(-10, 10) / 10
                )
            );
        }
    }

    createBalls();

    window.addEventListener("resize", () => {
        resizeCanvas();
        createBalls(); // regenerate balls to fit screen
    });

    /* ---------- TEXT ---------- */
    const texts = [
        new Text("9062", "#333333", canvas.width/2 + 3, 53),
        new Text("Critical Circuits", "#333333", canvas.width/2 + 3, 103),
        new Text("9062", "#8888FF", canvas.width/2, 50),
        new Text("Critical Circuits", "#8888FF", canvas.width/2, 100)
    ];

    /* ---------- IMAGE ---------- */
    const img = new Image();
    img.src = "images/logo.jpg";

    /* ---------- MOUSE TRACK ---------- */
    canvas.addEventListener("mousemove", e => {
        const rect = canvas.getBoundingClientRect();
        mouseX = e.clientX - rect.left;
        mouseY = e.clientY - rect.top;
    });


    /* =========================
       ANIMATION LOOP
    ========================= */

    function updateAnimation() {

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // draw logo
        ctx.drawImage(img, 25, 25, 75, 75);

        for (let ball of balls) {

            /* --- mouse bounce --- */
            const dx = ball.x - mouseX;
            const dy = ball.y - mouseY;
            const dist = Math.sqrt(dx*dx + dy*dy);

            if (dist < mouseRadius + ball.radius) {

                const nx = dx / dist;
                const ny = dy / dist;

                const dot = ball.xSpeed * nx + ball.ySpeed * ny;

                ball.xSpeed -= 2 * dot * nx;
                ball.ySpeed -= 2 * dot * ny;
            }

            /* --- wall bounce --- */
            if (ball.x >= canvas.width - ball.radius || ball.x <= ball.radius)
                ball.xSpeed *= -1;

            if (ball.y >= canvas.height - ball.radius || ball.y <= ball.radius)
                ball.ySpeed *= -1;

            ball.move();
            ball.draw();
        }

        for (let t of texts) {
            t.draw();
        }

        requestAnimationFrame(updateAnimation);
    }

    updateAnimation();
});