/**
 * get and display a random hadith from server
 * It will be shwon every 5 min, except in prayer moment
 */
randomHadith = {
    isRunning: false,
    init: function () {
        if (prayer.confData.randomHadithEnabled) {
            setInterval(function () {
                if (!randomHadith.isRunning && !prayer.isPrayingMoment()) {
                    randomHadith.get();
                    setTimeout(function () {
                        randomHadith.hide();
                    }, 2 * prayer.oneMinute);
                }
            }, 5 * prayer.oneMinute);
        }
    },
    get: function () {
        $.ajax({
            url: "../get-hadith-of-the-day",
            success: function (resp) {
                if (resp !== "") {
                    $(".hadith-of-the-day").text(resp);
                    randomHadith.setFontSize(resp);
                    randomHadith.show();
                }
            },
            error: function () {
                randomHadith.hide();
            }
        });
    },
    show: function () {
        randomHadith.isRunning = true;
        $(".desktop .prayer-content").addClass("to-bottom-times");
        $(".desktop .top-content").css("height", "67%");
        $(".desktop .footer").hide();
        $(".desktop .header").hide();
        $(".desktop .top-content .content").fadeOut(1000, function () {
            $(".hadith-of-the-day").fadeIn(1000);
        });
    },
    hide: function () {
        randomHadith.isRunning = false;
        $(".hadith-of-the-day").fadeOut(1000, function () {
            $(".desktop .prayer-content").removeClass("to-bottom-times");
            $(".desktop .top-content").css("height", "40%");
            $(".desktop .footer").show();
            $(".desktop .header").show();
            $(".desktop .top-content .content").fadeIn(1000);
        });
    },
    setFontSize: function (text) {
        var size;
        if (text.length < 100) {
            size = 130;
        }
        if (text.length >= 100 && text.length < 150) {
            size = 120;
        }
        if (text.length >= 150 && text.length < 200) {
            size = 110;
        }
        if (text.length >= 200 && text.length < 250) {
            size = 100;
        }
        if (text.length >= 250 && text.length < 300) {
            size = 90
        }
        if (text.length >= 300 && text.length < 350) {
            size = 80;
        }
        if (text.length >= 350) {
            size = 70;
        }

        $(".hadith-of-the-day").css("font-size", size + "px");
    }
};