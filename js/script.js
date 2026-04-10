window.addEventListener("load", () => {

    const canvas = document.getElementById("animationCanvas");
    const ctx = canvas.getContext("2d");

    function resizeCanvas() {
        canvas.width = canvas.clientWidth;
        canvas.height = 400;
    }

    resizeCanvas();
    window.addEventListener("resize", resizeCanvas);

    /* ================= BALL ================= */

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

    /* ================= TEXT ================= */

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

    /* ================= RANDOM ================= */

    function randR(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    /* ================= BALLS ================= */

    let balls = [];

    function createBalls() {
        balls = [];

        for (let i = 0; i < 250; i++) {
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

    /* ================= TEXT ================= */

    function createTexts() {
        return [
            new Text("9062", "#333333", canvas.width / 2 + 2, 53),
            new Text("Critical Circuits", "#333333", canvas.width / 2 + 2, 103),
            new Text("9062", "#8888FF", canvas.width / 2, 50),
            new Text("Critical Circuits", "#8888FF", canvas.width / 2, 100)
        ];
    }

    let texts = createTexts();

    /* ================= IMAGE ================= */

    const img = new Image();
    img.src = "images/logo.jpg";
    let imgLoaded = false;
    img.onload = () => imgLoaded = true;

    /* ================= MOUSE ================= */

    let mouseX = -100;
    let mouseY = -100;
    const mouseRadius = 50;

    canvas.addEventListener("mousemove", (e) => {
        const rect = canvas.getBoundingClientRect();
        mouseX = e.clientX - rect.left;
        mouseY = e.clientY - rect.top;
    });

    /* ================= ANIMATION ================= */

    function updateAnimation() {

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (imgLoaded) {
            ctx.drawImage(img, 25, 25, 75, 75);
        }

        for (let ball of balls) {

            const dx = ball.x - mouseX;
            const dy = ball.y - mouseY;
            const dist = Math.sqrt(dx * dx + dy * dy);

            if (dist < mouseRadius + ball.radius && dist > 0) {
                const nx = dx / dist;
                const ny = dy / dist;

                const dot = ball.xSpeed * nx + ball.ySpeed * ny;

                ball.xSpeed -= 2 * dot * nx;
                ball.ySpeed -= 2 * dot * ny;
            }

            if (ball.x <= ball.radius || ball.x >= canvas.width - ball.radius)
                ball.xSpeed *= -1;

            if (ball.y <= ball.radius || ball.y >= canvas.height - ball.radius)
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