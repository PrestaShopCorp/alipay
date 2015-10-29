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
 * Class AlipayConfirmationModuleFrontController
 * Extends ModuleFrontController
 * @see ModuleFrontController
 */
class AlipayConfirmationModuleFrontController extends ModuleFrontController
{
    /**
     * This method is called when the customer goes back to shop, after he made a payment.
     * The customer will redirected either to the order confirmation page or to an error page
     * @return mixed
     */
    public function postProcess()
    {
        require_once(dirname(__FILE__).'/../../api/loader.php');
        $alipay = new Alipay();
        $payment_response = new PaymentResponse();
        $payment_response->getPostData();
        if ($payment_response->getTradeStatus() == 'TRADE_FINISHED') {
            $payment_response->setIdModule($this->module->id);
            if (!$payment_response->processResponse()) {
                $errors = $payment_response->getErrors();
                if ($errors) {
                    foreach ($errors as $error) {
                        $this->errors[] = $error;
                    }
                    return $this->setTemplate('error.tpl');
                }
            }
            if ($payment_response->getIdOrder()) {
                $params = array(
                    'id_cart' => $payment_response->getIdCart(),
                    'id_module' => $payment_response->getIdModule(),
                    'id_order' => $payment_response->getIdOrder(),
                    'key' => $payment_response->getSecureKey(),
                );
                $s = $this->context->link->getModuleLink('alipay', 'confirmation', $params);
                Tools::redirect($s);
            } else {
                $this->context->smarty->assign($payment_response->getTplVars());
                return $this->setTemplate('check_order.tpl');
            }
        }
        $this->errors[] = $alipay->l('An error occured.
        Please contact the merchant to have more informations');
        return $this->setTemplate('error.tpl');
    }
}
