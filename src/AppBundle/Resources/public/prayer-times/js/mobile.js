/* global prayer */

// clic to go to mosque
$('#navigate').click(function (e) {
    var $url = "https://maps.google.com/maps?daddr=";
    var $platform = navigator.platform;

    if (($platform.indexOf("iPhone") != -1) || ($platform.indexOf("iPad") != -1) || ($platform.indexOf("iPod") != -1)) {
        $url = "maps://maps.google.com/maps?daddr=";
    }

    window.open($url + prayer.confData.latitude + "," + prayer.confData.longitude + "&amp;ll=");
});
