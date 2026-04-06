class Ball {
    constructor(x, y, xSpeed, ySpeed) {
        this.x = x;
        this.y = y;
        this.xSpeed = xSpeed;
        this.ySpeed = ySpeed;
        this.red = 255;
        this.green = 0;
        this.blue = 0;
        this.radius = 5;
    }

    setColor(red, green, blue) {
        this.red = red;
        this.green = green;
        this.blue = blue;
    }

    moveOneStep() {
        this.x += this.xSpeed;
        this.y += this.ySpeed;
    }

    draw(ctx) {
        ctx.fillStyle = "rgb(" + this.red + "," + this.green + "," + this.blue + ")";
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        ctx.closePath();
        ctx.fill();
    }
}

class Text {
    constructor(text, color, x, y) {
        this.x = x;
        this.y = y;
        this.color = color;
        this.text = text;
    }

    drawText(ctx) {
        ctx.fillStyle = this.color;
        ctx.font = "bold 48px Arial";
        ctx.textAlign = "center";
        ctx.fillText(this.text, this.x, this.y);
    }
}

function randR(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

// Following best practices, it's all inside a window load event
window.addEventListener("load", function (event) {
    const mouseRadius = 50;
    let mouseX = -10;
    let mouseY = -10;



    // Define Balls
    let balls = []
    for (let i = 0; i < 500; i++) {
        balls[i] = new Ball(
            randR(50, 750),
            randR(25, 375),
            randR(-10, 10) / 10,
            randR(-10, 10) / 10
        );
    }

    // Define Text
    let textBoxes = []
    textBoxes.push(
        new Text("9062", "#333333", 403, 53)
    );
    textBoxes.push(
        new Text("Critical Circuits", "#333333", 403, 103)
    );
    textBoxes.push(
        new Text("9062", "#8888FF", 400, 50)
    );
    textBoxes.push(
        new Text("Critical Circuits", "#8888FF", 400, 100)
    );

    // Define Image
    let img = new Image();
    img.src = 'images/logo.jpg';

    let imageData = {
        'image': img,
        'x': 25,
        'y': 25,
        'w': 75,
        'h': 75
    };

    let timerId;

    const c = document.getElementById("animationCanvas");
    const ctx = c.getContext("2d");

    c.addEventListener("mousemove", (e) => {
        const r = c.getBoundingClientRect();
        mouseX = e.clientX - r.left;
        mouseY = e.clientY - r.top;
    });

    function startAnimation() {
        timerId = setInterval(updateAnimation, 16);
        console.log("Animation Started")
    }

    function updateAnimation() {
        ctx.clearRect(0, 0, c.width, c.height);

        ctx.drawImage(
            imageData['image'], imageData['x'], imageData['y'],
            imageData['w'], imageData['h']);
        for (const ball of balls) {
            // ChatGPT helped with the logic for mouse circle bounces
            const dx = ball.x - mouseX;
            const dy = ball.y - mouseY;
            const minDist = ball.radius + mouseRadius;
            const dist2 = dx * dx + dy * dy;

            if (dist2 < minDist * minDist) {
                const dist = Math.sqrt(dist2) || 0.0001;
                const nx = dx / dist;
                const ny = dy / dist;

                // reflect velocity across the normal: v' = v - 2*(v·n)*n
                const dot = ball.xSpeed * nx + ball.ySpeed * ny;
                ball.xSpeed = ball.xSpeed - 2 * dot * nx;
                ball.ySpeed = ball.ySpeed - 2 * dot * ny;

                // push it out so it doesn't stick inside the radius
                const overlap = minDist - dist;
                ball.x += nx * overlap;
                ball.y += ny * overlap;
            }

            if (ball.x >= c.width - ball.radius || ball.x <= 0 + ball.radius) {
                ball.xSpeed *= -1;
            }

            if (ball.y >= c.height - ball.radius || ball.y <= 0 + ball.radius) {
                ball.ySpeed *= -1;
            }

            ball.moveOneStep();
            ball.draw(ctx);
        }

        for (textObject of textBoxes) {
            textObject.drawText(ctx);
        }
    }

    // Start things up!
    startAnimation();
});
