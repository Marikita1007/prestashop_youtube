{**
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
 *}

<div class="container">
    <div class="row">
        <div class="col">
            <div class="card gamification_banner">
                <h3 class="card-header">
                    <i class="material-icons">extension</i> {l s='Boost your sales by offering payment in 3 or 4 installments to your customers' mod='gamification'}
                </h3>
                <div class="card-block">
                    <div class="module-item-list">
                        <div class="row module-item-wrapper-list py-3">
                            <div class="col-12 col-sm-2 col-md-2 col-lg-3">
                                <img class="m-auto img-fluid" src="{$pspaylater_img_path|escape:'htmlall':'UTF-8'}" alt="{l s='PrestaShop Paylater with PayPlug & Oney' mod='gamification'}" title="{l s='PrestaShop Paylater with PayPlug & Oney' mod='gamification'}" />
                            </div>
                            <div class="col-12 col-sm-6 col-md-7 col-lg-7 pl-0">
                                <p>{l s='PrestaShop Paylater is the official PrestaShop payment in installments solution.' mod='gamification'}</p>
                                <ul class="text-muted">
                                    <li class="mb-1">{l s='Up to 70% increase in average shopping cart value on your store' mod='gamification'}</li>
                                    <li class="mb-1">{l s='Full coverage for fraud and outstanding payments' mod='gamification'}</li>
                                    <li class="mb-1">{l s='Up to 98% payment acceptance rate for your shoppers' mod='gamification'}</li>
                                </ul>
                            </div>
                            <div class="col-12 col-sm-4 col-md-3 col-lg-2 mb-3 m-auto">
                                <div class="text-xs-center">
                                    {if $pspaylater_enabled}
                                    <a href="{$pspaylater_configure_link|escape:'htmlall':'UTF-8'}" class="btn btn-primary-reverse btn-outline-primary light-button">
                                        {l s='Configure' mod='gamification'}
                                    </a>
                                    {else}
                                    <form class="btn-group form-action-button" method="post" action="{$pspaylater_install_link|escape:'htmlall':'UTF-8'}">
                                        <button type="submit" class="btn btn-primary-reverse btn-outline-primary module_action_menu_install" data-confirm_modal="module-modal-confirm-pspaylater-install">
                                            {l s='Install' mod='gamification'}
                                        </button>
                                    </form>
                                    {/if}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
