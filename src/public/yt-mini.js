'use strict';

const params = new URLSearchParams(window.location.search);

const videoId = params.get('v');
const rawStart = params.get('t') ?? '0';

const validVideoId =
  typeof videoId === 'string' &&
  /^[A-Za-z0-9_-]{11}$/.test(videoId);

const validStart =
  /^(0|[1-9][0-9]{0,6})$/.test(rawStart);

if (!validVideoId || !validStart) {
  document.body.textContent =
    'Ungültige Video-ID oder Startzeit.';

  throw new Error('Invalid player parameters');
}

const start = Number(rawStart);

if (!Number.isSafeInteger(start) || start > 604800) {
  document.body.textContent = 'Ungültige Startzeit.';

  throw new Error('Invalid start time');
}

const iframe = document.createElement('iframe');

const embedUrl = new URL(
  `https://www.youtube.com/embed/${videoId}`
);

embedUrl.searchParams.set('start', String(start));
embedUrl.searchParams.set('autoplay', '1');
embedUrl.searchParams.set('playsinline', '1');
embedUrl.searchParams.set('rel', '0');
embedUrl.searchParams.set('enablejsapi', '1');
embedUrl.searchParams.set(
  'origin',
  window.location.origin
);

iframe.src = embedUrl.href;
iframe.title = 'YouTube Video Player';
iframe.referrerPolicy =
  'strict-origin-when-cross-origin';

iframe.allow =
  'autoplay; encrypted-media; picture-in-picture; fullscreen';

iframe.allowFullscreen = true;

document.body.appendChild(iframe);

// --- Tab-Titel: "HH:MM:SS - Titel — Kanal" via YouTube postMessage-API ---

function formatDuration(totalSeconds) {
  const s = Math.floor(totalSeconds);
  const hh = String(Math.floor(s / 3600)).padStart(2, '0');
  const mm = String(Math.floor((s % 3600) / 60)).padStart(2, '0');
  const ss = String(s % 60).padStart(2, '0');
  return `${hh}:${mm}:${ss}`;
}

let currentTitle = null;
let currentDuration = null;
let currentAuthor = null;

function updateTabTitle() {
  if (currentTitle === null || currentDuration === null) {
    return;
  }

  const cleanTitle = currentTitle.replace(/\s+/g, ' ').trim();
  let next = `${formatDuration(currentDuration)} - ${cleanTitle}`;

  if (currentAuthor) {
    next += ` — ${currentAuthor.replace(/\s+/g, ' ').trim()}`;
  }

  if (document.title !== next) {
    document.title = next;
  }
}

window.addEventListener('message', (event) => {
  // Nur Nachrichten aus dem YouTube-Embed vertrauen.
  if (
    event.origin !== 'https://www.youtube.com' &&
    event.origin !== 'https://www.youtube-nocookie.com'
  ) {
    return;
  }

  if (typeof event.data !== 'string') {
    return;
  }

  let msg;
  try {
    msg = JSON.parse(event.data);
  } catch (_) {
    return;
  }

  const info = msg && msg.info;
  if (!info) {
    return;
  }

  if (info.videoData && typeof info.videoData.title === 'string') {
    currentTitle = info.videoData.title;
  }
  if (info.videoData && typeof info.videoData.author === 'string') {
    currentAuthor = info.videoData.author;
  }
  if (typeof info.duration === 'number' && info.duration > 0) {
    currentDuration = info.duration;
  }

  updateTabTitle();
});

// infoDelivery-Strom starten, sobald der Player geladen ist.
iframe.addEventListener('load', () => {
  iframe.contentWindow.postMessage(
    JSON.stringify({ event: 'listening', id: 1, channel: 'widget' }),
    '*'
  );
});
