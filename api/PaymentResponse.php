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
 * Class PaymentResponse
 * Extends Response
 * @see Response
 */
class PaymentResponse extends Response
{
    /**
     * @var
     */
    private $tplVars;

    /**
     * @var
     */
    private $errors;

    /**
     * @var
     */
    private $out_trade_no;

    /**
     * @var
     */
    private $trade_no;

    /**
     * @var
     */
    private $trade_status;

    /**
     * @var
     */
    private $currency;

    /**
     * @var
     */
    private $total_fee;

    /**
     * @var
     */
    private $sign;

    /**
     * @var
     */
    private $sign_type;

    /**
     * @var
     */
    private $id_cart;

    /**
     * @var
     */
    private $secure_key;

    /**
     * @var
     */
    private $id_module;

    /**
     * @var
     */
    private $id_order;

    /**
     * @var
     */
    private $param_list;

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * This method compares the Sign string sent by Alipay's response with a Sign string generated with local values
     * @return bool
     */
    public function compareSign()
    {
        require_once(dirname(__FILE__).'/alipay.api.php');
        $default_config = array(
            'partner_id' => false,
            'service' => false
        );
        $credentials = AlipayTools::getCredentials(false, $default_config);
        $alipayapi = new AlipayApi($credentials);
        $generated_string = $alipayapi->getResponseSign($this);
        if ($this->sign != $generated_string) {
            return false;
        }
        return true;
    }

    /**
     * This method checks data integrity
     * @param bool $response
     * @param bool $params
     * @return bool
     */
    public function processResponse($response = true, $params = true)
    {
        $alipay = new Alipay();
        if (!$response || !$params) {
            $this->errors[] = $alipay->l('An error occured. Please contact the merchant to have more informations');
            return false;
        }
        $cart = new Cart((int)$this->id_cart);
        if (!Validate::isLoadedObject($cart)) {
            $this->errors[] = $alipay->l('Cannot load Cart object.');
            return false;
        }
        $customer = new Customer((int)$cart->id_customer);
        if (!Validate::isLoadedObject($customer)) {
            $this->errors[] = $alipay->l('Cannot load Customer object.');
            return false;
        }
        if ($customer->secure_key != $this->secure_key || !$this->compareSign()) {
            $this->errors[] = $alipay->l('An error occured. Please contact the merchant to have more informations');
            return false;
        }
        $this->tplVars = array(
            'id_cart'       => $this->id_cart,
            'id_module'     => $this->id_module,
            'secure_key'    => $this->secure_key
        );
        return true;
    }

    /**
     * @return mixed
     */
    public function getTplVars()
    {
        return $this->tplVars;
    }

    /**
     * @return mixed
     */
    public function getOutTradeNo()
    {
        return $this->out_trade_no;
    }

    /**
     * @param $out_trade_no
     */
    public function setOutTradeNo($out_trade_no)
    {
        $this->out_trade_no = $out_trade_no;
        $this->param_list['out_trade_no'] = $out_trade_no;
    }

    /**
     * @return mixed
     */
    public function getTradeStatus()
    {
        return $this->trade_status;
    }

    /**
     * @param $trade_status
     */
    public function setTradeStatus($trade_status)
    {
        $this->trade_status = $trade_status;
        $this->param_list['trade_status'] = $trade_status;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        $this->param_list['currency'] = $currency;
    }

    /**
     * @return mixed
     */
    public function getTotalFee()
    {
        return $this->total_fee;
    }

    /**
     * @param $total_fee
     */
    public function setTotalFee($total_fee)
    {
        $this->total_fee = $total_fee;
        $this->param_list['total_fee'] = $total_fee;
    }

    /**
     * @return mixed
     */
    public function getTradeNo()
    {
        return $this->trade_no;
    }

    /**
     * @param mixed $trade_no
     */
    public function setTradeNo($trade_no)
    {
        $this->trade_no = $trade_no;
        $this->param_list['trade_no'] = $trade_no;
    }

    /**
     * @return mixed
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param mixed $sign
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
    }

    /**
     * @return mixed
     */
    public function getSignType()
    {
        return $this->sign_type;
    }

    /**
     * @param mixed $sign_type
     */
    public function setSignType($sign_type)
    {
        $this->sign_type = $sign_type;
    }

    /**
     * @return mixed
     */
    public function getIdCart()
    {
        return $this->id_cart;
    }

    /**
     * @param mixed $id_cart
     */
    public function setIdCart($id_cart)
    {
        $this->id_cart = $id_cart;
    }

    /**
     * @return mixed
     */
    public function getSecureKey()
    {
        return $this->secure_key;
    }

    /**
     * @param mixed $secure_key
     */
    public function setSecureKey($secure_key)
    {
        $this->secure_key = $secure_key;
    }

    /**
     * @return mixed
     */
    public function getIdModule()
    {
        return $this->id_module;
    }

    /**
     * @param mixed $id_module
     */
    public function setIdModule($id_module)
    {
        $this->id_module = $id_module;
    }

    /**
     * @return mixed
     */
    public function getIdOrder()
    {
        return $this->id_order;
    }

    /**
     * @param mixed $id_order
     */
    public function setIdOrder($id_order)
    {
        $this->id_order = $id_order;
    }

    /**
     * @return mixed
     */
    public function getParamList()
    {
        return $this->param_list;
    }

    /**
     * This method sets the PaymentResponse object parameters with the Post values
     */
    public function getPostData()
    {
        $this->setOutTradeNo((int)Tools::getValue('out_trade_no'));
        $this->setTradeStatus(Tools::getValue('trade_status'));
        $this->setTradeNo(Tools::getValue('trade_no'));
        $this->setCurrency(Tools::getValue('currency'));
        $this->setSign(Tools::getValue('sign'));
        $this->setSignType(Tools::getValue('sign_type'));
        $this->setTotalFee(Tools::getValue('total_fee'));
        $this->setIdCart(Tools::getValue('id_cart'));
        $this->setSecureKey(Tools::getValue('secure_key'));
    }
}
