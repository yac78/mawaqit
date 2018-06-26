/* global prayer */

prayer.hideSpinner =  function () {
    $("#spinner").fadeOut(500, function () {
        $(".main").fadeIn(500);
    });
}

if(!prayer.confData.iqamaEnabled){
    $(".wait").css("visibility","hidden");
}

prayer.setTime();
prayer.setTimeInterval();
prayer.loadData();
prayer.setBackgroundColor();
prayer.setDate();
prayer.setTimes();
prayer.nextPrayerCountdown();
prayer.setWaitings();
prayer.initNextTimeHilight();
prayer.adhan.initFlash();
prayer.iqama.initFlash();
prayer.initCronHandlingTimes();
prayer.setSpecialTimes();
prayer.showSpecialTimes();
prayer.initUpdateConfData();
prayer.translateToArabic();
prayer.hideSpinner();
