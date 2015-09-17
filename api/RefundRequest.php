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
 * Class RefundRequest
 * Extends Request
 * @see Request
 */
class RefundRequest extends Request
{
    /**
     * @var
     */
    private $out_return_no;

    /**
     * @var
     */
    private $out_trade_no;

    /**
     * @var
     */
    private $return_amount;

    /**
     * @var
     */
    private $currency;

    /**
     * @var
     */
    private $gmt_return;

    /**
     * @var
     */
    private $reason;

    /**
     * @var
     */
    private $return_rmb_amount;

    /**
     * @var
     */
    private $param_list;

    /**
     * @return mixed
     */
    public function getOutReturnNo()
    {
        return $this->out_return_no;
    }

    /**
     * @param $out_return_no
     */
    public function setOutReturnNo($out_return_no)
    {
        $this->out_return_no = $out_return_no;
        $this->param_list['out_return_no'] = $out_return_no;
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
    public function getReturnAmount()
    {
        return $this->return_amount;
    }

    /**
     * @param $return_amount
     */
    public function setReturnAmount($return_amount)
    {
        $this->return_amount = $return_amount;
        $this->param_list['return_amount'] = $return_amount;
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
    public function getGmtReturn()
    {
        return $this->gmt_return;
    }

    /**
     * @param $gmt_return
     */
    public function setGmtReturn($gmt_return)
    {
        $this->gmt_return = $gmt_return;
        $this->param_list['gmt_return'] = $gmt_return;
    }

    /**
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        $this->param_list['reason'] = $reason;
    }

    /**
     * @return mixed
     */
    public function getReturnRmbAmount()
    {
        return $this->return_rmb_amount;
    }

    /**
     * @param $return_rmb_amount
     */
    public function setReturnRmbAmount($return_rmb_amount)
    {
        $this->return_rmb_amount = $return_rmb_amount;
        $this->param_list['return_rmb_amount'] = $return_rmb_amount;
    }

    /**
     * @return mixed
     */
    public function getParamList()
    {
        return $this->param_list;
    }
}
