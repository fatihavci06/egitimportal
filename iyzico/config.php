<?php

require_once('IyzipayBootstrap.php');

IyzipayBootstrap::init();

class Config
{
    public static function options()
    {
        $options = new \Iyzipay\Options();
        $options->setApiKey("sandbox-ZB0Xiesca8CVZeURjTyOjwPugpDyfAos");
        $options->setSecretKey("sandbox-aG8p6kUfR5tGRHLN2GzPJxTjpaWGYW5P");
        $options->setBaseUrl("https://sandbox-api.iyzipay.com");
        return $options;
    }
}