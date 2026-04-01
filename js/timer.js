let cachedETag = null;
let cachedData = null;

async function tbaFetch(url, cacheKey) {
    const etag = localStorage.getItem(cacheKey + "_etag");

    const headers = {
        "X-TBA-Auth-Key": "1z1rvTlo2S8iXFco9I8et09kOrln30gku4LnAa7gCVTSR5M0YpenZqKaelGzZo1L",
        "accept": "application/json"
    };

    if (etag) headers["If-None-Match"] = etag;

    const res = await fetch(url, { headers });

    if (res.status === 304) {
        console.log("Using cached data");
        return null;
    }

    const data = await res.json();

    localStorage.setItem(cacheKey + "_etag", res.headers.get("ETag"));

    return data;
}

function toEpoch(dateStr, timezone) {
    const date = new Date(
        new Date(`${dateStr}T08:00:00`).toLocaleString("en-US", {
            timeZone: timezone
        })
    );
    return date.getTime();
}

async function getCompetitions() {
    const year = new Date().getFullYear();

    // 🔥 Start all requests immediately (no await yet)
    const worldsPromise = tbaFetch(
        `https://www.thebluealliance.com/api/v3/event/${year}cmptx`,
        "events_worlds"
    );

    const provsPromise = tbaFetch(
        `https://www.thebluealliance.com/api/v3/event/${year}oncmp`,
        "events_provs"
    );

    const teamPromise = tbaFetch(
        `https://www.thebluealliance.com/api/v3/team/frc9062/events/${year}/simple`,
        "events_team"
    );

    const [worldsJSON, provsJSON, teamJSON] = await Promise.all([
        worldsPromise,
        provsPromise,
        teamPromise
    ]);

    // --- Worlds ---
    let worlds = null;
    if (worldsJSON) {
        worlds = {
            date: worldsJSON.start_date,
            name: "FRC Worlds",
            time: toEpoch(worldsJSON.start_date, "America/Chicago")
        };
        localStorage.setItem("events_worlds_data", JSON.stringify(worlds));
    } else {
        worlds = JSON.parse(localStorage.getItem('events_worlds_data'));
    }

    // --- Provs ---
    let provs = null;
    if (provsJSON) {
        provs = {
            date: provsJSON.start_date,
            name: "Ontario Provincials",
            time: toEpoch(provsJSON.start_date, "America/Toronto")
        };
        localStorage.setItem("events_provs_data", JSON.stringify(provs));
    } else {
        provs = JSON.parse(localStorage.getItem('events_provs_data'));
    }

    // --- Team ---
    let team = null;
    if (teamJSON) {
        team = teamJSON.slice(0, 2).map(event => ({
            date: event.start_date,
            name: event.name,
            time: toEpoch(event.start_date, "America/Toronto")
        }));

        localStorage.setItem("events_team_data", JSON.stringify(team));
    } else {
        team = JSON.parse(localStorage.getItem('events_team_data'));
    }

    // --- Logic ---
    const current = Date.now();
    if (team && team[0] && team[0].time > current) return team[0];
    if (team && team[1] && team[1].time > current) return team[1];
    if (provs && provs.time > current) return provs;
    if (worlds && worlds.time > current) return worlds;

    return null;
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
    eventName.style.fontSize = `${Math.floor(fontSize * scale)}px`;
}

document.addEventListener("DOMContentLoaded", async () => {
    let EVENT = "9062 BUILD DAY";

    EVENT = await getCompetitions();
    console.log(EVENT);

    const eventName = document.getElementById("event-name");
    eventName.textContent = EVENT.name.replaceAll("Event", "Competition").replaceAll("ONT District", "");
    resizeEventName();

    updateCountdown(EVENT.time);

    const timerInterval = setInterval(() => {
        updateCountdown(EVENT.time);

        if (Date.now() >= EVENT.time) {
            clearInterval(timerInterval);
        }
    }, 1000);
});

window.addEventListener('resize', () => {
    resizeEventName();
});