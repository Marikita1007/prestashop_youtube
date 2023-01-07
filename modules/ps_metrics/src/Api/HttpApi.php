<?php

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

namespace PrestaShop\Module\Ps_metrics\Api;

use PrestaShop\Module\Ps_metrics\Api\Client\HttpClient;
use PrestaShop\Module\Ps_metrics\Helper\ConfigHelper;
use Psr\Http\Client\ClientExceptionInterface;

class HttpApi
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * HttpApi constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(
        HttpClient $httpClient,
        ConfigHelper $configHelper
    ) {
        $this->client = $httpClient;
        $this->client->setMiddlewares();
        $this->configHelper = $configHelper;
    }

    /**
     * @param string $moduleKey
     * @param string $isoCode
     * @param string $psVersion
     *
     * @return mixed
     */
    public function getFaq($moduleKey, $isoCode, $psVersion)
    {
        $url =
            'https://api.addons.prestashop.com/request/faq/' .
            $moduleKey .
            '/' .
            $psVersion .
            '/' .
            $isoCode;
        $this->client->setUrl($url);

        $faq = $this->client->get();

        return !empty($faq['error']) ? null : $faq['body'];
    }

    /**
     * @param string $moduleId
     * @param string $isoCode
     * @param string $psVersion
     * @param string $format
     *
     * @return mixed
     */
    public function getLastedVersion(
        $moduleId,
        $isoCode,
        $psVersion,
        $format = 'json'
    ) {
        $url =
            'https://api.addons.prestashop.com/?version=' .
            $psVersion .
            '&iso_lang=' .
            $isoCode .
            '&iso_code=' .
            $isoCode .
            '&format=' .
            $format .
            '&method=listing&action=module&id_module=' .
            $moduleId;
        $this->client->setUrl($url);
        $module = $this->client->get();

        return empty($module['error']) &&
            !empty($module['body']['modules'][0]['version'])
            ? $module['body']['modules'][0]['version']
            : false;
    }

    /**
     * @param string $isoCode
     *
     * @return mixed
     */
    public function getPlansDetails($isoCode)
    {
        $this->client->setUrl(
            $this->configHelper->getApiBaseUrl() .
                '/plans/description/' .
                $isoCode
        );
        $plans = $this->client->get();

        return !empty($plans['error'] || empty($plans['body']))
            ? null
            : $plans['body']['plans_description'];
    }

    /**
     * @param string $url
     *
     * @return mixed
     */
    public function getSourcePage($url)
    {
        $this->client->setUrl($url);
        try {
            $source = $this->client->get();
        } catch (ClientExceptionInterface $error) {
            $source['error'] = $error->getMessage();
        }

        return !empty($source['error']) ? null : $source['body'];
    }

    /**
     * Convert amount from currency to another currency
     *
     * @param float $amount
     * @param string $currency_target
     * @param string $currency_source
     *
     * @return float
     */
    public function convertToCurrency(
        $amount,
        $currency_target,
        $currency_source = 'EUR'
    ) {
        if ($currency_source === $currency_target) {
            return $amount;
        }

        $this->client->setUrl(
            $this->configHelper->getApiBaseUrl() .
                '/currencies?currency=' .
                $currency_source
        );
        $datas = $this->client->get();

        $rate =
            !empty($datas['body']) && !empty($datas['body'][$currency_target])
                ? $datas['body'][$currency_target]
                : 1;

        // 1 USD = 0.8 EUR (by exemple)
        // 100 USD / 0.8 = 120 EUR
        return round($amount / $rate, 2);
    }
}
