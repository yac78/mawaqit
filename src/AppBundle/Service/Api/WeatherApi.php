<?php

namespace AppBundle\Service\Api;

abstract class WeatherApi {

    private $endpoint;
    private $key;

    function __construct($endpoint, $key) {
        $this->endpoint = $endpoint;
        $this->key = $key;
    }

    function call($method, $params) {
        $url = $this->endpoint . "?_auth=" . $this->key . "&" . $params;
        
        // Call
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($curl);

        if ($result === false) {
            return null;
        }

        return json_decode($result);
    }

}
