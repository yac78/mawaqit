/**
 * check and hilight imcompleted months
 */

function checkAndHilightIncompletedMonths() {
    $(".month-panel").each(function (i, elm) {
        var panel = elm;
        $(panel).find(".panel-heading").css("background-color", " #e2e2e2");
        var title = $(panel).find("h4>strong");
        title.text(title.text().replace(" (Mois incomplet)", ""));
        $(panel).find(".calendar-prayer-time").each(function (i, input) {
            if ($(input).val() === "") {
                $(panel).find(".panel-heading").css("background-color", "#f2dede");
                title.text(title.text() + " (Mois incomplet)");
                return false;
            }
        });
    });
}

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
    $(this).css("background-color", /^\d{2}:\d{2}$/g.test($(this).val()) ? "#ffffff" : "#f8d4d4");
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
        var error = false;
        var lines = csv.split(/(?:\r?\n)/g);
        month = month[1];
        for (var day = 1; day < lines.length; day++) {
            var line = lines[day].split(/,|;/);
            for (var prayer = 1; prayer < line.length; prayer++) {
                var inputPrayer = $("input[name='appbundle_configuration[calendar][" + month + "][" + day + "][" + prayer + "]']");
                if (/^\d{2}:\d{2}$/.test(line[prayer]) === false) {
                    error = true;
                }
                inputPrayer.val(line[prayer]);
                inputPrayer.trigger("change");
            }
        }
        checkAndHilightIncompletedMonths();
        if (error) {
            $(panel).find(".alert-danger").removeClass("hidden");
        }
        else {
            $(panel).find(".alert-success").removeClass("hidden");
        }
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
                checkAndHilightIncompletedMonths();
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

function handleErrorsDisplay() {
    $(".has-error").parents(".panel-collapse").collapse("show");
}

/**
 * Jumua as duh handling checkbox
 */
$("#appbundle_configuration_jumuaAsDuhr").bind("change", function (event) {
    if ($(this).is(":checked")) {
        $(".jumua-bloc-time").hide();
    } else {
        $(".jumua-bloc-time").show();
    }
});

/**
 * No jumua checkbox handling
 */
$("#appbundle_configuration_noJumua").bind("change", function (event) {
    if ($(this).is(":checked")) {
        $(".jumua-bloc").hide();
    } else {
        $(".jumua-bloc").show();
    }
});

/**
 * jumu`a Reminder an blackScreen checkbox handling
 */
$(".jumuaTimeoutHandler input").bind("change", function () {
    var $jumuaTimeoutParent = $("#appbundle_configuration_jumuaTimeout").parent();
    $jumuaTimeoutParent.hide();

    $(".jumuaTimeoutHandler input").each(function (i, e) {
        if ($(e).is(":checked")) {
            $jumuaTimeoutParent.show();
            return false;
        }
    })

});

$("#appbundle_configuration_randomHadithEnabled").bind("change", function (event) {
    if ($(this).is(":checked")) {
        $(".hadith-block").show();
    } else {
        $(".hadith-block").hide();
    }
});

function iqamaSettingsDisplayHandler() {
    var $iqamaCheckbox = $("#appbundle_configuration_iqamaEnabled");
    if ($iqamaCheckbox.is(":checked")) {
        $(".iqama-settings").show();
    } else {
        $(".iqama-settings").hide();
    }
}

$("#appbundle_configuration_iqamaEnabled").bind("change", function (event) {
    iqamaSettingsDisplayHandler();
});


$("." + $("#appbundle_configuration_sourceCalcul").val()).removeClass("hidden");
$(".calendar-prayer input").each(function (index) {
    if ($(this).val() === "") {
        $(this).css("background-color", "#f8d4d4");
    }
});
$("#appbundle_configuration_prayerMethod").trigger("change");
$("#appbundle_configuration_jumuaAsDuhr").trigger("change");
$("#appbundle_configuration_noJumua").trigger("change");
$("#appbundle_configuration_randomHadithEnabled").trigger("change");
$(".jumuaTimeoutHandler input").trigger("change");

function dstDisplayHandler() {
    var $dst = $("#appbundle_configuration_dst");
    if ($dst.val() == "1") {
        $(".dst-bloc").show();
    } else {
        $(".dst-bloc").hide();
    }
}

$("#appbundle_configuration_dst").change(function () {
    dstDisplayHandler();
});


function wakeUpAzanDisplayHandler() {
    var $wakeForFajrTime = $("#appbundle_configuration_wakeForFajrTime");
    if ($wakeForFajrTime.val() > 0) {
        $(".wakeAzanVoice").show();
    } else {
        $(".wakeAzanVoice").hide();
    }
}

$("#appbundle_configuration_wakeForFajrTime").change(function () {
    wakeUpAzanDisplayHandler();
});

var wakeAzanVoice = new Audio();
$("#appbundle_configuration_wakeAzanVoice").change(function () {
    $("#azabStop").removeClass("hidden");
    wakeAzanVoice.pause();
    wakeAzanVoice = new Audio('/static/mp3/' + $(this).val() + '.mp3');
    wakeAzanVoice.play();
});

$("#azabStop").click(function () {
    wakeAzanVoice.pause();
});


dstDisplayHandler();
checkAndHilightIncompletedMonths();
handleErrorsDisplay();
iqamaSettingsDisplayHandler();
wakeUpAzanDisplayHandler();