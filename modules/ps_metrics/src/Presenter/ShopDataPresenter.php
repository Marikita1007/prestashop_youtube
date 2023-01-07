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

namespace PrestaShop\Module\Ps_metrics\Presenter;

use PrestaShop\Module\Ps_metrics\Api\HttpApi;
use PrestaShop\Module\Ps_metrics\Helper\DbHelper;
use PrestaShop\Module\Ps_metrics\Helper\MultishopHelper;
use PrestaShop\Module\Ps_metrics\Helper\PrestaShopHelper;
use PrestaShop\Module\Ps_metrics\Helper\ShopHelper;
use PrestaShop\Module\Ps_metrics\Helper\ToolsHelper;
use PrestaShop\Module\Ps_metrics\Module\GAInstaller;
use PrestaShop\Module\Ps_metrics\Provider\AnalyticsAccountsListProvider;
use PrestaShop\Module\Ps_metrics\Repository\ConfigurationRepository;
use PrestaShop\Module\Ps_metrics\Repository\OrderRepository;
use PrestaShop\PsAccountsInstaller\Installer\Exception\InstallerException;
use PrestaShop\PsAccountsInstaller\Installer\Facade\PsAccounts;
use Ps_metrics;

class ShopDataPresenter
{
    /**
     * @var Ps_metrics
     */
    private $module;

    /**
     * @var PrestaShopHelper
     */
    private $prestashopHelper;

    /**
     * @var ConfigurationRepository
     */
    private $configurationRepository;

    /**
     * @var ShopHelper
     */
    private $shopHelper;

    /**
     * @var AnalyticsAccountsListProvider
     */
    private $analyticsAccountsListProvider;

    /**
     * @var GAInstaller
     */
    private $gaInstaller;

    /**
     * @var HttpApi
     */
    private $httpApi;

    /**
     * @var PsAccounts
     */
    private $psAccountsFacade;

    /**
     * @var ToolsHelper
     */
    private $toolsHelper;

    /**
     * @var DbHelper
     */
    private $dbHelper;

    /**
     * @var MultishopHelper
     */
    private $multishopHelper;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * Presenter constructor.
     *
     * @param Ps_metrics $module
     * @param PrestaShopHelper $prestashopHelper
     * @param ConfigurationRepository $configurationRepository
     * @param ShopHelper $shopHelper
     * @param AnalyticsAccountsListProvider $analyticsAccountsListProvider
     * @param GAInstaller $gaInstaller
     * @param HttpApi $httpApi
     * @param PsAccounts $psAccountsFacade
     * @param ToolsHelper $toolsHelper
     * @param DbHelper $dbHelper
     * @param MultishopHelper $multishopHelper
     * @param OrderRepository $orderRepository
     *
     * @return void
     */
    public function __construct(
        Ps_metrics $module,
        PrestaShopHelper $prestashopHelper,
        ConfigurationRepository $configurationRepository,
        ShopHelper $shopHelper,
        AnalyticsAccountsListProvider $analyticsAccountsListProvider,
        GAInstaller $gaInstaller,
        HttpApi $httpApi,
        PsAccounts $psAccountsFacade,
        ToolsHelper $toolsHelper,
        DbHelper $dbHelper,
        MultishopHelper $multishopHelper,
        OrderRepository $orderRepository
    ) {
        $this->module = $module;
        $this->prestashopHelper = $prestashopHelper;
        $this->configurationRepository = $configurationRepository;
        $this->shopHelper = $shopHelper;
        $this->analyticsAccountsListProvider = $analyticsAccountsListProvider;
        $this->gaInstaller = $gaInstaller;
        $this->httpApi = $httpApi;
        $this->psAccountsFacade = $psAccountsFacade;
        $this->toolsHelper = $toolsHelper;
        $this->dbHelper = $dbHelper;
        $this->multishopHelper = $multishopHelper;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Get shop datas for replacing the shop data in the module settings page.
     *
     * @return array
     */
    public function getShopData(): array
    {
        $currentShop = $this->shopHelper->getShopUrl(
            $this->prestashopHelper->getShopId()
        );

        try {
            $psAccountsService = $this->psAccountsFacade->getPsAccountsService();
            $psAccountsJWT = $psAccountsService->getOrRefreshToken();
            $psAccountsToken = $psAccountsService->getRefreshToken();
            $shopUuidV4 = $psAccountsService->getShopUuidV4();
            $email = $psAccountsService->getEmail();
            $emailIsValidated = $psAccountsService->isEmailValidated();
            $accountsUrl = $psAccountsService->getAdminAjaxUrl();
        } catch (InstallerException $e) {
            $psAccountsToken = '';
            $psAccountsJWT = '';
            $shopUuidV4 = '';
            $email = '';
            $emailIsValidated = false;
            $accountsUrl = '';
        }

        $psEventBusModuleVersion = '0.0.0';
        if (\Module::isInstalled('ps_eventbus')) {
            /** @var \Module $psEventBusModule */
            $psEventBusModule = \Module::getInstanceByName('ps_eventbus');
            $psEventBusModuleVersion = $psEventBusModule->version;
        }

        $psAccountsModuleVersion = '0.0.0';
        if (\Module::isInstalled('ps_accounts')) {
            /** @var \Module $psAccountsModule */
            $psAccountsModule = \Module::getInstanceByName('ps_accounts');
            $psAccountsModuleVersion = $psAccountsModule->version;
        }

        $link = $this->prestashopHelper->getLink();

        $googleLinkedUrl = $link->getAdminLink('MetricsOauthController', true, [
            'route' => 'metrics_oauth',
            'from' => 'PS',
        ]);

        $caForPlan = $this->httpApi->convertToCurrency(
            $this->orderRepository->getCaForPlans(),
            $this->prestashopHelper->getCurrencyIsoCode()
        );

        $response = [
            'modules' => [
                'psMetrics' => [
                    'name' => $this->module->name,
                    'displayName' => $this->module->displayName,
                    'version' => $this->module->version,
                    'emailSupport' => $this->module->emailSupport,
                    'termsOfServiceUrl' => $this->module->termsOfServiceUrl,
                ],
                'psEventBus' => [
                    'version' => $psEventBusModuleVersion,
                ],
                'psAccounts' => [
                    'version' => $psAccountsModuleVersion,
                    'userInfos' => [
                        'email' => $email,
                        'emailIsValidated' => $emailIsValidated,
                        'isSuperAdmin' => (new PrestaShopHelper())
                            ->getEmployee()
                            ->isSuperAdmin(),
                    ],
                    'apiUrl' => $accountsUrl,
                    'token' => $psAccountsToken,
                    'jwt' => $psAccountsJWT,
                ],
                'psAnalytics' => [
                    'version' => $this->gaInstaller->getModuleVersion(),
                    'isInstalled' => $this->gaInstaller->isInstalled(),
                    'isEnabled' => $this->gaInstaller->isEnabled(),
                    'installLink' => $this->gaInstaller->getInstallLink(),
                    'enableLink' => $this->gaInstaller->getEnableLink(),
                    'configLink' => $this->gaInstaller->getConfigLink(),
                ],
            ],
            'billing' => [
                'caForPlan' => $caForPlan < 1 ? 1 : $caForPlan,
            ],
            'links' => [
                'api' => [
                    'graphql' => $link->getAdminLink(
                        'MetricsGraphqlController',
                        true,
                        ['route' => 'metrics_graphql']
                    ),
                    'accountsRetrieveToken' => $accountsUrl,
                ],
                'controllers' => [
                    'metrics' => $link->getAdminLink(
                        'MetricsController',
                        true,
                        ['route' => 'metrics_page']
                    ),
                    'dashboard' => $link->getAdminLink(
                        'AdminDashboard',
                        true,
                        []
                    ),
                    'upgrade' => $link->getAdminLink('AdminModules', true, []),
                    'settings' => $link->getAdminLink('MetricsController', true, [
                            'route' => 'metrics_page',
                        ]) . '#/settings',
                ],
            ],
            'faq' => [
                'data' => [],
                'docsUrl' => $this->getReadme(),
            ],
            'shopInfos' => [
                'uuid' => $shopUuidV4,
                'shopVersion' => _PS_VERSION_,
                'domain' => $currentShop['domain'],
                'name' => \Configuration::get('PS_SHOP_NAME'),
                'isMultiShop' => $this->multishopHelper->isMultishopActive(),
                'shopList' => $this->multishopHelper->getAllShopsOrGroupShops(),
                'selectedShopData' => $this->multishopHelper->getSelectedShop(),
                'shopContext' => $this->multishopHelper->getShopContextLitteral(),
                'adminToken' => '',
            ],
            'i18n' => [
                'isoCode' => $this->prestashopHelper->getLanguageIsoCode(),
                'currencyIsoCode' => $this->prestashopHelper->getCurrencyIsoCode(),
            ],
            'serverInfos' => [
                'phpVersion' => phpversion(),
            ],
            'employeeInfos' => [
                'email' => $this->prestashopHelper->getEmployeeEmail(),
                'ip' => isset($_SERVER['REMOTE_ADDR'])
                    ? $_SERVER['REMOTE_ADDR']
                    : '',
            ],
            'pagesContext' => [
                'isLegacyStatsPage' => $this->checkIfPageIsLegacyStats(),
                'app' => '',
            ],
            'googleAccount' => [
                'isLinked' => (bool) $this->configurationRepository->getGoogleLinkedValue(),
                'linkedUrl' => $googleLinkedUrl,
                'list' => $this->analyticsAccountsListProvider->getAccountsList(),
                'selected' => $this->analyticsAccountsListProvider->getSelectedAccount(),
                'username' => $this->analyticsAccountsListProvider->getUserName(),
                'error' => $this->toolsHelper->getValue('google_message_error'),
            ],
        ];

        return $response;
    }

    /**
     * Check if page is ols stats page
     *
     * @return bool
     */
    private function checkIfPageIsLegacyStats(): bool
    {
        return $this->prestashopHelper->getControllerName() === 'AdminStats';
    }

    /**
     * Get the documentation url depending on the current language
     *
     * @return string path of the doc
     */
    private function getReadme(): string
    {
        $isoCode = $this->prestashopHelper->getLanguageIsoCode();
        $baseUrl =
            'https://storage.googleapis.com/psessentials-documentation/' .
            $this->module->name;

        if (
            !$this->checkFileExist(
                $baseUrl . '/user_guide_' . $isoCode . '.pdf'
            )
        ) {
            $isoCode = 'en';
        }

        return $baseUrl . '/user_guide_' . $isoCode . '.pdf';
    }

    /**
     * Use cUrl to get HTTP headers and detect any HTTP 404
     *
     * @param string $docUrl
     *
     * @return bool
     */
    private function checkFileExist($docUrl): bool
    {
        $ch = curl_init($docUrl);

        if (false === $ch) {
            return false;
        }

        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $retcode < 400;
    }

    /**
     * Retrieve the date_add when a field has been added to
     * the configuration table
     *
     * @param string $key name field in configuration table
     *
     * @return string date
     */
    private function getProductTourDate($key): string
    {
        $query = $this->dbHelper->getValue(
            'SELECT date_add
            FROM ' .
                _DB_PREFIX_ .
                'configuration c
            WHERE c.name = "' .
                $key .
                '"'
        );

        if (!empty($query)) {
            $date = new \DateTime($query);
            $query = $date->format('Y-m-d');
        }

        return $query;
    }
}
