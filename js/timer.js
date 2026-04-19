// Author: Trevor Goff
// Date: Mar 30-19
// Description: JS to call PHP backend to get competition info.

// Call backend
async function getCompetitions() {
    const res = await fetch('./php/get_competitions.php');

    if (!res.ok) {
        throw new Error(`Request failed with status ${res.status}`);
    }

    return await res.json();
}

// Set time element to 2 digit (add leading zero) 
function setTwoDigits(unitElement, value) {
    const digits = unitElement.querySelectorAll(".digit");
    const text = String(value).padStart(2, "0");

    digits[0].textContent = text[0];
    digits[1].textContent = text[1];
}

// Update the countdown every second
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

// Change the event name to take up the same horizontal space as the timers
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
    eventName.style.fontSize = `${Math.floor(fontSize * scale)}px`;
}

document.addEventListener("DOMContentLoaded", async () => {
    try {
        const eventData = await getCompetitions();

        if (!eventData || !eventData.name || !eventData.time) {
            throw new Error("No event data available");
        }

        const eventName = document.getElementById("event-name");
        eventName.textContent = eventData.name
            .replaceAll("Event", "Competition")
            .replaceAll("ONT District", "");

        resizeEventName();
        updateCountdown(eventData.time);

        const timerInterval = setInterval(() => {
            updateCountdown(eventData.time);

            if (Date.now() >= eventData.time) {
                clearInterval(timerInterval);
            }
        }, 1000);
    } catch (error) {
        console.error(error);
    }
});
window.addEventListener('resize', () => {
    resizeEventName();
});