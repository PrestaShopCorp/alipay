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
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

var date = new Date();
var d = date.getDate();
var m = date.getMonth();
var y = date.getFullYear();
var bdate= new Date(y, m, d-10);
var edate= new Date(y, m, d-1);
$(document).ready(function(){
    if ($('input[name=ALIPAY_LIVE_MODE]').attr('checked') !== 'checked') {
        $('#ALIPAY_PARTNER_ID').prop('readonly', true);
        $('#ALIPAY_SECRETE_KEY').prop('readonly', true);
    }

    $('input[name=ALIPAY_LIVE_MODE]').change(function(){
        if ($('input[name=ALIPAY_LIVE_MODE]').attr('checked') !== 'checked') {
            $('#ALIPAY_PARTNER_ID').val('2088101122136241');
            $('#ALIPAY_SECRETE_KEY').val('760bdzec6y9goq7ctyx96ezkz78287de');
            $('#ALIPAY_PARTNER_ID').prop('readonly', true);
            $('#ALIPAY_SECRETE_KEY').prop('readonly', true);
        } else {
            $('#ALIPAY_PARTNER_ID').val('');
            $('#ALIPAY_SECRETE_KEY').val('');
            $('#ALIPAY_PARTNER_ID').prop('readonly', false);
            $('#ALIPAY_SECRETE_KEY').prop('readonly', false);
        }
    });
    $('.from_settlement').datepicker({
        prevText: '',
        nextText: '',
        minDate: bdate,
        maxDate: edate,
        dateFormat: 'yy-mm-dd'
    });
    $('.to_settlement').datepicker({
        prevText: '',
        nextText: '',
        minDate: bdate,
        maxDate: edate,
        dateFormat: 'yy-mm-dd'
    });
    $('.from_reconciliation').datepicker({
        prevText: '',
        nextText: '',
        minDate: bdate,
        maxDate: edate,
        dateFormat: 'yy-mm-dd'
    });
    $('.to_reconciliation').datepicker({
        prevText: '',
        nextText: '',
        minDate: bdate,
        maxDate: edate,
        dateFormat: 'yy-mm-dd'
    });
});
