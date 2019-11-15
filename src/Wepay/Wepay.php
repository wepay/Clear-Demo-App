<?php

namespace App\Wepay;

use App\Wepay\Exceptions\WepayConfigurationException;
use App\Wepay\Exceptions\WepayRequestException;
use App\Wepay\Exceptions\WepayServerException;
use DomainException;
use RuntimeException;

class Wepay
{
    CONST PRODUCTION_ENDPOINT = 'https://api.wepay.com';
    CONST STAGE_ENDPOINT      = 'https://stage-api.wepay.com';

    CONST PRODUCTION_ENVIRONMENT = 'production';
    CONST STAGE_ENVIRONMENT      = 'stage';

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $apiVersion;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var Wepay
     */
    private static $instance;

    /**
     * Private constructor. To prevent class initialization without call getInstance() method
     */
    private function __construct()
    {

    }

    /**
     * @return Wepay
     */
    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new Wepay();
        }

        return self::$instance;
    }

    /**
     * @param string $environment
     * @param string $appId
     * @param string $apiVersion
     * @param string $token
     */
    public function init($environment, $appId, $apiVersion, $token)
    {
        if ($this->initialized === true) {
            throw new RuntimeException('Wepay client already initialized');
        }

        $this->configureEndpoint($environment);

        $this->environment = $environment;
        $this->appId       = $appId;
        $this->apiVersion  = $apiVersion;
        $this->token       = $token;

        $this->configureHeaders();

        $this->initialized = true;
    }

    /**
     * Configure headers for API requests
     */
    private function configureHeaders()
    {
        $this->headers[] = 'App-ID: ' . $this->appId;
        $this->headers[] = 'API-Version: ' . $this->apiVersion;
        $this->headers[] = 'App-Token: ' . $this->token;
        $this->headers[] = 'Content-Type: application/json';
    }

    /**
     * @param string $environment
     */
    private function configureEndpoint($environment)
    {
        switch ($environment) {
            case self::STAGE_ENVIRONMENT:
                $this->endpoint = self::STAGE_ENDPOINT;

                break;

            case self::PRODUCTION_ENVIRONMENT:
                $this->endpoint = self::PRODUCTION_ENDPOINT;

                break;

            default:
                throw new DomainException("Unknown environment {$environment}");

                break;
        }
    }

    /**
     * @param string $endpoint
     * @param array  $body
     * @param bool   $withUniqueHeader
     *
     * @return array
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function request($endpoint, $body = [], $withUniqueHeader = false)
    {
        if ($this->initialized === false) {
            throw new RuntimeException('Wepay client not initialized. You mast call Wepay::init() before using API requests');
        }

        $headers = $this->headers;

        if ($withUniqueHeader) {
            $headers[] = 'Unique-Key: ' . uniqid();
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if (!empty($body)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $uri = $this->endpoint . $endpoint;
        curl_setopt($ch, CURLOPT_URL, $uri);

        $response = curl_exec($ch);
        $jsonResponse = json_decode($response, true);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode >= 500) {
            throw new WepayServerException($jsonResponse['error_message'], $httpCode, $jsonResponse);
        } else if ($httpCode >= 400) {
            throw new WepayRequestException($jsonResponse['error_message'], $httpCode, $jsonResponse);
        } else if ($httpCode >= 200 && $httpCode < 300) {
            return $jsonResponse;
        } else {
            throw new WepayConfigurationException($jsonResponse['error_message'], $httpCode, $jsonResponse);
        }
    }

    /**
     * @return bool
     */
    public function isInitialized()
    {
        return $this->initialized;
    }
}