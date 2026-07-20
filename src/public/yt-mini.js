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
