<?php
/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2015 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

/**
 * Class AlipayBackEndForm
 * Extends Alipay
 * @see Alipay
 * @see Helper
 * This class generates the module back-office forms thanks to the Helper class
 */
class AlipayBackEndForm extends Alipay
{
    /**
     * @return HelperForm
     */
    public function getHelper()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->name_controller = 'col-lg-8';
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        return $helper;
    }

    /**
     * @return array
     */
    public function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => (_PS_VERSION_ < '1.6' ? 'radio':'switch'),
                        'label' => $this->l('Live mode'),
                        'name' => 'ALIPAY_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 4,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-user"></i>',
                        'desc' => $this->l('Enter your Partner ID provided by Alipay'),
                        'name' => 'ALIPAY_PARTNER_ID',
                        'label' => $this->l('Partner ID'),
                    ),
                    array(
                        'col' => 4,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-key"></i>',
                        'name' => 'ALIPAY_SECRETE_KEY',
                        'label' => $this->l('Secrete key'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right button',
                ),
            ),
        );
    }

    /**
     * @return mixed
     */
    public function renderConfigForm()
    {
        $helper = $this->getHelper();
        $helper->submit_action = 'submitAlipayModule';
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * @return array
     */
    public function getReconciliationForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Reconciliation file'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'date',
                        'name' => 'from_reconciliation',
                        'class' => 'from_reconciliation',
                        'required' => true,
                        'label' => $this->l('From'),
                    ),
                    array(
                        'type' => 'date',
                        'name' => 'to_reconciliation',
                        'class' => 'to_reconciliation',
                        'required' => true,
                        'label' => $this->l('To'),
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'html_data',
                        'html_content' => '<span style="color: red">(<sup>*</sup>)'.$this->l(': required').'</span>',
                    ),
                ),
                'submit' => array(
                    'class' => 'btn btn-default pull-right button',
                    'title' => $this->l('Generate'),
                ),
            ),
        );
    }


    /**
     * @return mixed
     */
    public function renderReconciliationForm()
    {
        $helper = $this->getHelper();
        $helper->name_controller = 'col-lg-5';
        $helper->submit_action = 'submitReconciliationFile';
        $helper->tpl_vars = array(
            'fields_value'  => array('from_reconciliation' => '', 'to_reconciliation' => ''),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        return $helper->generateForm(array($this->getReconciliationForm()));
    }

    /**
     * @return array
     */
    public function getSettlementForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settlement file'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'date',
                        'name' => 'from_settlement',
                        'class' => 'from_settlement',
                        'required' => true,
                        'label' => $this->l('From'),
                    ),
                    array(
                        'type' => 'date',
                        'name' => 'to_settlement',
                        'class' => 'to_settlement',
                        'required' => true,
                        'label' => $this->l('To'),
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'html_data',
                        'html_content' => '<span style="color: red">(<sup>*</sup>)'.$this->l(': required').'</span>',
                    ),
                ),
                'submit' => array(
                    'class' => 'btn btn-default pull-right button',
                    'title' => $this->l('Generate'),
                ),
            ),
        );
    }

    /**
     * @return mixed
     */
    public function renderSettlementForm()
    {
        $helper = $this->getHelper();
        $helper->name_controller = 'col-lg-5';
        $helper->submit_action = 'submitSettlementFile';
        $helper->tpl_vars = array(
            'fields_value'  => array('from_settlement' => '', 'to_settlement' => ''),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        return $helper->generateForm(array($this->getSettlementForm()));
    }

    /**
     * @return array
     */
    public function getExchangeRateForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Exchange rate file'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'html',
                        'name' => 'html_data',
                        'html_content' => '<button class="btn btn-primary" type="submit" name="submitExchangeRate">'
                            .$this->l('Download').'</button>',
                    ),
                ),
            ),
        );
    }

    /**
     * @return mixed
     */
    public function renderExchangeRateForm()
    {
        $helper = $this->getHelper();
        $helper->name_controller = 'col-lg-2';
        $helper->submit_action = 'submitExchangeRate';
        $helper->tpl_vars = array(
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        return $helper->generateForm(array($this->getExchangeRateForm()));
    }

    /**
     * @return array
     */
    public function getHelpForm()
    {
        $img_link = Tools::getHttpHost(true).__PS_BASE_URI__.'modules/alipay/views/img/help/';
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('How to make test payments on Front Office?'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'html',
                        'name' => 'html_data',
                        'html_content' =>
                            '<h5 class="help">'.$this->l('Alipay front office sandbox account').'</h5>'
                            .'<p><strong>'.$this->l('Email: ').'</strong>sandbox_forex1@alipay.com<br />'
                            .'<strong>'.$this->l('Password: ').'</strong>111111</p>'
                            .'<p>'.$this->l('Click the images below to see step-by-step guide to run the testing mode')
                            .'</p><br />'
                            .'<p>
                                <a href="'.$img_link.'help1.jpg" target="_blank">
                                <img class="help" src="'.$img_link.'thumb1.jpg" width="50" height="50" /></a>
                                <a href="'.$img_link.'help2.jpg" target="_blank">
                                <img class="help" src="'.$img_link.'thumb2.jpg" width="50" height="50" /></a>
                                <a href="'.$img_link.'help3.jpg" target="_blank">
                                <img class="help" src="'.$img_link.'thumb3.jpg" width="50" height="50" /></a>
                                <a href="'.$img_link.'help4.jpg" target="_blank">
                                <img class="help" src="'.$img_link.'thumb4.jpg" width="50" height="50" /></a>
                                <a href="'.$img_link.'help5.jpg" target="_blank">
                                <img class="help" src="'.$img_link.'thumb5.jpg" width="50" height="50" /></a>
                                <a href="'.$img_link.'help6.jpg" target="_blank">
                                <img class="help" src="'.$img_link.'thumb6.jpg" width="50" height="50" /></a>
                                <a href="'.$img_link.'help7.jpg" target="_blank">
                                <img class="help" src="'.$img_link.'thumb7.jpg" width="50" height="50" /></a>
                                <a href="'.$img_link.'help8.jpg" target="_blank">
                                <img class="help" src="'.$img_link.'thumb8.jpg" width="50" height="50" /></a>
                            </p>',
                    ),
                ),
            ),
        );
    }

    /**
     * @return mixed
     */
    public function renderHelpForm()
    {
        $helper = $this->getHelper();
        $helper->name_controller = 'col-lg-4';
        $helper->submit_action = 'submitExchangeRate';
        $helper->tpl_vars = array(
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        return $helper->generateForm(array($this->getHelpForm()));
    }
}
