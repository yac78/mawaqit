
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
            setTimeout(function () {
                $(".desktop .main").fadeOut(1000, function () {
                    $(".douaa-after-prayer").fadeIn(1000);
                });

                var douaaInterval = setInterval(function () {
                    douaaSlider.moveRight();
                }, douaaSlider.oneDouaaShowingTime);

                setTimeout(function () {
                    clearInterval(douaaInterval);
                    $(".douaa-after-prayer").fadeOut(1000, function () {
                        $(".desktop .main").fadeIn(1000);
                        $('.douaa-after-prayer .slider').html(douaaSlider.sliderHtmlContent);
                    });

                    // show messages if exist
                    messageInfoSlider.show();
                }, douaaSlider.getTimeForShow());
            }, prayer.confData.duaAfterPrayerShowTimes[currentTimeIndex] * prayer.oneMinute);
        }
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
     *  show message slider
     */
    show: function () {
        setTimeout(function () {
            $.ajax({
                dataType: "json",
                url: "get-messages",
                success: function (data) {
                    if (data.length > 0) {
                        var items = [];
                        $.each(data, function (i, message) {
                            items.push('<li>'
                                    + '<div class="title">' + message.title + '</div>'
                                    + '<div class="content">' + message.content + '</div>'
                                    + "</li>"
                                    );
                        });

                        $(".message-info-slider .slider ul").html(items.join(""));

                        $(".desktop .main").fadeOut(1000, function () {
                            $(".message-info-slider").fadeIn(1000);
                        });

                        var screenWidth = $(window).width();
                        $('.message-info-slider .slider ul li').width(screenWidth);
                        if (data.length === 1) {
                            setTimeout(function () {
                                $(".message-info-slider").fadeOut(1000, function () {
                                    $(".desktop .main").fadeIn(1000);
                                });
                                messageInfoSlider.messageInfoIshowing = false;
                            }, messageInfoSlider.oneMessageShowingTime);
                        }

                        if (data.length === 2) {
                            $(".message-info-slider li").hide();
                            $(".message-info-slider li:eq(0)").show();

                            setTimeout(function () {
                                $(".message-info-slider li:eq(0)").hide(1000, function () {
                                    $(".message-info-slider li:eq(1)").show(1000);
                                });
                            }, messageInfoSlider.oneMessageShowingTime);

                            setTimeout(function () {
                                $(".message-info-slider").fadeOut(1000, function () {
                                    $(".desktop .main").fadeIn(1000);
                                });
                                messageInfoSlider.messageInfoIshowing = false;
                            }, messageInfoSlider.oneMessageShowingTime * 2);
                        }

                        if (data.length > 2) {
                            var slideCount = items.length;
                            var sliderUlWidth = slideCount * screenWidth;
                            $('.message-info-slider .slider').css({width: screenWidth});
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
                                    $(".desktop .main").fadeIn(1000);
                                    $('.message-info-slider .slider').html(messageInfoSlider.sliderHtmlContent);
                                });
                                messageInfoSlider.messageInfoIshowing = false;
                            }, (items.length * messageInfoSlider.oneMessageShowingTime) - 1000);
                        }
                    }
                }
            });
        }, 5 * prayer.oneSecond);
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
    messageInfoIshowing: false,
    /**
     * Cron handling message info showing
     * 5 before every adhan the messages will be shown
     */
    initCronMessageInfo: function () {
        setInterval(function () {
            if (messageInfoSlider.messageInfoIshowing === false) {
                $(prayer.getTimes()).each(function (i, time) {
                    var diffTimeInMiniute = Math.floor((new Date() - prayer.getCurrentDateForPrayerTime(time)) / prayer.oneMinute);
                    if (diffTimeInMiniute === -5) {
                        messageInfoSlider.messageInfoIshowing = true;
                        messageInfoSlider.show();
                    }
                });
            }
        }, prayer.oneMinute);
    },
};