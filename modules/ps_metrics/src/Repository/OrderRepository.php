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

namespace PrestaShop\Module\Ps_metrics\Repository;

use PrestaShop\Module\Ps_metrics\Helper\DbHelper;
use PrestaShop\Module\Ps_metrics\Helper\ShopHelper;

class OrderRepository
{
    /**
     * @var DbHelper
     */
    private $dbHelper;

    /**
     * @var ShopHelper
     */
    private $shopHelper;

    /**
     * __construct
     *
     * @param DbHelper $dbHelper
     * @param ShopHelper $shopHelper
     */
    public function __construct(DbHelper $dbHelper, ShopHelper $shopHelper)
    {
        $this->dbHelper = $dbHelper;
        $this->shopHelper = $shopHelper;
    }

    /**
     * Get CA for the shop for de order paid since last 3 months for billing plan
     *
     * @return int
     */
    public function getCaForPlans()
    {
        $firstOrderDate = $this->getFirstOrderDate();

        if (empty($firstOrderDate)) {
            return 0;
        }
        $begin = new \DateTime($firstOrderDate);
        $end = new \DateTime();
        $interval = date_diff($begin, $end);

        $intervalMonth = $interval->m + $interval->y * 12;
        $months = $intervalMonth > 3 ? 3 : $intervalMonth;
        $months = $months == 0 ? 1 : $months;

        $date = date('Y-m-d', (int) strtotime('-' . $months . 'months'));

        $amount = (int) $this->dbHelper->getValue(
            'SELECT SUM((od.unit_price_tax_incl / o.conversion_rate) * od.product_quantity) as revenues
            FROM ' .
                _DB_PREFIX_ .
                'order_detail od
            INNER JOIN ' .
                _DB_PREFIX_ .
                'orders o ON (od.id_order = o.id_order)
            INNER JOIN ' .
                _DB_PREFIX_ .
                'order_state os ON (o.current_state = os.id_order_state)
            WHERE
                o.date_add >= "' .
                pSQL($date) .
                '"
                AND os.logable = 1
                ' .
                $this->shopHelper->addSqlRestriction(false, 'o') .
                '
            ORDER BY o.date_add ASC'
        );

        return $amount / $months;
    }

    /**
     * Get first order date
     *
     * @return string
     */
    private function getFirstOrderDate()
    {
        return $this->dbHelper->getValue(
            'SELECT date_add
            FROM ' .
                _DB_PREFIX_ .
                'orders o
            INNER JOIN ' .
                _DB_PREFIX_ .
                'order_state os ON (o.current_state = os.id_order_state)
            WHERE os.logable = 1
                ' .
                $this->shopHelper->addSqlRestriction(false, 'o') .
                '
            ORDER BY date_add DESC'
        );
    }
}
