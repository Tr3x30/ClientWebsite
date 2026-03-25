async function loadData() {
    const response = await fetch("https://tr3x30.github.io/ClientWebsite/database/competitions.json");;
    const data = await response.json();
    return data;
}

function setTwoDigits(unitElement, value) {
    const digits = unitElement.querySelectorAll(".digit");
    const text = String(value).padStart(2, "0");

    digits[0].textContent = text[0];
    digits[1].textContent = text[1];
}

function updateCountdown(targetEpoch) {
    const now = Date.now();
    let diff = targetEpoch - now;

    if (diff < 0) {
        diff = 0;
    }

    const totalSeconds = Math.floor(diff / 1000);

    const weeks = Math.floor(totalSeconds / (7 * 24 * 60 * 60));
    const days = Math.floor((totalSeconds % (7 * 24 * 60 * 60)) / (24 * 60 * 60));
    const hours = Math.floor((totalSeconds % (24 * 60 * 60)) / (60 * 60));
    const mins = Math.floor((totalSeconds % (60 * 60)) / 60);
    const secs = totalSeconds % 60;

    const units = document.querySelectorAll("#timer .unit");

    setTwoDigits(units[0], weeks);
    setTwoDigits(units[1], days);
    setTwoDigits(units[2], hours);
    setTwoDigits(units[3], mins);
    setTwoDigits(units[4], secs);
}

function resizeEventName() {
    const eventName = document.getElementById("event-name");
    const computed = window.getComputedStyle(eventName);

    // helper to convert "123px" > 123
    const px = (value) => parseFloat(value);

    eventName.style.fontSize = "20px";

    let fontSize = px(computed.fontSize);
    let maxWidth = px(computed.maxWidth);
    let width = px(computed.width);
    let scale = maxWidth / width;
    console.log(`${maxWidth} / ${width} = ${scale}`);
    eventName.style.fontSize = `${Math.floor(fontSize * scale)}px`;
}

document.addEventListener("DOMContentLoaded", async () => {
    let EPOCH = Date.now();
    let EVENT = "9062 BUILD DAY";

    const EVENTS = await loadData();

    for (let key in EVENTS) {
        const eventTime = Number(key);

        if (eventTime > Date.now()) {
            EPOCH = eventTime;
            EVENT = EVENTS[key];
            break;
        }
    }

    const eventName = document.getElementById("event-name");
    eventName.textContent = EVENT;
    resizeEventName();

    updateCountdown(EPOCH);

    const timerInterval = setInterval(() => {
        updateCountdown(EPOCH);

        if (Date.now() >= EPOCH) {
            clearInterval(timerInterval);
        }
    }, 1000);
});

window.addEventListener('resize', () => {
    console.log('huh');
    resizeEventName();
});