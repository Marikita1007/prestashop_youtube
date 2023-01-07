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

namespace PrestaShop\Module\Ps_metrics\Controller\Admin;

use PrestaShop\Module\Ps_metrics\Api\AnalyticsApi;
use PrestaShop\Module\Ps_metrics\Helper\JsonHelper;
use PrestaShop\Module\Ps_metrics\Helper\PrestaShopHelper;
use PrestaShop\Module\Ps_metrics\Module\Uninstall;
use PrestaShop\Module\Ps_metrics\Module\Upgrade;
use PrestaShop\Module\Ps_metrics\Presenter\FaqPresenter;
use PrestaShop\Module\Ps_metrics\Presenter\ShopDataPresenter;
use PrestaShop\Module\Ps_metrics\Provider\AnalyticsAccountsListProvider;
use PrestaShop\Module\Ps_metrics\Validation\SelectAccountAnalytics;
use PrestaShop\PsAccountsInstaller\Installer\Facade\PsAccounts;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Ps_metrics;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MetricsResolverController extends FrameworkBundleAdminController
{
    /**
     * @var Request
     */
    private $request = null;

    /** @var Ps_metrics */
    private $module;

    public function __construct(Ps_metrics $module)
    {
        $this->module = $module;
    }

    /**
     * Api endpoint
     *
     * @param Request $request
     * @param string $query
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function resolve(Request $request, string $query): Response
    {
        try {
            $this->request = $request;

            if (is_callable([$this, $query])) {
                return call_user_func([$this, $query]);
            }
        } catch (\Throwable $th) {
            throw new \Exception('#001 Message : ' . $th->getMessage());
        }

        return new Response('Not found', 404);
    }

    /**
     * Get shop datas api
     *
     * @return Response
     */
    public function getShopData(): Response
    {
        /** @var ShopDataPresenter $shopData */
        $shopData = $this->module->getService('ps_metrics.presenter.shopData');

        /** @var JsonHelper $jsonHelper */
        $jsonHelper = $this->module->getService('ps_metrics.helper.json');

        return new Response(
            $jsonHelper->jsonEncode($shopData->getShopData()),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * Retrieve category tree
     *
     * @return Response
     */
    public function getCategoryTree(): Response
    {
        $categoryTree = [];
        $context = \Context::getContext();

        if (null !== $context) {
            /** @var \Language $language */
            $language = $context->language;

            $categoryTree = \Category::getNestedCategories(\Category::getRootCategory()->id, (int) $language->id, false);
        }

        if (null === $categoryTree) {
            $categoryTree = [];
        }

        /** @var JsonHelper $jsonHelper */
        $jsonHelper = $this->module->getService('ps_metrics.helper.json');

        return new Response(
            $jsonHelper->jsonEncode($categoryTree),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * Get faq from addons
     *
     * @return Response
     */
    public function getFaq(): Response
    {
        /** @var FaqPresenter $faq */
        $faq = $this->module->getService('ps_metrics.presenter.faq');

        return new Response($faq->getFaq(), 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Generate product link
     *
     * @return Response
     */
    public function retrieveProductsLinksFromList(): Response
    {
        /** @var JsonHelper $jsonHelper */
        $jsonHelper = $this->module->getService('ps_metrics.helper.json');

        $products = explode(',', $this->request->request->get('products', ''));
        $links = [];

        foreach ($products as $product) {
            $links[$product] =
                $this->getAdminLink(
                    'AdminProducts',
                    ['id_product' => $product, 'updateproduct' => 1],
                    true
                ) . '#tab-step3';
        }

        return new Response($jsonHelper->jsonEncode($links), 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Upgrade a module
     *
     * @return Response
     */
    public function upgradeModule(): Response
    {
        /** @var Upgrade $upgrade */
        $upgrade = $this->module->getService('ps_metrics.module.upgrade');

        return new Response($upgrade->upgradeMetrics(), 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Select a Google Account for psessentials
     * Need webPropertyId and viewId. Returns 201 if done
     *
     * @return Response
     */
    public function selectAccountAnalytics(): Response
    {
        /** @var JsonHelper $jsonHelper */
        $jsonHelper = $this->module->getService('ps_metrics.helper.json');

        /** @var AnalyticsApi $apiAnalytics */
        $apiAnalytics = $this->module->getService('ps_metrics.api.analytics');

        /** @var SelectAccountAnalytics $serviceProcessSelectAccountAnalytics */
        $serviceProcessSelectAccountAnalytics = $this->module->getService(
            'ps_metrics.validation.processselectaccountanalytics'
        );

        $validateData = $serviceProcessSelectAccountAnalytics->validate([
            'webPropertyId' => $this->request->request->get(
                'webPropertyId',
                ''
            ),
            'viewId' => $this->request->request->get('viewId', ''),
        ]);

        if (false === $validateData) {
            return new Response(
                $jsonHelper->jsonEncode(['success' => false]),
                200,
                ['Content-Type' => 'application/json']
            );
        }
        $serviceResult = $apiAnalytics->setAccountSelection([
            'webPropertyId' => $this->request->request->get(
                'webPropertyId',
                ''
            ),
            'viewId' => $this->request->request->get('viewId', ''),
        ]);

        if (false === $serviceResult) {
            return new Response(
                $jsonHelper->jsonEncode([
                    'success' => false,
                ]),
                200,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            $jsonHelper->jsonEncode([
                'success' => true,
            ]),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * Google Analytics Logout must enable disabled modules, unsubscribe from PsEssentials
     * Also, it must reset configuration's values
     *
     * @return Response
     */
    public function ajaxProcessLogOut(): Response
    {
        /** @var JsonHelper $jsonHelper */
        $jsonHelper = $this->module->getService('ps_metrics.helper.json');

        /** @var Uninstall $uninstallGoogleAccount */
        $uninstallGoogleAccount = $this->module->getService('ps_metrics.module.uninstall');

        if (false === $uninstallGoogleAccount->unsubscribePsEssentials()) {
            return new Response(
                $jsonHelper->jsonEncode([
                    'success' => false,
                    'googleLinked' => true,
                ]),
                200,
                ['Content-Type' => 'application/json']
            );
        }

        if (false === $uninstallGoogleAccount->resetConfigurationValues()) {
            return new Response(
                $jsonHelper->jsonEncode([
                    'success' => false,
                    'googleLinked' => true,
                ]),
                200,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            $jsonHelper->jsonEncode([
                'success' => true,
                'googleLinked' => false,
            ]),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * Google Analytics Logout must enable disabled modules, unsubscribe from PsEssentials
     * Also, it must reset configuration's values
     *
     * @return Response
     */
    public function ajaxProcessRefreshGA(): Response
    {
        /** @var JsonHelper $jsonHelper */
        $jsonHelper = $this->module->getService('ps_metrics.helper.json');

        /** @var AnalyticsApi $apiAnalytics */
        $apiAnalytics = $this->module->getService('ps_metrics.api.analytics');

        $serviceResult = $apiAnalytics->refreshGA();

        if (!empty($serviceResult['error'])) {
            return new Response(
                $jsonHelper->jsonEncode([
                    'success' => false,
                    'message' => $serviceResult['error'],
                ]),
                200,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            $jsonHelper->jsonEncode([
                'success' => true,
            ]),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * Google Analytics Property List
     *
     * @return Response
     */
    public function ajaxProcessListProperty(): Response
    {
        /** @var JsonHelper $jsonHelper */
        $jsonHelper = $this->module->getService('ps_metrics.helper.json');

        /** @var AnalyticsAccountsListProvider $analyticsAccountListProvider */
        $analyticsAccountListProvider = $this->module->getService(
            'ps_metrics.provider.analyticsaccountslist'
        );

        $serviceResult = $analyticsAccountListProvider->getAccountsList();
        if (empty($serviceResult)) {
            return new Response(
                $jsonHelper->jsonEncode([
                    'success' => false,
                    'listProperty' => [],
                    'error' => 'No property list on this account',
                ]),
                200,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            $jsonHelper->jsonEncode([
                'success' => true,
                'listProperty' => $serviceResult,
            ]),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * Init Billing Free
     *
     * @return Response
     */
    public function ajaxProcessBillingFree(): Response
    {
        /** @var JsonHelper $jsonHelper */
        $jsonHelper = $this->module->getService('ps_metrics.helper.json');

        /** @var PsAccounts $accounts */
        $accounts = $this->module->getService('ps_accounts.facade');
        $billingService = $accounts->getPsBillingService();

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //whether ip is from proxy
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            //whether ip is from remote address
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        /*
            in some case, $ip_address contain list of ip address...
            and cause some billings problems
        */
        $ip_address = explode(',', $ip_address)[0];

        $result = $billingService->subscribeToFreePlan(
            $this->module->name,
            'metrics-free',
            false,
            trim($ip_address)
        );

        if (empty($result)) {
            return new Response(
                $jsonHelper->jsonEncode([
                    'success' => false,
                ]),
                200,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            $jsonHelper->jsonEncode([
                'success' => true,
                'billing' => $result,
            ]),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * Change shop context
     *
     * @return Response
     */
    public function ajaxProcessChangeShopContext(): Response
    {
        /** @var PrestaShopHelper $prestashopHelper */
        $prestashopHelper = $this->module->getService('ps_metrics.helper.prestashop');

        $link = $prestashopHelper->getLink();

        \Tools::redirectAdmin(
            $link->getAdminLink('MetricsController', true, [
                'route' => 'metrics_page',
            ]) . '#/settings'
        );

        return new Response();
    }
}
