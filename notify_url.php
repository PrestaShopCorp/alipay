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
 * This is the Notify URL file, called by Alipay system
 *
 * First, we need to verify the genuineness of the notification by calling the Alipay "notify_verify" service
 * Then we performs some tests with local values
 * Finally, after we made the proper actions, we need to answer to Alipay with the word "success" or "fail"
 * @see AlipayNotify
 */

require_once('../../config/config.inc.php');
require_once(dirname(__FILE__).'/alipay.php');
require_once(dirname(__FILE__).'/api/loader.php');

$alipay_notify = new AlipayNotify();
$alipay_notify->getPostData();

switch ($alipay_notify->getNotifyType()) {
    case "trade_status_sync":
        $default_config = array(
            'secrete_key'   => false
        );
        $service = Configuration::get('ALIPAY_SERVICE_NOTIFY_VERIFY');
        $credentials = AlipayTools::getCredentials($service, $default_config);
        $alipayapi = new AlipayApi($credentials);
        $alipay_notify->setParamList('notify_verify');
        $alipayapi->prepareRequest($alipay_notify, false);
        $url = $alipayapi->createUrl();
        $alipay_notify->setParamList('compare_sign');
        $params = $alipayapi->getProtocolParams();
        unset($params['partner']);
        unset($params['service']);
        $alipayapi->setProtocolParams($params);
        $alipayapi->setSecreteKey(Configuration::get('ALIPAY_SECRETE_KEY'));
        $alipayapi->prepareRequest($alipay_notify);
        if ($alipayapi->getResponse($url) != 'true' ||
            !$alipay_notify->verifyDupplicates() ||
            $alipayapi->getSign() != Tools::getValue('sign')
        ) {
            return;
        }
        if ($alipay_notify->saveNotify()) {
            $alipay = new Alipay();
            $extra_vars = array();
            $extra_vars['transaction_id'] = $alipay_notify->getTradeNo();
            $alipay->validateOrder(
                (int)$alipay_notify->getIdCart(),
                Configuration::get('PS_OS_PAYMENT'),
                $alipay_notify->getTotalFee(),
                'Alipay',
                null,
                $extra_vars,
                null,
                false,
                $alipay_notify->getSecureKey()
            );
            $id_order = (int)Order::getOrderByCartId($alipay_notify->getIdCart());
            if ($id_order) {
                $alipay_notify->saveOrder($id_order);
                header('HTTP/1.1 200 OK');
                echo "success";
                exit;
            }
        }
        header('HTTP/1.1 200 OK');
        echo "fail";
        exit;
    default:
        break;
}
