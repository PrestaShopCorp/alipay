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
 * Class TransactionInfoResponse
 * Extends Response
 * @see Response
 */
class TransactionInfoResponse extends Response
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
     * This method get useful payment details and assign them into the Order back-office template
     * @param bool|true $response
     * @param bool|true $params
     * @return int
     */
    public function processResponse($response = true, $params = true)
    {
        $alipay = new Alipay();
        if (!$response) {
            $this->errors[] = $alipay->l('Impossible to retrieve transaction information');
            return -1;
        }
        $xmlObj = new SimpleXMLElement($response);
        if ($xmlObj && $xmlObj->is_success == 'T') {
            $this->tplVars = get_object_vars($xmlObj->response->trade);
            $this->tplVars['currency'] = Currency::getIdByIsoCode($params['currency']);
            $this->tplVars['iso_code'] = $params['currency'];
            $this->tplVars['amount_in_currency'] = $params['total_fee'];
        } else {
            $this->errors[] = $alipay->l('Impossible to retrieve transaction information');
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
