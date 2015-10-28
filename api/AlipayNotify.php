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
 * Class AlipayNotify
 * Extends Request
 * @see Request
 */
class AlipayNotify extends Request
{
    /**
     * @var
     */
    private $notify_type;

    /**
     * @var
     */
    private $notify_id;

    /**
     * @var
     */
    private $notify_time;

    /**
     * @var
     */
    private $sign;

    /**
     * @var
     */
    private $charset;

    /**
     * @var
     */
    private $sign_type;

    /**
     * @var
     */
    private $out_trade_no;

    /**
     * @var
     */
    private $trade_status;

    /**
     * @var
     */
    private $trade_no;

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
    private $id_cart;

    /**
     * @var
     */
    private $secure_key;

    /**
     * @var
     */
    private $param_list;

    /**
     * This method stores payment details in database
     * @param $id_order
     * @return mixed
     */
    public function saveOrder($id_order)
    {
        return Db::getInstance()->insert('alipay', array(
            'id_order'      => (int)$id_order,
            'out_trade_no'  => pSQL($this->out_trade_no),
            'trade_no'      => pSQL($this->trade_no),
            'trade_status'  => pSQL($this->trade_status),
            'currency'      => pSQL($this->currency),
            'total_fee'     => (float)$this->total_fee
        ));
    }

    /**
     * This method stores notification details in database
     * @return mixed
     */
    public function saveNotify()
    {
        return Db::getInstance()->insert('alipay_notification', array(
            'id_notification'           => null,
            'notify_type'               => pSQL($this->notify_type),
            'notify_id'                 => pSQL($this->notify_id),
            'notify_date'               => pSQL($this->notify_time),
            'sign'                      => pSQL($this->sign),
            'sign_type'                 => pSQL($this->sign_type),
            'partner_transaction_id'    => pSQL($this->out_trade_no),
            'alipay_transaction_id'     => pSQL($this->trade_no),
        ));
    }

    /**
     * @return bool
     */
    public function verifyDupplicates()
    {
        $sql = new DbQueryCore();
        $sql->select('an.notify_id, an.sign');
        $sql->from('alipay_notification', 'an');
        $sql->where('an.notify_id="'.pSQL($this->notify_id).'" AND an.sign="'.pSQL($this->sign).'"');
        $result = Db::getInstance()->getRow($sql);
        if ($result) {
            return false;
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function getNotifyType()
    {
        return $this->notify_type;
    }

    /**
     * @param $notify_type
     */
    public function setNotifyType($notify_type)
    {
        $this->notify_type = $notify_type;
    }

    /**
     * @return mixed
     */
    public function getNotifyId()
    {
        return $this->notify_id;
    }

    /**
     * @param $notify_id
     */
    public function setNotifyId($notify_id)
    {
        $this->notify_id = $notify_id;
    }

    /**
     * @return mixed
     */
    public function getNotifyTime()
    {
        return $this->notify_time;
    }

    /**
     * @param $notify_time
     */
    public function setNotifyTime($notify_time)
    {
        $this->notify_time = $notify_time;
    }

    /**
     * @return mixed
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param $sign
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
    }

    /**
     * @return mixed
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * @return mixed
     */
    public function getSignType()
    {
        return $this->sign_type;
    }

    /**
     * @param $sign_type
     */
    public function setSignType($sign_type)
    {
        $this->sign_type = $sign_type;
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
    }

    /**
     * @return mixed
     */
    public function getTradeNo()
    {
        return $this->trade_no;
    }

    /**
     * @param $trade_no
     */
    public function setTradeNo($trade_no)
    {
        $this->trade_no = $trade_no;
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
    }

    /**
     * @return mixed
     */
    public function getIdCart()
    {
        return $this->id_cart;
    }

    /**
     * @param $id_cart
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
     * @param $secure_key
     */
    public function setSecureKey($secure_key)
    {
        $this->secure_key = $secure_key;
    }

    /**
     * @return mixed
     */
    public function getParamList()
    {
        return $this->param_list;
    }

    /**
     * @param $action
     * @return bool
     */
    public function setParamList($action)
    {
        $this->param_list = array();
        if ($action == 'notify_verify') {
            $this->param_list['notify_id'] = $this->notify_id;
        } elseif ($action == 'compare_sign') {
            $this->param_list['currency'] = $this->currency;
            $this->param_list['notify_id'] = $this->notify_id;
            $this->param_list['notify_time'] = $this->notify_time;
            $this->param_list['notify_type'] = $this->notify_type;
            $this->param_list['out_trade_no'] = $this->out_trade_no;
            $this->param_list['trade_no'] = $this->trade_no;
            $this->param_list['total_fee'] = $this->total_fee;
            $this->param_list['trade_status'] = $this->trade_status;
        } else {
            return false;
        }
    }

    /**
     * This method sets the AlipayNotify object parameters with the Post values
     */
    public function getPostData()
    {
        /**
         * PROTOCOL PARAMETERS
         */
        $this->setNotifyType(Tools::getValue('notify_type'));
        $this->setNotifyId(Tools::getValue('notify_id'));
        $this->setNotifyTime(Tools::getValue('notify_time'));
        $this->setSign(Tools::getValue('sign'));
        $this->setCharset(Tools::getValue('_input_charset'));
        $this->setSignType(Tools::getValue('sign_type'));
        /**
         * BUSINESS PARAMETERS
         */
        $this->setOutTradeNo(Tools::getValue('out_trade_no'));
        $this->setTradeStatus(Tools::getValue('trade_status'));
        $this->setTradeNo(Tools::getValue('trade_no'));
        $this->setCurrency(Tools::getValue('currency'));
        $this->setTotalFee(Tools::getValue('total_fee'));
        /**
         * PRESTASHOP PARAMETERS
         */
        $this->setIdCart(Tools::getValue('id_cart'));
        $this->setSecureKey(Tools::getValue('secure_key'));
    }
}
