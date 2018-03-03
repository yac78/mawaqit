/* global prayer */

prayer.hideSpinner =  function () {
    $("#spinner").fadeOut(500, function () {
        $(".main").fadeIn(500);
    });
}

if(!prayer.confData.iqamaEnabled){
    $(".wait").css("visibility","hidden");
}

prayer.loadData();
prayer.setBackgroundColor();
prayer.setTime();
prayer.setDate();
prayer.setTimes();
prayer.setWaitings();
prayer.initNextTimeHilight();
prayer.initAdhanFlash();
prayer.initIqamaFlash();
prayer.initCronHandlingTimes();
prayer.setSpecialTimes();
prayer.showSpecialTimes();
prayer.initUpdateConfData();
prayer.translateToArabic();
prayer.hideSpinner();
