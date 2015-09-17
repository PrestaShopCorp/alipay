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
 * Class RefundResponse
 * Extends Response
 * @see Response
 */
class RefundResponse extends Response
{
    /**
     * @var
     */
    private $errors;

    /**
     * @var
     */
    private $tplVars;

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function insertRefund($params)
    {
        return Db::getInstance()->insert('alipay_refund', array(
            'id_order'          => (int)$params['id_order'],
            'refund_no'         => pSQL($params['refund_no']),
            'out_trade_no'      => pSQL($params['out_trade_no']),
            'return_amount'     => pSQL($params['amount']),
            'currency'          => pSQL($params['currency']),
            'reason'            => pSQL($params['refund_reason']),
            'date_add'          => date('Y-m-d H:i:s')
        ));
    }

    /**
     * This method checks Alipay's response regarding the refund
     * @param bool|true $response
     * @param bool|true $params
     * @return int
     */
    public function processResponse($response = true, $params = true)
    {
        $alipay = new Alipay();
        if (!$response) {
            $this->errors[] = $alipay->l('An error occurred during the process.');
            return -1;
        }
        $xmlObj = new SimpleXMLElement($response);
        if ($xmlObj && $xmlObj->is_success == 'T') {
            $this->insertRefund($params);
        } elseif ($xmlObj && $xmlObj->is_success == 'F') {
            $this->errors[] = sprintf($alipay->l('An error occured during the process: %s'), $xmlObj->error);
            return -1;
        } else {
            $this->errors[] = $alipay->l('An error occurred during the process.');
            return -1;
        }
    }

    /**
     * @return mixed
     */
    public function getTplVars()
    {
        return $this->tplVars;
    }
}
