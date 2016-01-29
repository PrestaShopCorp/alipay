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

class Alipay extends PaymentModule
{
    /**
     * @var bool
     */
    protected $config_form = false;

    public $confirmation_message = '';

    public function __construct()
    {
        $this->name = 'alipay';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.4';
        $this->author = 'Alipay';
        $this->need_instance = 0;

        $this->bootstrap = true;
        $this->module_key = '4661b6e2596bc617243143e7878f17e2';

        parent::__construct();

        $this->displayName = $this->l('Alipay');
        $this->description = $this->l('ALIPAY IS THE WORLDS LEADING E-PAYMENT PROVIDER WITH 400 MILLION ACTIVE USERS IN CHINA. It processes 50% of the total online transactions and is the most preferred payment method by Chinese consumers. Configure Alipay and start selling to China now.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall Alipay?');

        $this->limited_currencies = array(
            'GBP',
            'HKD',
            'USD',
            'SGD',
            'CHF',
            'SEK',
            'DKK',
            'NOK',
            'JPY',
            'CAD',
            'AUD',
            'EUR',
            'NZD',
            'RUB',
            'MOP',
            'THB'
        );

        if (!defined('_PS_VERSION_')) {
            exit;
        }
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        include_once(_PS_MODULE_DIR_.'alipay/api/loader.php');

        if (extension_loaded('curl') == false) {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }

        Configuration::updateValue('ALIPAY_LIVE_MODE', 0);
        Configuration::updateValue('ALIPAY_PARTNER_ID', '2088101122136241');
        Configuration::updateValue('ALIPAY_SECRETE_KEY', '760bdzec6y9goq7ctyx96ezkz78287de');
        Configuration::updateValue('ALIPAY_GATEWAY', 'https://openapi.alipaydev.com/gateway.do?');
        Configuration::updateValue('ALIPAY_GATEWAY_PROD', 'https://mapi.alipay.com/gateway.do?');
        Configuration::updateValue('ALIPAY_SERVICE_PAYMENT', 'create_forex_trade');
        Configuration::updateValue('ALIPAY_SERVICE_TRADE_QUERY', 'single_trade_query');
        Configuration::updateValue('ALIPAY_SERVICE_REFUND', 'forex_refund');
        Configuration::updateValue('ALIPAY_SERVICE_COMPARE_FILE', 'forex_compare_file');
        Configuration::updateValue('ALIPAY_SERVICE_LIQUIDATION_FILE', 'forex_liquidation_file');
        Configuration::updateValue('ALIPAY_SERVICE_EXCHANGE_RATE', 'forex_rate_file');
        Configuration::updateValue('ALIPAY_SERVICE_NOTIFY_VERIFY', 'notify_verify');

        $admin_order_hook = (_PS_VERSION_ < '1.6' ? 'displayAdminOrder':'displayAdminOrderLeft');
        return parent::install() &&
        AlipayTools::createDb() &&
        $this->registerHook('header') &&
        $this->registerHook('backOfficeHeader') &&
        $this->registerHook('payment') &&
        $this->registerHook('paymentReturn') &&
        $this->registerHook($admin_order_hook);
    }

    /**
     * @return mixed
     */
    public function uninstall()
    {
        Configuration::deleteByName('ALIPAY_LIVE_MODE');
        Configuration::deleteByName('ALIPAY_PARTNER_ID');
        Configuration::deleteByName('ALIPAY_SECRETE_KEY');
        Configuration::deleteByName('ALIPAY_GATEWAY');
        Configuration::deleteByName('ALIPAY_GATEWAY_PROD');
        Configuration::deleteByName('ALIPAY_SERVICE_PAYMENT');
        Configuration::deleteByName('ALIPAY_SERVICE_TRADE_QUERY');
        Configuration::deleteByName('ALIPAY_SERVICE_REFUND');
        Configuration::deleteByName('ALIPAY_SERVICE_COMPARE_FILE');
        Configuration::deleteByName('ALIPAY_SERVICE_LIQUIDATION_FILE');
        Configuration::deleteByName('ALIPAY_SERVICE_EXCHANGE_RATE');
        Configuration::deleteByName('ALIPAY_SERVICE_NOTIFY_VERIFY');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        require_once(dirname(__FILE__).'/AlipayBackEndForm.php');
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitAlipayModule')) == true && (bool)Tools::isSubmit('ALIPAY_PARTNER_ID')) {
            $this->postProcess();
        }
        if (((bool)Tools::isSubmit('submitReconciliationFile')) == true) {
            $this->postProcessReconciliation();
        }
        if (((bool)Tools::isSubmit('submitSettlementFile')) == true) {
            $this->postProcessSettlementFile();
        }
        if (((bool)Tools::isSubmit('submitExchangeRate')) == true) {
            $this->postProcessExchangeRate();
        }

        if (!extension_loaded('curl')) {
            $this->context->smarty->assign('curl', 1);
        }

        $current_index = AdminController::$currentIndex.'&amp;configure='.$this->name
            .'&token='.Tools::getAdminTokenLite('AdminModules');
        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('current_index', $current_index);
        $this->context->smarty->assign('old_version', (_PS_VERSION_ < '1.6' ? '1':'0'));

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/backend.tpl');
        $back_end_form = new AlipayBackEndForm();
        $config_form = $back_end_form->renderConfigForm();
        $reconciliation_file_form = $back_end_form->renderReconciliationForm();
        $settlement_file_form = $back_end_form->renderSettlementForm();
        if (_PS_VERSION_ < '1.6') {
            $exchange_form = $this->context->smarty->fetch($this->local_path.'views/templates/admin/exchange_rate.tpl');
            $help_form = $this->context->smarty->fetch($this->local_path.'views/templates/admin/howto.tpl');
        } else {
            $exchange_form = $back_end_form->renderExchangeRateForm();
            $help_form = $back_end_form->renderHelpForm();
        }
        return $this->confirmation_message.$output.$config_form.$help_form.$reconciliation_file_form
        .$settlement_file_form.$exchange_form;
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'ALIPAY_LIVE_MODE'   => Configuration::get('ALIPAY_LIVE_MODE'),
            'ALIPAY_PARTNER_ID' => Configuration::get('ALIPAY_PARTNER_ID'),
            'ALIPAY_SECRETE_KEY' => ConfigurationCore::get('ALIPAY_SECRETE_KEY')
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
        $this->confirmation_message = (_PS_VERSION_ < '1.6' ?
            '<div class="conf confirmation">'.$this->l('Settings updated').'</div>':
            $this->displayConfirmation($this->l('Settings updated')));
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name || Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
            if (_PS_VERSION_ < '1.6') {
                $this->context->controller->addCSS($this->_path.'/views/css/back_15.css');
            }
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
        if (_PS_VERSION_ < '1.6') {
            $this->context->controller->addCSS($this->_path.'/views/css/front_15.css');
        }
    }

    /**
     * This method is used to render the payment button,
     * Take care if the button should be displayed or not.
     */
    public function hookPayment($params)
    {
        include_once(_PS_MODULE_DIR_.'alipay/api/loader.php');

        $currency_id = $params['cart']->id_currency;
        $currency = new Currency((int)$currency_id);
        $cart = new Cart($params['cart']->id);
        if (!ValidateCore::isLoadedObject($cart)) {
            return false;
        }
        if (in_array($currency->iso_code, $this->limited_currencies) == false) {
            return false;
        }
        $service = Configuration::get('ALIPAY_SERVICE_PAYMENT');
        $credentials = AlipayTools::getCredentials($service, false);

        $alipayapi = new AlipayApi($credentials);
        $alipayapi->setReturnUrl($this->getReturnUrl($cart->secure_key, $cart->id));
        $alipayapi->setNotifyUrl($this->getNotifyUrl($cart->secure_key, $cart->id));
        $alipayapi->setCharset('UTF-8');
        date_default_timezone_set('Asia/Hong_Kong');
        $payment_request = new PaymentRequest();
        $payment_request->setCurrency($currency->iso_code);
        $payment_request->setPartnerTransactionId(date('YmdHis').$cart->id);
        $payment_request->setGoodsDescription($this->getGoodsDescription());
        $payment_request->setGoodsName($this->getGoodsName($cart->id));
        $payment_request->setOrderGmtCreate(date('Y-m-d H:i:s'));
        $payment_request->setOrderValidTime(21600);
        $payment_request->setTotalFee($cart->getOrderTotal());
        $alipayapi->prepareRequest($payment_request);
        $url = $alipayapi->createUrl();
        $this->smarty->assign(
            array(
                'module_dir' => $this->_path,
                'alipay_payment_url' => $url
            )
        );

        return $this->display(__FILE__, 'views/templates/hook/payment.tpl');
    }

    /**
     * This hook is used to display the order confirmation page.
     */
    public function hookPaymentReturn($params)
    {
        include_once(_PS_MODULE_DIR_.'alipay/api/loader.php');

        if ($this->active == false) {
            return;
        }

        $order = $params['objOrder'];

        if ($order->getCurrentOrderState()->id != Configuration::get('PS_OS_ERROR')) {
            $this->smarty->assign('status', 'ok');
        }
        $sql = new DbQuery();
        $sql->select('a.trade_no');
        $sql->from('alipay', 'a');
        $sql->where('a.id_order='.(int)$params['objOrder']->id);
        $trade_no = Db::getInstance()->getValue($sql);

        $this->smarty->assign(array(
            'trade_no' => $trade_no,
            'id_order' => $order->id,
            'reference' => $order->reference,
            'params' => $params,
            'total' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
        ));

        return $this->display(__FILE__, 'views/templates/hook/confirmation.tpl');
    }

    public function hookDisplayAdminOrderLeft($params)
    {
        include_once(_PS_MODULE_DIR_.'alipay/api/loader.php');

        $transaction_details = $this->getTransactionDetails($params['id_order']);
        if (!$transaction_details || empty($transaction_details)) {
            return false;
        }

        if (Tools::isSubmit('submitRefund')) {
            $this->postProcessRefund($params['id_order'], $transaction_details);
        }

        $credentials = AlipayTools::getCredentials(Configuration::get('ALIPAY_SERVICE_TRADE_QUERY'));
        $alipayapi = new AlipayApi($credentials);
        $alipayapi->setCharset('UTF-8');
        $trade_query = new TransactionInfoRequest();
        $trade_query->setPartnerTransactionId($transaction_details['out_trade_no']);
        $alipayapi->prepareRequest($trade_query);
        $url = $alipayapi->createUrl();
        $xml = $alipayapi->getResponse($url);

        $trade_response = new TransactionInfoResponse();
        if ($trade_response->processResponse($xml, $transaction_details) < 0) {
            $this->context->controller->errors = array_merge(
                $this->context->controller->errors,
                $trade_response->getErrors()
            );
        } else {
            $vars = $trade_response->getTplVars();
            $this->context->smarty->assign(array(
                'refunds' => $this->getRefunds($params['id_order']),
                'transaction_details' => $vars,
                'prestashop_version_15' => (_PS_VERSION_ < '1.6' ? 1:0)
            ));
            if (_PS_VERSION_ < '1.6') {
                $this->context->controller->addCSS($this->_path . '/views/css/admin_15.css');
            }
            return $this->display(__FILE__, 'views/templates/hook/adminorder.tpl');
        }
    }

    public function hookDisplayAdminOrder($params)
    {
        return $this->hookDisplayAdminOrderLeft($params);
    }

    /**
     * @return mixed
     */
    public function getGoodsDescription()
    {
        return $this->context->shop->name;
    }

    /**
     * This method returns the products name of the products order
     * @param $id_cart
     * @return string
     */
    public function getGoodsName($id_cart)
    {
        $cart = new Cart($id_cart);
        $products = $cart->getProducts();
        $goods_name = '';
        foreach ($products as $product) {
            $goods_name .= $product['name'].', ';
        }
        if ($goods_name) {
            return Tools::substr($goods_name, 0, -2);
        }
        return $goods_name;
    }

    /**
     * This method returns the Notify URL used by Alipay to send notifications to the module
     * @param $secure_key
     * @param $id_cart
     * @return string
     */
    public function getNotifyUrl($secure_key, $id_cart)
    {
        $shop_url = Tools::getHttpHost(true).__PS_BASE_URI__;
        return $shop_url.'modules/alipay/notify_url.php?secure_key='.$secure_key.'&id_cart='.$id_cart;
    }

    /**
     * This method returns the Return URL after a payment is made by a customer
     * @param $secure_key
     * @param $id_cart
     * @return string
     */
    public function getReturnUrl($secure_key, $id_cart)
    {
        $shop_url = Tools::getHttpHost(true).__PS_BASE_URI__;
        return $shop_url.'index.php?fc=module&module='.$this->name
        .'&controller=confirmation&secure_key='.$secure_key.'&id_cart='.$id_cart;
    }

    /**
     * @param $id_order
     * @param $transaction_details
     */
    public function postProcessRefund($id_order, $transaction_details)
    {
        include_once(_PS_MODULE_DIR_.'alipay/api/loader.php');

        $service = Configuration::get('ALIPAY_SERVICE_REFUND');
        $credentials = AlipayTools::getCredentials($service, false);
        $gmt_date = date('Ymdhis');

        $params = array();
        $params['amount'] = Tools::getValue('refund_amount');
        $params['refund_reason'] = Tools::getValue('refund_reason');
        $params['out_trade_no'] = $transaction_details['out_trade_no'];
        $params['refund_no'] = md5(date('Ymdhis'));
        $params['id_order'] = $id_order;
        $params['currency'] = $transaction_details['currency'];

        $alipayapi = new AlipayApi($credentials);
        $alipayapi->setCharset('UTF-8');
        $refund_request = new RefundRequest();
        $refund_request->setOutReturnNo($params['refund_no']);
        $refund_request->setOutTradeNo($params['out_trade_no']);
        $refund_request->setReturnAmount($params['amount']);
        $refund_request->setCurrency($params['currency']);
        $refund_request->setGmtReturn($gmt_date);
        $refund_request->setReason($params['refund_reason']);
        $alipayapi->prepareRequest($refund_request);
        $url = $alipayapi->createUrl();
        $xml = $alipayapi->getResponse($url);

        $refund_response = new RefundResponse();
        if ($refund_response->processResponse($xml, $params) < 0) {
            $this->context->controller->errors = array_merge(
                $this->context->controller->errors,
                $refund_response->getErrors()
            );
        }
    }

    /**
     * This method returns an array of informations about a given order
     * @param $id_order
     * @return array|bool|null|object
     */
    public function getTransactionDetails($id_order)
    {
        $sql = new DbQuery();
        $sql->select('a.*');
        $sql->from('alipay', 'a');
        $sql->where('a.id_order = '.(int)$id_order);
        return Db::getInstance()->getRow($sql);
    }

    /**
     * This method returns an array of the refunds for a given order
     * @param $id_order
     * @return array|false|mysqli_result|null|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public function getRefunds($id_order)
    {
        $sql = new DbQuery();
        $sql->select('ar.*');
        $sql->from('alipay_refund', 'ar');
        $sql->where('ar.id_order = '.(int)$id_order);
        return Db::getInstance()->executeS($sql);
    }

    /**
     * This method downloads a text file that includes exchange rate by currency
     */
    public function postProcessExchangeRate()
    {
        include_once(_PS_MODULE_DIR_.'alipay/api/loader.php');

        $service = Configuration::get('ALIPAY_SERVICE_EXCHANGE_RATE');
        $credentials = AlipayTools::getCredentials($service, false);
        $alipayapi = new AlipayApi($credentials);
        $exchange_rate_request = new ExchangeRateRequest();
        $alipayapi->prepareRequest($exchange_rate_request);
        $url = $alipayapi->createUrl();
        Tools::redirect($url);
    }

    /**
     * This method downloads the Reconciliation File
     */
    public function postProcessReconciliation()
    {
        include_once(_PS_MODULE_DIR_.'alipay/api/loader.php');

        $service = Configuration::get('ALIPAY_SERVICE_COMPARE_FILE');
        $credentials = AlipayTools::getCredentials($service, false);
        $alipayapi = new AlipayApi($credentials);
        $reconciliation_request = new ReconciliationRequest();
        $reconciliation_request->setStartDate(str_replace('-', '', Tools::getValue('from_reconciliation')));
        $reconciliation_request->setEndDate(str_replace('-', '', Tools::getValue('to_reconciliation')));
        $alipayapi->prepareRequest($reconciliation_request);
        $url = $alipayapi->createUrl();
        $xml = simplexml_load_string(Tools::file_get_contents($url));
        if ($xml) {
            $response = $alipayapi->getResponse($url);
            $xmlObj = new SimpleXMLElement($response);
            $this->context->controller->errors[] = sprintf($this->l('An error occurred: %s'), $xmlObj->error);
        } else {
            Tools::redirect($url);
        }
    }

    /**
     * This method download the Settlement File
     */
    public function postProcessSettlementFile()
    {
        include_once(_PS_MODULE_DIR_.'alipay/api/loader.php');

        $service = Configuration::get('ALIPAY_SERVICE_LIQUIDATION_FILE');
        $credentials = AlipayTools::getCredentials($service, false);
        $alipayapi = new AlipayApi($credentials);
        $settlement_request = new SettlementFileRequest();
        $settlement_request->setStartDate(str_replace('-', '', Tools::getValue('from_settlement')));
        $settlement_request->setEndDate(str_replace('-', '', Tools::getValue('to_settlement')));
        $alipayapi->prepareRequest($settlement_request);
        $url = $alipayapi->createUrl();
        $xml = simplexml_load_string(Tools::file_get_contents($url));
        if ($xml) {
            $response = $alipayapi->getResponse($url);
            $xmlObj = new SimpleXMLElement($response);
            $this->context->controller->errors[] = sprintf($this->l('An error occurred: %s'), $xmlObj->error);
        } else {
            Tools::redirect($url);
        }
    }
}
