const isDarkMode = () =>
	window.matchMedia &&
	window.matchMedia("(prefers-color-scheme: dark)").matches;

if ('serviceWorker' in navigator) {
	window.addEventListener('load', function () {
		navigator.serviceWorker.register('public/js/sw.js').then(function (registration) {
			// Registration was successful
			console.log('ServiceWorker registration successful with scope: ', registration.scope);
		}, function (err) {
			// registration failed :(
			console.log('ServiceWorker registration failed: ', err);
		});
	});
}