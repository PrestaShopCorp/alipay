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

class AlipayTools
{
    /**
     * This method returns an array with the basic protocol parameters.
     * @see AlipayApi::__construct()
     * @param $service
     * @param bool|false $default_config
     * @return array
     */
    public static function getCredentials($service, $default_config = false)
    {
        $credentials = array();
        $credentials['partner_id'] = (
            isset($default_config['partner_id']) ?
            $default_config['partner_id']:
            Configuration::get('ALIPAY_PARTNER_ID')
        );
        $credentials['service'] = $service;
        $credentials['secrete_key'] = (
            isset($default_config['secrete_key']) ?
            $default_config['secrete_key']:
            Configuration::get('ALIPAY_SECRETE_KEY')
        );
        $mode = Configuration::get('ALIPAY_LIVE_MODE');
        if ($mode == 1) {
            $credentials['gateway'] = Configuration::get('ALIPAY_GATEWAY_PROD');
        } else {
            $credentials['gateway'] = Configuration::get('ALIPAY_GATEWAY');
        }
        return $credentials;
    }

    /**
     * This method returns a bool, whether or not the module is configured correctly
     * @return bool
     */
    public static function isAlipayConfigured()
    {
        $partner_id = Configuration::get('ALIPAY_PARTNER_ID');
        $secrete_key = Configuration::get('ALIPAY_SECRETE_KEY');
        if ($partner_id && $secrete_key) {
            return true;
        }
        return false;
    }

    /**
     * Create needed tables during the installation if they not exist
     * @return mixed
     */
    public static function createDb()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."alipay` (
                    `id_order` INT(11) NOT NULL,
                    `out_trade_no` VARCHAR(50) NOT NULL,
                    `trade_no` VARCHAR(50) NOT NULL,
                    `trade_status` VARCHAR(50) NOT NULL,
                    `currency` VARCHAR(3) NOT NULL,
                    `total_fee` FLOAT NOT NULL,
                    PRIMARY KEY (`id_order`)
                )
                COLLATE='latin1_swedish_ci'
                ENGINE=InnoDB;
                ";
        $res = Db::getInstance()->execute($sql);
        $sql = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."alipay_notification` (
                    `id_notification` INT(11) NOT NULL AUTO_INCREMENT,
                    `notify_type` VARCHAR(50) NULL DEFAULT NULL,
                    `notify_id` VARCHAR(50) NOT NULL,
                    `notify_date` DATETIME NULL DEFAULT NULL,
                    `sign` VARCHAR(50) NULL DEFAULT NULL,
                    `sign_type` ENUM('md5','dsa') NULL DEFAULT NULL,
                    `partner_transaction_id` VARCHAR(50) NULL DEFAULT NULL,
                    `alipay_transaction_id` VARCHAR(50) NULL DEFAULT NULL,
                    PRIMARY KEY (`notify_id`),
                    INDEX `id_notification` (`id_notification`)
                )
                COLLATE='latin1_swedish_ci'
                ENGINE=InnoDB
                AUTO_INCREMENT=37
                ;";
        $res &= Db::getInstance()->execute($sql);
        $sql = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."alipay_refund` (
                    `id_order` INT(11) NOT NULL,
                    `refund_no` VARCHAR(50) NOT NULL,
                    `out_trade_no` VARCHAR(50) NULL DEFAULT NULL,
                    `return_amount` FLOAT NULL DEFAULT NULL,
                    `currency` VARCHAR(50) NULL DEFAULT NULL,
                    `reason` VARCHAR(100) NULL DEFAULT NULL,
                    `date_add` DATETIME NULL DEFAULT NULL,
                    PRIMARY KEY (`id_order`, `refund_no`)
                )
                COLLATE='latin1_swedish_ci'
                ENGINE=InnoDB
                ;
                ";
        $res &= Db::getInstance()->execute($sql);
        return $res;
    }
}
