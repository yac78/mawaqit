/**
 * handle weather
 */
var weather = {
    /**
     * get and display weather
     */
    getWeather: function () {
        $weatherEl = $("#weather");
        $.ajax({
            url: $weatherEl.data("remote"),
            success: function (resp) {
                if (resp) {
                    $weatherEl.removeAttr("class");
                    if (parseInt(resp.temperature) <= 0) {
                        $weatherEl.addClass("blue");
                    }
                    // default white if > 0 && <= 10
                    if (parseInt(resp.temperature) > 10 && parseInt(resp.temperature) <= 20) {
                        $weatherEl.addClass("yellow");
                    }
                    if (parseInt(resp.temperature) > 20 && parseInt(resp.temperature) <= 30) {
                        $weatherEl.addClass("orange");
                    }
                    if (parseInt(resp.temperature) > 30) {
                        $weatherEl.addClass("red");
                    }

                    var today = new Date();
                    var hour = today.getHours();
                    var icon = resp.icon;
                    // @todo compare with maghreb and isha
                    if (hour > 8 && hour < 18) {
                        icon = "day-" + icon;
                    } else {
                        // fix night sunny
                        if (icon = 'sunny') {
                            icon = 'clear';
                        }
                        icon = "night-" + icon;
                    }

                    $weatherEl.find("i").attr('class', 'wi wi-' + icon);
                    $weatherEl.find("span").text(resp.temperature);
                }
            },
            error: function () {
                $weatherEl.addClass("hidden");
            }
        });
    },
    initUpdateWeather: function () {
        if (prayer.confData.temperatureEnabled === true) {
            weather.getWeather();
            setInterval(function () {
                weather.getWeather();
            }, prayer.oneMinute * 20);
        }
    }
};
