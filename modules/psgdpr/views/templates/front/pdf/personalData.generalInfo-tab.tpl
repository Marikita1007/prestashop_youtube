{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}


{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}

<h2>{l s='General info' mod='psgdpr'}</h2>
<br>
<table width="100%">
    <tr>
        <td width="47%">
            <table id="total-tab" width="100%">
                <tr>
                    <td class="grey" width="50%">
                        {l s='Gender' mod='psgdpr'}
                    </td>
                    <td class="white" width="50%">
                        {$customerInfo['gender']}
                    </td>
                </tr>
                <tr>
                    <td class="grey" width="50%">
                        {l s='Name' mod='psgdpr'}
                    </td>
                    <td class="white" width="50%">
                        {$customerInfo['firstname']} {$customerInfo['lastname']}
                    </td>
                </tr>
                <tr>
                    <td class="grey" width="50%">
                        {l s='Birth date' mod='psgdpr'}
                    </td>
                    <td class="white" width="50%">
                        {$customerInfo['birthday']}
                    </td>
                </tr>
                <tr>
                    <td class="grey" width="50%">
                        {l s='Age' mod='psgdpr'}
                    </td>
                    <td class="white" width="50%">
                        {$customerInfo['age']}
                    </td>
                </tr>
                <tr>
                    <td class="grey" width="50%">
                        {l s='Email' mod='psgdpr'}
                    </td>
                    <td class="white" width="50%">
                        {$customerInfo['email']}
                    </td>
                </tr>
                <tr>
                    <td class="grey" width="50%">
                        {l s='Language' mod='psgdpr'}
                    </td>
                    <td class="white" width="50%">
                        {$customerInfo['language']}
                    </td>
                </tr>
            </table>
        </td>
        <td width="5%"></td>
        <td width="47%">
            <table id="total-tab" width="100%">
                <tr>
                    <td class="grey" width="50%">
                        {l s='Creation account date' mod='psgdpr'}
                    </td>
                    <td class="white" width="50%">
                        {$customerInfo['date_add']}
                    </td>
                </tr>
                <tr>
                    <td class="grey" width="50%">
                        {l s='Last visit' mod='psgdpr'}
                    </td>
                    <td class="white" width="50%">
                        {$customerInfo['last_visit']}
                    </td>
                </tr>
                <tr>
                    <td class="grey" width="50%">
                        {l s='Siret' mod='psgdpr'}
                    </td>
                    <td class="white" width="50%">
                        {$customerInfo['siret']}
                    </td>
                </tr>
                <tr>
                    <td class="grey" width="50%">
                        {l s='Ape' mod='psgdpr'}
                    </td>
                    <td class="white" width="50%">
                        {$customerInfo['ape']}
                    </td>
                </tr>
                <tr>
                    <td class="grey" width="50%">
                        {l s='Company' mod='psgdpr'}
                    </td>
                    <td class="white" width="50%">
                        {$customerInfo['company']}
                    </td>
                </tr>
                <tr>
                    <td class="grey" width="50%">
                        {l s='Website' mod='psgdpr'}
                    </td>
                    <td class="white" width="50%">
                        {$customerInfo['website']}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    {* <tr>
        <td class="grey" width="50%">
            {l s='Gender' mod='psgdpr'}
        </td>
        <td class="white" width="50%">
            {$customerInfo['gender']}
        </td>
        <td class="white" width="50%">
        </td>
    </tr> *}

    {* {if $footer.product_discounts_tax_excl > 0}
        <tr>
            <td class="grey" width="50%">
                {l s='Total Discounts' d='Shop.Pdf' pdf='true'}
            </td>
            <td class="white" width="50%">
                - {displayPrice currency=$order->id_currency price=$footer.product_discounts_tax_excl}
            </td>
        </tr>

    {/if}

    <tr class="bold">
        <td class="grey">
            {l s='Total (Tax excl.)' d='Shop.Pdf' pdf='true'}
        </td>
        <td class="white">
            {displayPrice currency=$order->id_currency price=$footer.total_paid_tax_excl}
        </td>
    </tr>

    <tr class="bold big">
        <td class="grey">
            {l s='Total' d='Shop.Pdf' pdf='true'}
        </td>
        <td class="white">
            {displayPrice currency=$order->id_currency price=$footer.total_paid_tax_incl}
        </td>
    </tr> *}

</table>
