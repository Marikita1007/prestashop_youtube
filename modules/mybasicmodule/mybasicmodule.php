<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop-project.org/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

use PhpParser\Node\Expr\Cast\Bool_;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if(!defined('_PS_VERSION_')){
    exit;
} 

//the main class Video:40:36
class MyBasicModule extends Module implements WidgetInterface {
    

    private $templateFile;

    //constructor                                                                  
    public function __construct()
    {
        $this->name = "mybasicmodule";
        $this->tab = "front_office_features";
        $this->version = "1.0";
        $this->author = "Marika Abe";
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            "min" => "1.6",
            "max" => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l("My very first module");
        $this->description = $this->l("This is a great testing module");
        $this->confirmUninstall = $this->l("Are you crazy, you are goiong to uninstall a great module!");

        // Confirm uninstall
        $this->templateFile = 'module:mybasicmodule/views/templates/hook/footer.tpl';

    }

    //install method
    public function install(): Bool
    {
        return parent::install() 
        && $this->registerHook('registerGDPRConsent')
        && $this->dbinstall();

    }

    //uninstall method
    public function uninstall(): Bool
    {
        return parent::uninstall();
    }

    //sql install
    public function dbinstall()
    {
        //sql query that create certain table
        return true;
    }

    //The Hooks convention : hooks functions always starts the name from hook. ex hookMyHookFunctionName()
    public function hookdisplayFooter($params)
    {
        //print_r($this->context->cart);
        $this->context->smarty->assign([
            'myparamtest' => "Marika Abe",
            'id_shop' => $this->context->cart->id_shop
        ]);
        return $this->display(__FILE__, 'views/templates/hook/footer.tpl');
 
    }

    //Generate the content of front page
    public function renderWidget($hookName, array $configuration)
    {
    
        if ($hookName === 'displayNavFullWidth') {
            return 'This is an exception from the displayNavFullWidth hook';
        };
        if (!$this->isCached($this->templateFile, $this->getCacheId($this->name))){
            $this->context->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }
        return $this->fetch('module:mybasicmodule/views/templates/hook/footer.tpl');
    }

    //Injecting variables(params) into renderWidget()
    //It returns an array of parameters that we need to pass them to our template
    public function getWidgetVariables($hookName, array $configuration)
    {
        return [
            'idshop' => $this->context->cart->id_shop,
            'myparamtest' => "Prestashop developer"
        ];
    }

    // //configuration page
    // public function getContent()
    // {

    //     $message = null;

    //     //This allows me to access to post or get objects.
    //     if(Tools::getValue("courserating")){
    //         Configuration::updateValue('COURSE_RATING',Tools::getValue("courserating"));
    //         $message = "Form save it correctly";
    //     };

    //     //field: courserating
    //     $courserating = Configuration::get('COURSE_RATING');
    //     $this->context->smarty->assign(
    //         [
    //             'courserating' => $courserating,
    //             'message' => $message
    //         ]
    //     );
    //     return $this->fetch('module:mybasicmodule/views/templates/admin/configuration.tpl');
    // }

    public function getContent()
    {
        return $this->displayForm();
    }

    public function displayForm()
    {

        $defaultLang = (int) Configuration::get('PS_LANG_DEFAULT');

        //form inputs
        $fields[0]['form'] = [
            'legend' => [
                'title' => $this->trans('Rating setting')
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Course rating'),
                    'name' => 'courserating',
                    'size' => 20,
                    'required' => true,
                ]
            ],
            'submit' => [
                'title' => $this->trans('Save the rating'),
                'class' => 'btn btn-primary pull-right'
            ]
        ];

        //Downbelow are from the site ,but maybe it needs update

        // instance of HF
        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        
        //Language
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        //Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];
        //$helper->fields_value['facebook_widget'] = Configuration::get('blocksocialJM_facebook_widget');
        //$helper->fields_value['twitter_widget'] = Configuration::get('blocksocialJM_twitter_widget');
        $helper->fields_value['courserating'] = Configuration::get('COURSE_RATING');
        return $helper->generateForm($fields);
    }
}