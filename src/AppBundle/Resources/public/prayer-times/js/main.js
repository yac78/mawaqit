/* global prayer */
/* global randomHadith */
/* global weather */
/* global messageInfoSlider */
/* global douaaSlider */

prayer.loadData();
prayer.setBackgroundColor();
prayer.setTime();
prayer.setTimeInterval();
prayer.setDate();
prayer.setTimes();
prayer.nextPrayerCountdown();
prayer.setWaitings();
prayer.initNextTimeHilight();
prayer.adhan.initFlash();
prayer.iqama.initFlash();
prayer.initCronHandlingTimes();
prayer.jumuaHandler.init();
prayer.setSpecialTimes();
prayer.showSpecialTimes();
prayer.initUpdateConfData();
prayer.initWakupFajr();
prayer.initEvents();
prayer.setQRCode();
prayer.hideSpinner();
prayer.initCronReloadPage();
randomHadith.init();
weather.initUpdateWeather();
douaaSlider.init();
messageInfoSlider.initCronMessageInfo();