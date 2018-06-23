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
prayer.adhan.initAdhanFlash();
prayer.iqama.initFlash();
prayer.initCronHandlingTimes();
prayer.jumuaHandler.init();
prayer.setSpecialTimes();
prayer.showSpecialTimes();
prayer.initUpdateConfData();
prayer.initWakupFajr();
prayer.initEvents();
prayer.translateToArabic();
prayer.setQRCode();
prayer.hideSpinner();
prayer.initCronReloadPage();
randomHadith.init();
weather.initUpdateTemperature();
douaaSlider.init();
messageInfoSlider.initCronMessageInfo();