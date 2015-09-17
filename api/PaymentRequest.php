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
 * Class PaymentRequest
 * Extends Request class
 * @see Request
 */
class PaymentRequest extends Request
{
    /**
     * @var
     */
    private $partner_transaction_id;
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
    private $rmb_fee;
    /**
     * @var
     */
    private $order_gmt_create;
    /**
     * @var
     */
    private $order_valid_time;
    /**
     * @var
     */
    private $goods_name;
    /**
     * @var
     */
    private $goods_description;

    private $param_list;

    /**
     * @return mixed
     */
    public function getPartnerTransactionId()
    {
        return $this->partner_transaction_id;
    }

    /**
     * @param mixed $partner_transaction_id
     */
    public function setPartnerTransactionId($partner_transaction_id)
    {
        $this->partner_transaction_id = $partner_transaction_id;
        $this->param_list['out_trade_no'] = $partner_transaction_id;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
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
     * @param mixed $total_fee
     */
    public function setTotalFee($total_fee)
    {
        $this->total_fee = $total_fee;
        $this->param_list['total_fee'] = $total_fee;
    }

    /**
     * @return mixed
     */
    public function getRmbFee()
    {
        return $this->rmb_fee;
    }

    /**
     * @param mixed $rmb_fee
     */
    public function setRmbFee($rmb_fee)
    {
        $this->rmb_fee = $rmb_fee;
        $this->param_list['rmb_fee'] = $rmb_fee;
    }

    /**
     * @return mixed
     */
    public function getOrderGmtCreate()
    {
        return $this->order_gmt_create;
    }

    /**
     * @param mixed $order_gmt_create
     */
    public function setOrderGmtCreate($order_gmt_create)
    {
        $this->order_gmt_create = $order_gmt_create;
        $this->param_list['order_gmt_create'] = $order_gmt_create;
    }

    /**
     * @return mixed
     */
    public function getOrderValidTime()
    {
        return $this->order_valid_time;
    }

    /**
     * @param mixed $order_valid_time
     */
    public function setOrderValidTime($order_valid_time)
    {
        $this->order_valid_time = $order_valid_time;
        $this->param_list['order_valid_time'] = $order_valid_time;
    }

    /**
     * @return mixed
     */
    public function getGoodsName()
    {
        return $this->goods_name;
    }

    /**
     * @param mixed $goods_name
     */
    public function setGoodsName($goods_name)
    {
        $this->goods_name = $goods_name;
        $this->param_list['subject'] = $goods_name;
    }

    /**
     * @return mixed
     */
    public function getGoodsDescription()
    {
        return $this->goods_description;
    }

    /**
     * @param mixed $goods_description
     */
    public function setGoodsDescription($goods_description)
    {
        $this->goods_description = $goods_description;
        $this->param_list['body'] = $goods_description;
    }

    /**
     * @return mixed
     */
    public function getParamList()
    {
        return $this->param_list;
    }
}
