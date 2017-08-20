$(document).ready(function () {
    $("." + $("#appbundle_configuration_sourceCalcul").val()).removeClass("hidden");
    $(".calendar-prayer input").each(function (index) {
        if ($(this).val() == "")
        {
            $(this).css("background-color", "#f8d4d4");
        }
    });
    $("#appbundle_configuration_prayerMethod").trigger("change");

    $(document).ready(function () {
        $("#appbundle_configuration_jumuaAsDuhr").trigger("change");
    });
});

$("#appbundle_configuration_sourceCalcul").bind("change keyup", function (event) {
    $(".api, .calendar").addClass("hidden");
    $("." + $(this).val()).removeClass("hidden");
});

$("#appbundle_configuration_prayerMethod").bind("change keyup", function (event) {
    $(".degree").addClass("hidden");
    if ($(this).val() === 'CUSTOM') {
        $(".degree").removeClass("hidden");
    }
});

$(".calendar-prayer input").bind("change keyup", function (event) {
    $(this).css("background-color", $(this).val().match(/\d{2}:\d{2}/g) ? "#ffffff" : "#f8d4d4");
});

/**
 * On change file input (fill calendar) process fill prayer times from csv file
 */
$(".fill-calendar").change(function (e) {
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        var self = this;
        var reader = new FileReader();
        // Read file into memory as UTF-8      
        reader.readAsText(this.files[0]);
        // Handel load file
        reader.onload = function (event) {
            var csv = event.target.result;
            processFillMonthPrayerTimes(csv, self);
        };
        // Handle errors load
        reader.onerror = function (evt) {
            if (evt.target.error.name == "NotReadableError") {
                alert("Unable to read csv file");
            }
        };
    } else {
        alert("This fonctionality is not fully supported in your browser.");
    }
});
/**
 * Read CSV and fill prayerTimes in input elements 
 * @param {string} csv
 * @param {object} inputFile
 */
function processFillMonthPrayerTimes(csv, inputFile) {
    try {
        var panel = $(inputFile).parents(".month-panel");
        var panelId = panel.attr("id");
        var month = panelId.split('_');
        month = month[1];
        var lines = csv.split(/\r|\n/);
        for (var day = 1; day < lines.length; day++) {
            var line = lines[day].split(/,|;/);
            for (var prayer = 1; prayer < line.length; prayer++) {
                var inputPrayer = $("input[name='appbundle_configuration[calendar][" + month + "][" + day + "][" + prayer + "]']");
                if (line[prayer].match(/\d{2}:\d{2}/g)) {
                    inputPrayer.val(line[prayer]);
                    inputPrayer.trigger("change");
                }
            }
        }
        $(panel).find(".alert-success").removeClass("hidden");
    } catch (e) {
        $(panel).find(".alert-danger").removeClass("hidden");
    }
}

$("#predefined-calendar").change(function () {
    var calendarName = $(this).val();
    if (calendarName) {
        waitingDialog.show("Chargement en cours...");
        var url = $(this).data("ajax-url");
        var elem = null;
        $.ajax({
            url: url,
            method: "GET",
            dataType: "json",
            data: {
                calendarName: calendarName
            },
            success: function (calendar) {
                for (var month = 0; month < calendar.length; month++) {
                    for (var day = 1; day < calendar[month].length; day++) {
                        for (var prayer = 1; prayer < calendar[month][day].length; prayer++) {
                            elem = $("[name='appbundle_configuration[calendar][" + month + "][" + day + "][" + prayer + "]']");
                            elem.val(calendar[month][day][prayer]);
                            elem.trigger("keyup");
                        }
                    }
                }
                waitingDialog.hide();
            },
            error: function () {
                alert("Une erreur est survenue");
            },
            complete: function () {
                waitingDialog.hide();
            }
        });
    }
});

$("#appbundle_configuration_jumuaAsDuhr").bind("change", function (event) {
    if ($(this).is(":checked")) {
        $(".jumua-bloc").hide();
    } else {
        $(".jumua-bloc").show();
    }
});