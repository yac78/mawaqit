importScripts('sw-toolbox.js');

toolbox.router.get(/.*/, toolbox.networkFirst, {
    cache: {
        name: 'mawaqit',
    }
});