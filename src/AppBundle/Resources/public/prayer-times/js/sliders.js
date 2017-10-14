
/**
 * Douaa slider class
 * @type {Object}
 */
var douaaSlider = {
    oneDouaaShowingTime: 20000,
    /**
     * it saves html (ul,li)
     * @type String
     */
    sliderHtmlContent: '',
    /**
     *  init douaa after prayer slider
     */
    init: function () {
        // if mobile device ignore douaa slider
        if ($(window).width() < 1200) {
            return;
        }

        var screenWidth = $(window).width();
        $('.douaa-after-prayer .slider ul li').width(screenWidth);
        var slideCount = $('.douaa-after-prayer .slider ul li').length;
        var sliderUlWidth = slideCount * screenWidth;
        $('.douaa-after-prayer .slider').css({width: screenWidth});
        $('.douaa-after-prayer .slider ul').css({width: sliderUlWidth, marginLeft: -screenWidth});
        $('.douaa-after-prayer .slider ul li:last-child').prependTo('.douaa-after-prayer .slider ul');
        if (lang === "ar") {
            $(".douaa-after-prayer .slider .fr").remove();
        }
        //save html slider
        this.sliderHtmlContent = $('.douaa-after-prayer .slider').html();
    },
    /**
     * If enabled show douaa after prayer for 5 minutes
     * @param {Number} currentTimeIndex
     */
    show: function (currentTimeIndex) {
        if (prayer.confData.douaaAfterPrayerEnabled === true && !prayer.isJoumouaa(currentTimeIndex)) {
            $(".main").fadeOut(1000, function () {
                $(".douaa-after-prayer").fadeIn(1000);
            });

            var douaaInterval = setInterval(function () {
                douaaSlider.moveRight();
            }, douaaSlider.oneDouaaShowingTime);

            setTimeout(function () {
                clearInterval(douaaInterval);
                $(".douaa-after-prayer").fadeOut(1000, function () {
                    $(".main").fadeIn(1000);
                    $('.douaa-after-prayer .slider').html(douaaSlider.sliderHtmlContent);
                });

                // show messages if exist after 5 sec after duaa
                setTimeout(function () {
                    messageInfoSlider.get();
                }, 5 * prayer.oneSecond);

            }, douaaSlider.getTimeForShow());
        } else {
            // show messages if exist after prayer
            setTimeout(function () {
                messageInfoSlider.get();
            }, 5 * prayer.oneSecond);
        }
    },
    timeout: function (currentTimeIndex) {
        setTimeout(function () {
            douaaSlider.show(currentTimeIndex);
        }, prayer.confData.duaAfterPrayerShowTimes[currentTimeIndex] * prayer.oneMinute);
    },
    /**
     * Number of seconds to show all douaa
     * @returns {Number}
     */
    getTimeForShow: function () {
        return ($('.douaa-after-prayer .slider ul li').length * douaaSlider.oneDouaaShowingTime) - 1000;
    },
    moveRight: function () {
        var screenWidth = $(window).width();
        $('.douaa-after-prayer .slider ul').animate({
            left: -screenWidth
        }, 1000, function () {
            $('.douaa-after-prayer .slider ul li:first-child').appendTo('.douaa-after-prayer .slider ul');
            $('.douaa-after-prayer .slider ul').css('left', '');
        });
    }
};

/**
 * Messages slider class
 * @type {Object}
 */
var messageInfoSlider = {
    oneMessageShowingTime: 30000,
    /**
     * it saves html (ul,li)
     * @type String
     */
    sliderHtmlContent: '',
    /**
     *  run message slider
     */
    run: function () {
        messageInfoSlider.messageInfoIsShowing = true;
        var screenWidth = $(window).width();
        var screenHeight = $(window).height();
        var nbSlides = $('.message-info-slider .slider ul li').length;

        $('.message-info-slider .slider ul li').width(screenWidth);

        $(".main").fadeOut(1000, function () {
            $(".message-info-slider").fadeIn(1000);
        });

        if (nbSlides === 1) {
            setTimeout(function () {
                $(".message-info-slider").fadeOut(1000, function () {
                    $(".main").fadeIn(1000);
                });
                messageInfoSlider.messageInfoIsShowing = false;
            }, messageInfoSlider.oneMessageShowingTime);
        }

        if (nbSlides === 2) {
            $(".message-info-slider li").hide();
            $(".message-info-slider li:eq(0)").show();

            setTimeout(function () {
                $(".message-info-slider li:eq(0)").hide(1000, function () {
                    $(".message-info-slider li:eq(1)").show(1000);
                });
            }, messageInfoSlider.oneMessageShowingTime);

            setTimeout(function () {
                $(".message-info-slider").fadeOut(1000, function () {
                    $(".main").fadeIn(1000);
                });
                messageInfoSlider.messageInfoIsShowing = false;
            }, messageInfoSlider.oneMessageShowingTime * 2);
        }

        if (nbSlides > 2) {
            var sliderUlWidth = nbSlides * screenWidth;
            $('.message-info-slider .slider ul').css({width: sliderUlWidth, marginLeft: -screenWidth});
            $('.message-info-slider .slider ul li:last-child').prependTo('.message-info-slider .slider ul');

            //save html slider
            messageInfoSlider.sliderHtmlContent = $('.message-info-slider .slider').html();

            var interval = setInterval(function () {
                messageInfoSlider.moveRight();
            }, messageInfoSlider.oneMessageShowingTime);

            setTimeout(function () {
                clearInterval(interval);
                $(".message-info-slider").fadeOut(1000, function () {
                    $(".main").fadeIn(1000);
                    $('.message-info-slider .slider').html(messageInfoSlider.sliderHtmlContent);
                });
                messageInfoSlider.messageInfoIsShowing = false;
            }, (nbSlides * messageInfoSlider.oneMessageShowingTime) - 1000);
        }

        $(".message-info-slider .slider").css({width: screenWidth, height: screenHeight});
    },
    /**
     * Get message from server
     */
    get: function () {
        $.ajax({
            dataType: "json",
            url: $(".message-info-slider").data("remote"),
            success: function (data) {
                if (data.length > 0) {
                    var items = [];
                    $.each(data, function (i, message) {
                        if (message.image) {
                            items.push('<li class="message-image">'
                                    + '<img src="/upload/images/' + message.image + '"/>'
                                    + "</li>"
                                    );
                        } else {
                            items.push('<li>'
                                    + '<div class="title">' + message.title + '</div>'
                                    + '<div class="content">' + message.content + '</div>'
                                    + "</li>"
                                    );
                        }
                    });
                    $(".message-info-slider .slider ul").html(items.join(""));
                    messageInfoSlider.run();
                } else {
                    $(".main").fadeIn(1000);
                }
            },
            /**
             * If error show offline existing message
             */
            error: function (data) {
                if ($(".message-info-slider .slider ul li").length > 0) {
                    messageInfoSlider.run();
                } else {
                    $(".main").fadeIn(1000);
                }
            },
        });
    },
    moveRight: function () {
        var screenWidth = $(window).width();
        $('.message-info-slider .slider ul').animate({
            left: -screenWidth
        }, 1000, function () {
            $('.message-info-slider .slider ul li:first-child').appendTo('.message-info-slider .slider ul');
            $('.message-info-slider .slider ul').css('left', '');
        });
    },
    messageInfoIsShowing: false,
    /**
     * Cron handling message info showing
     * The messages will be shown
     *  - 5 before every adhan 
     *  - 5 before jumu`a
     *  - At defined time
     */
    initCronMessageInfo: function () {
        setInterval(function () {
            if (messageInfoSlider.messageInfoIsShowing === false) {
                var date = new Date();
                var diffTimeInMiniute;
                // 5 before every adhan
                $(prayer.getTimes()).each(function (i, time) {
                    diffTimeInMiniute = Math.floor((date - prayer.getCurrentDateForPrayerTime(time)) / prayer.oneMinute);
                    if (diffTimeInMiniute === -5) {
                        messageInfoSlider.get();
                        return;
                    }
                });

                // 5 min before jumu`a time
                if (date.getDay() === 5) {
                    diffTimeInMiniute = Math.floor((date - prayer.getCurrentDateForPrayerTime(prayer.getJoumouaaTime())) / prayer.oneMinute);
                    if (diffTimeInMiniute === -5) {
                        messageInfoSlider.get();
                        return;
                    }
                }

                // At defined time
//                prayer.confData.messageDefinedTimes = ["21:00", "21:05", "21:10"]
//                $.each(prayer.confData.messageDefinedTimes, function (i, time) {
//                    time = time.split(":");
//                    if (date.getHours() === parseInt(time[0]) && date.getMinutes() === parseInt(time[1])) {
//                        messageInfoSlider.show();
//                        return false;
//                    }
//                });
            }
        }, prayer.oneMinute);
    },
};