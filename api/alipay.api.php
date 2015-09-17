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
 * Class AlipayApi
 */
class AlipayApi
{
    /**
     * @var
     */
    public $params;

    /**
     * @var
     */
    private $partner_id;
    /**
     * @var
     */
    private $service;
    /**
     * @var
     */
    private $charset;
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
    private $return_url;
    /**
     * @var
     */
    private $notify_url;

    /**
     * @var
     */
    private $protocol_params;

    /**
     * @var
     */
    private $secrete_key;

    /**
     * @var
     */
    private $gateway;
    /**
     * @param $credentials
     */
    public function __construct($credentials)
    {
        $this->partner_id = $credentials['partner_id'];
        $this->service = $credentials['service'];
        $this->secrete_key = $credentials['secrete_key'];
        $this->gateway = $credentials['gateway'];
        if ($credentials['partner_id']) {
            $this->protocol_params['partner'] = $this->partner_id;
        }
        if ($credentials['service']) {
            $this->protocol_params['service'] = $this->service;
        }
    }

    /**
     * @return mixed
     */
    public function getPartnerId()
    {
        return $this->partner_id;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
        $this->protocol_params['service'] = $service;
    }

    /**
     * @return mixed
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param mixed $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        $this->protocol_params['_input_charset'] = $charset;
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
    public function getReturnUrl()
    {
        return $this->return_url;
    }

    /**
     * @param mixed $return_url
     */
    public function setReturnUrl($return_url)
    {
        $this->return_url = $return_url;
        $this->protocol_params['return_url'] = $return_url;
    }

    /**
     * @return mixed
     */
    public function getNotifyUrl()
    {
        return $this->notify_url;
    }

    /**
     * @param mixed $notify_url
     */
    public function setNotifyUrl($notify_url)
    {
        $this->notify_url = $notify_url;
        $this->protocol_params['notify_url'] = $notify_url;
    }

    /**
     * @return mixed
     */
    public function getProtocolParams()
    {
        if ($this->protocol_params && is_array($this->protocol_params) && !empty($this->protocol_params)) {
            return $this->protocol_params;
        }
        return array();
    }

    /**
     * @param mixed $protocol_params
     */
    public function setProtocolParams($protocol_params)
    {
        $this->protocol_params = $protocol_params;
    }

    /**
     * @return mixed
     */
    public function getSecreteKey()
    {
        return $this->secrete_key;
    }

    /**
     * @param mixed $secrete_key
     */
    public function setSecreteKey($secrete_key)
    {
        $this->secrete_key = $secrete_key;
    }

    /**
     * @param $params
     * @return string|void
     */
    public function getPreSignedString($params)
    {
        if (empty($params)) {
            return null;
        }
        $arg = '';
        while (list($key, $val) = each($params)) {
            $arg .= $key."=".$val."&";
        }
        $arg = Tools::substr($arg, 0, count($arg)-2);
        if (get_magic_quotes_gpc()) {
            $arg = Tools::stripslashes($arg);
        }
        return md5($arg.$this->getSecreteKey());
    }

    /**
     * @param $params
     * @return mixed
     */
    public function paramSort($params)
    {
        $ret_params = $params;
        ksort($ret_params);
        reset($ret_params);
        return $ret_params;
    }


    /**
     * @param Request $request
     * @param bool $sign
     */
    public function prepareRequest(Request $request, $sign = true)
    {
        $this->params = array_merge($request->getParamList(), $this->getProtocolParams());
        $this->params = $this->paramSort($this->params);
        if ($sign == true) {
            $this->setSign($this->getPreSignedString($this->params));
            $this->params['sign'] = $this->getSign();
            $this->params['sign_type'] = 'MD5';
        }
    }

    /**
     * @param Response $response
     * @return mixed
     */
    public function getResponseSign(Response $response)
    {
        $this->params = array_merge($response->getParamList(), $this->getProtocolParams());
        $this->params = $this->paramSort($this->params);
        $this->setSign($this->getPreSignedString($this->params));
        return $this->sign;
    }

    /**
     * @param $params
     * @return string|void
     */
    public function getPaymentUrlParam($params)
    {
        if (empty($params)) {
            return null;
        }
        $arg = '';
        while (list($key, $val) = each($params)) {
            $arg .= $key."=".urlencode($val)."&";
        }
        $arg = Tools::substr($arg, 0, count($arg)-2);
        if (get_magic_quotes_gpc()) {
            $arg = Tools::stripslashes($arg);
        }
        return $arg;
    }

    /**
     * @param bool $gateway
     * @return string
     */
    public function createUrl($gateway = false)
    {
        $params_url = $this->getPaymentUrlParam($this->params);
        if ($gateway == false) {
            $url = $this->gateway.$params_url;
        } else {
            $url = $gateway . $params_url;
        }
        return $url;
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getResponse($path)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }
}
