$(document).ready(function () {
    $("." + $("#appbundle_configuration_sourceCalcul").val()).removeClass("hidden");
    $(".calendar-prayer input").each(function (index) {
        if ($(this).val() == "")
        {
            $(this).css("background-color", "#f8d4d4");
        }
    });
});

$("#appbundle_configuration_sourceCalcul").bind("change keyup", function (event) {
    $(".api, .calendar").addClass("hidden");
    $("." + $(this).val()).removeClass("hidden");
});

$(".calendar-prayer input").bind("change keyup", function (event) {
    $(this).css("background-color", $(this).val().match(/\d{2}:\d{2}/g) ? "#ffffff" : "#f8d4d4");
});

//$(".fill-calendar").change(function (e){
//    console.dir(this.files);
//})

function handleFiles(fileInput) {
    if (window.FileReader) {
        getAsText(fileInput);
    } else {
        alert("Cette fonctionalité n'est pas supporté par votre navigateur");
    }
}

function getAsText(fileInput) {
    var reader = new FileReader();
    // Read file into memory as UTF-8      
    reader.readAsText(fileInput.files[0]);
    // Handle errors load
    reader.onload = function (event) {
        var csv = event.target.result;
        processData(csv);
    };
    reader.onerror = function (evt) {
        if (evt.target.error.name == "NotReadableError") {
            alert("Impossible de lire le fichier csv");
        }
    };
}

function processData(csv) {
    var lines = csv.split(/\r\n|\n/);
    for (var i = 1; i < lines.length; i++) {
        var line = lines[i].split(',');
        for (var j = 1; j < line.length; j++) {
            var inputPrayer = $("input[name='appbundle_configuration[calendar][january][" + i + "][" + j + "]']");
            inputPrayer.val(line[j]);
            inputPrayer.trigger("change");
        }
    }
}

