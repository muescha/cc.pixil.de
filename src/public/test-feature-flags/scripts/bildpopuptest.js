/* jshint esversion: 11 */

const BildPopupTest = (mypic, myname, breite, hoehe) => {

	'use strict';

	// --- DPI-Korrektur fuer HiDPI-/Retina-Displays ---------------------------
	const ratio = window.devicePixelRatio || 1;

	// Inverse Anpassung, damit das Fenster "optisch" die richtige Groesse hat
	const correctedWidth = Math.round(breite / ratio);
	const correctedHeight = Math.round(hoehe / ratio);

	// --- Bildschirmmitte berechnen ------------------------------------------
	const screen_width = screen.width;
	const screen_height = screen.height;

	const wleft = Math.max(0, (screen_width - breite) / 2);
	const wtop = Math.max(0, (screen_height - hoehe) / 2);

	// --- Fensternamen bereinigen --------------------------------------------
	myname = myname.replaceAll('&#x2011;', '-').replaceAll('-', '_').replaceAll(' ', '%20');

	// --- Hintergrundfarbe bestimmen -----------------------------------------
	let bgcolor = '';

	if (mypic.includes('/admin/') || mypic.includes('/adultcheck/')) {

		bgcolor = 'eeeeee';

	}
	else {

		bgcolor = '6699cc';

	}


	// --- Popup-Features ------------------------------------------------------
	const windowFeatures = [

		'titlebar=1',
		'toolbar=0',
		'location=0',
		'directories=0',
		'menubar=0',
		'resizable=0',
		'fullscreen=0',
		`width=${correctedWidth}`,
		`height=${correctedHeight}`,
		`top=${wtop}`,
		`left=${wleft}`

	].join(',');

	// --- URL-Parameter -------------------------------------------------------
	const urlParams = [

		`mypic=${encodeURIComponent(mypic)}`,
		`myname=${encodeURIComponent(myname)}`,
		`breite=${encodeURIComponent(breite)}`,
		`hoehe=${encodeURIComponent(hoehe)}`,
		`bgcolor=${encodeURIComponent(bgcolor)}`

	].join('&');

	const popupUrl = `/test-feature-flags/popup/popupandclose.php?${urlParams}`;


	// --- Popup oeffnen --------------------------------------------------------
	const handle = window.open(popupUrl, myname, windowFeatures);

	if (handle) {

		handle.moveTo(wleft, wtop);
		handle.focus();

	}
	else {

		console.warn('Popup konnte nicht geöffnet werden (vielleicht durch Pop-up-Blocker blockiert).');

	}

	alert('Popup-URL: ' + popupUrl + '\nwidth: ' + breite + '\nheight: ' + hoehe + '\nratio: ' + ratio + '\ncorrectedWidth: ' + correctedWidth + '\ncorrectedHeight: ' + correctedHeight + '\nwleft: ' + wleft + '\nwtop: ' + wtop + '')


};
