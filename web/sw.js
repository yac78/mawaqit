importScripts('sw-toolbox.js');

toolbox.precache([
   '/fr/mosquee-essunna-houilles',
]);

toolbox.router.get(/\.(?:js|css|png|jpg)$/, toolbox.cacheFirst, {
    cache: {
        name: 'assets'
    }
});