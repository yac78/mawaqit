/**
 * handle weather
 */
var weather = {
    /**
     * get and display temperature
     */
    getTemperature: function () {
        $.ajax({
            url: "temperature",
            success: function (resp) {
                $(".temperature").hide();
                if (resp != "") {
                    if (parseInt(resp) > 15 && parseInt(resp) < 25) {
                        $(".temperature").addClass("orange");
                    } else if (parseInt(resp) >= 25) {
                        $(".temperature").addClass("red");
                    }
                    $(".temperature span").text(resp);
                    $(".temperature").show();
                }
            }
        });
    },
    initUpdateTemperature: function () {
        weather.getTemperature();
        setInterval(function () {
            weather.getTemperature();
        }, prayer.oneMinute * 60);
    }
};
