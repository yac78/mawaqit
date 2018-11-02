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

        if (lang === "ar") {
            $(".adhkar-after-prayer .fr").remove();
        }

        var screenWidth = $(window).width();
        $('.adhkar-after-prayer li').width(screenWidth);
        var slideCount = $('.adhkar-after-prayer li').length;
        var sliderUlWidth = slideCount * screenWidth;
        $('.adhkar-after-prayer ul').css({width: sliderUlWidth});
        //save html slider
        this.sliderHtmlContent = $('.adhkar-after-prayer').html();
    },
    /**
     * If enabled show douaa after prayer for 5 minutes
     * @param {Number} currentTimeIndex
     */
    show: function (currentTimeIndex) {
        if (!prayer.isJumua(currentTimeIndex)) {
            if (prayer.confData.duaAfterPrayerEnabled) {
                $("#black-screen, .main").fadeOut(500, function () {
                    $(".adhkar-after-prayer").fadeIn(500, function () {
                        douaaSlider.setFontSize();
                    });
                });

                var douaaInterval = setInterval(function () {
                    douaaSlider.moveRight();
                }, douaaSlider.oneDouaaShowingTime);

                setTimeout(function () {
                    clearInterval(douaaInterval);
                    $(".adhkar-after-prayer").fadeOut(500, function () {
                        $(".main").fadeIn(500);
                        $('.adhkar-after-prayer').html(douaaSlider.sliderHtmlContent);
                    });

                    // show messages if exist after 10 sec after duaa
                    setTimeout(function () {
                        messageInfoSlider.get();
                    }, 10 * prayer.oneSecond);

                }, douaaSlider.getTimeForShow());
            } else {
                $("#black-screen").fadeOut(500, function () {
                    $(".main").fadeIn(500);
                });
                setTimeout(function () {
                    // no douaa, show messages if exist after 2 min after prayer
                    messageInfoSlider.get();
                }, 2 * prayer.oneMinute);
            }
        }
    },
    /**
     * Number of seconds to show all douaa
     * @returns {Number}
     */
    getTimeForShow: function () {
        return (($('.adhkar-after-prayer li').length - (lang === "ar" ? 1 : 0)) * douaaSlider.oneDouaaShowingTime) - 1000;
    },
    moveRight: function () {
        var screenWidth = $(window).width();
        $('.adhkar-after-prayer ul').animate({
            left: -screenWidth
        }, 1000, function () {
            $('.adhkar-after-prayer li:first-child').appendTo('.adhkar-after-prayer ul');
            $('.adhkar-after-prayer ul').css('left', '');
        });
    },
    setFontSize: function () {
        $('.slider li').each(function (i, slide) {
            fixFontSize(slide, 180);
        });
    }
};

/**
 * Messages slider class
 * @type {Object}
 */
var messageInfoSlider = {
    messageInfoIsShowing: false,
    /**
     * it saves html (ul,li)
     * @type String
     */
    sliderHtmlContent: '',
    /**
     * Cron handling message info showing
     * The messages will be shown
     *  - 5 before every adhan
     *  - 5 before jumu`a
     *  - At defined time
     */
    initCronMessageInfo: function () {
        setInterval(function () {
            if (prayer.isPrayingMoment()) {
                return false;
            }

            if (messageInfoSlider.messageInfoIsShowing === false) {
                messageInfoSlider.get();
            }
        }, prayer.oneMinute * 9);
    },
    /**
     *  run message slider
     */
    run: function () {
        var screenWidth = $(window).width();
        var nbSlides = $('.message-info-slider li').length;

        $('.message-info-slider li').width(screenWidth);

        var sliderUlWidth = nbSlides * screenWidth;
        $('.message-info-slider ul').css({width: sliderUlWidth});

        //save html slider
        messageInfoSlider.sliderHtmlContent = $('.message-info-slider').html();

        var interval = setInterval(function () {
            messageInfoSlider.moveRight();
        }, prayer.confData.timeToDisplayMessage * 1000);

        messageInfoSlider.messageInfoIsShowing = true;
        setTimeout(function () {
            clearInterval(interval);
            $(".message-info-slider").fadeOut(1000, function () {
                $(".main").fadeIn(1000);
            });
            messageInfoSlider.messageInfoIsShowing = false;
        }, (nbSlides * prayer.confData.timeToDisplayMessage * 1000) - 1000);

        $(".main").fadeOut(500, function () {
            $(".message-info-slider").fadeIn(500);
            messageInfoSlider.setFontSize();
        });
    },
    /**
     * Get message from server
     */
    get: function () {
        if ($(".main").is(":visible")) {
            $.ajax({
                dataType: "json",
                url: $(".message-info-slider").data("remote"),
                success: function (data) {
                    if (data.messages.length > 0) {
                        var slide;
                        var items = [];
                        $.each(data.messages, function (i, message) {
                            slide = '<li>';
                            if (message.image) {
                                slide += '<img src="/upload/' + message.image + '"/>';
                            } else {
                                if (message.title) {
                                    slide += '<div class="title">' + message.title + '</div>';
                                }
                                if (message.content) {
                                    slide += '<div class="content">' + message.content + '</div>';
                                }
                            }
                            slide += '</li>';
                            items.push(slide);
                        });
                        $(".message-info-slider").html("<ul>" + items.join("") + "</ul>");
                        messageInfoSlider.run();
                    }
                },
                /**
                 * If error show offline existing message
                 */
                error: function () {
                    if ($(".message-info-slider li").length > 0) {
                        messageInfoSlider.run();
                    }
                },
            });
        }
    },
    moveRight: function () {
        var screenWidth = $(window).width();
        $('.message-info-slider ul').animate({
            left: -screenWidth
        }, 1000, function () {
            $('.message-info-slider li:first-child').appendTo('.message-info-slider ul');
            $('.message-info-slider ul').css('left', '');
        });
    },
    setFontSize: function () {
        $('.message-info-slider li').each(function (i, slide) {
            var $slide = $(slide);
            if ($slide.find("img").length > 0) {
                return true;
            }
            fixFontSize(slide, 20);
        });
    }
};