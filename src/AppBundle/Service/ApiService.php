<?php

namespace AppBundle\Service;

use AppBundle\Service\OvhApiService;
use Monolog\Logger;

class ApiService extends OvhApiService {

    const URI_DYNHOST = "/domain/zone/%s/dynHost/record";
    const URI_LOGIN = "/domain/zone/%s/dynHost/login";
    const URI_REFRESH = "/domain/zone/%s/refresh";

    private $zoneName;

    /**
     * @var Logger
     */
    private $logger;

    ### Manage Dynhosts ###

    /**
     * Get all dynhost
     * @return array
     */
    function getDynhosts() {
        $url = sprintf(self::URI_DYNHOST, $this->zoneName);
        return $this->get($url);
    }

    /**
     * Get a dynhost
     * @param string $id
     * @return boolean, true if succès
     * @throws \Exception if error
     */
    function getDynhost($id) {
        $url = sprintf(self::URI_DYNHOST, $this->zoneName) . "/$id";
        $result = $this->get($url);
        die(dump($result));
        if ($result === null) {
            return true;
        }

        $msg = "La récupération de l'équipement id=$id a échouée";
        $this->logger->err($msg, [$result]);
        throw new \Exception($msg);
    }

    /**
     * Create a dynhost
     * @param string $subDomain
     * @param string $ip
     * @return integer the id of dynhost has just been created if succès
     * @throws \Exception
     */
    function createDynhost($subDomain, $ip) {
        $body = ["subDomain" => $subDomain, "ip" => $ip];
        $url = sprintf(self::URI_DYNHOST, $this->zoneName);
        $result = $this->post($url, $body);

        if ($result instanceof \stdClass && isset($result->id)) {
            $this->refresh();
            return $result->id;
        }

        $msg = "La création de l'équipement a échouée";
        $this->logger->err($msg, [$result]);
        throw new \Exception($msg);
    }

    /**
     * Update a dynhost
     * @param integer $id
     * @param string $subDomain
     * @param string $ip
     * @return boolean, true if succès
     * @throws \Exception if error
     */
    function updateDynhost($id, $subDomain, $ip) {
        $body = ["subDomain" => $subDomain, "ip" => $ip];
        $url = sprintf(self::URI_DYNHOST, $this->zoneName) . "/$id";
        $result = $this->put($url, $body);

        if ($result === null) {
            $this->refresh();
            return true;
        }

        $msg = "La mise à jour de l'équipement id=$id a échouée";
        $this->logger->err($msg, [$result]);
        throw new \Exception($msg);
    }

    /**
     * Delete a dynhost
     * @param integer $id
     * @return boolean, true if succès
     * @throws \Exception if error
     */
    function deleteDynhost($id) {
        $url = sprintf(self::URI_DYNHOST, $this->zoneName) . "/$id";
        $result = $this->delete($url);
        if ($result === null) {
            $this->refresh();
            return true;
        }

        $msg = "La supression de l'équipement id=$id a échouée";
        $this->logger->err($msg, [$result]);
        throw new \Exception($msg);
    }

    /**
     * refresh zone
     */
    function refresh() {
        $url = sprintf(self::URI_REFRESH, $this->zoneName);
        $this->post($url, []);
    }

    ### Manage Logins ###

    /**
     * Get all logins
     * @return array
     */
    function getLogins() {
        $url = sprintf(self::URI_LOGIN, $this->zoneName);
        return $this->get($url);
    }

    /**
     * Create a user
     * @param string $subDomain
     * @param string $loginSuffix
     * @param string $password
     * @return string the login has been created if success
     * @throws \Exception if error
     */
    function createLogin($subDomain, $loginSuffix, $password) {

        $body = ["subDomain" => $subDomain, "loginSuffix" => $loginSuffix, "password" => $password];
        $url = sprintf(self::URI_LOGIN, $this->zoneName);
        $result = $this->post($url, $body);

        if ($result instanceof \stdClass && isset($result->login)) {
            return $result->login;
        }

        $msg = "La création de l'utilisateur a échouée";
        $this->logger->err($msg, [$result]);
        throw new \Exception($msg);
    }

    /**
     * Update a user
     * @param string $login
     * @param string $subDomain
     * @return boolean true if success
     * @throws \Exception if error
     */
    function updateLogin($login, $subDomain) {
        $body = ["subDomain" => $subDomain];
        $url = sprintf(self::URI_LOGIN, $this->zoneName) . "/$login";
        $result = $this->put($url, $body);

        if ($result === null) {
            return true;
        }

        $msg = "La mise à jour de l'utilisateur $login a échouée";
        $this->logger->err($msg, [$result]);
        throw new \Exception($msg);
    }

    /**
     * Delete a user
     * @param string $login
     * @return boolean true if success
     * @throws \Exception if error
     */
    function deleteLogin($login) {
        $url = sprintf(self::URI_LOGIN, $this->zoneName) . "/$login";
        $result = $this->delete($url);
        if ($result === null) {
            return true;
        }

        $msg = "La supression l'utilisateur $login a échouée";
        $this->logger->err($msg, [$result]);
        throw new \Exception($msg);
    }

    function setZoneName(string $zoneName) {
        $this->zoneName = $zoneName;
    }

    function setLogger(Logger $logger) {
        $this->logger = $logger;
    }

}
