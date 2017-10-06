/**
 * get and display a random hadith from server
 * It will be shwon every 5 min, except in prayer moment
 */
var randomHadith = {
    topContentHeight: null,
    init: function () {
        if (prayer.confData.randomHadithEnabled) {
            setInterval(function () {
                if (!prayer.isPrayingMoment()) {
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
            type: "JSON",
            url: "../get-random-hadith/" + lang,
            success: function (resp) {
                if (resp.text !== "") {
                    $(".random-hadith").removeClass("random-hadith-fr");
                    $(".random-hadith .text div").text(resp.text);
                    if (resp.lang === "fr") {
                        $(".random-hadith").addClass("random-hadith-fr");
                    }
                    randomHadith.show(randomHadith.setFontSize);
                }
            },
            error: function () {
                randomHadith.hide();
            }
        });
    },
    show: function (callback) {
        randomHadith.isRunning = true;
        prayer.nextPrayerCountdown();
        randomHadith.topContentHeight = $(".desktop .top-content").css("height");
        $(".desktop .top-content .content").fadeOut(1000, function () {
            $(".desktop .header").hide();
            $(".desktop .temperature").hide();
            $(".desktop .footer").hide();
            $(".desktop .prayer-content").addClass("to-bottom-times");
            $(".desktop .top-content").css("height", "68%");
            $(".random-hadith").fadeIn(1000);
            callback();
        });
    },
    hide: function () {
        randomHadith.isRunning = false;
        $(".random-hadith").fadeOut(1000, function () {
            $(".desktop .prayer-content").removeClass("to-bottom-times");
            $(".desktop .footer").show();
            $(".desktop .header").show();
            $(".desktop .temperature").show();
            $(".desktop .top-content").css("height", randomHadith.topContentHeight);
            $(".desktop .top-content .content").fadeIn(1000);
        });
    },
    setFontSize: function () {
        var $textContainer = $('.random-hadith .text');
        var $textContainerDiv = $('.random-hadith .text div');
        $textContainerDiv.css('font-size', '150px');
        $textContainerDiv.css('line-height', '300px');
        while ($textContainerDiv.height() > $textContainer.height()) {
            $textContainerDiv.css('font-size', (parseInt($textContainerDiv.css('font-size')) - 1) + "px");
            $textContainerDiv.css('line-height', (parseInt($textContainerDiv.css('line-height')) - 2) + "px");
        }
    }
};
