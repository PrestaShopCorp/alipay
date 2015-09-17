{*
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
*}


<div class="row">
	<div id="formAddPaymentPanel" class="panel">
        {if $prestashop_version_15 == 1}
            <fieldset>
                <legend>{l s='Alipay' mod=alipay}</legend>
        {else}
		<div class="panel-heading">
			<i class="icon-money"></i>
			{l s='Alipay' mod=alipay} <span class="badge">{displayPrice price=$transaction_details['total_fee']}</span>
		</div>
		<div class="clear">&nbsp;</div>
		<h4>{l s='Payments' mod=alipay}</h4>
        {/if}
		<form id="formAddPayment"  method="post" action="#&amp;vieworder&amp;token={$smarty.get.token|escape:'html':'UTF-8'}">
			<div class="table-responsive">
				<table class="table" cellpadding="0" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><span class="title_box ">{l s='Date' mod=alipay}</span></th>
							<th><span class="title_box ">{l s='Transaction ID' mod=alipay}</span></th>
							<th><span class="title_box ">{l s='Amount in ' mod=alipay}{$transaction_details['iso_code']|escape:'htmlall':'UTF-8'}</span></th>
							<th><span class="title_box ">{l s='Status' mod=alipay}</span></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{$transaction_details['gmt_payment']|escape:'html':'UTF-8'}</td>
							<td>{$transaction_details['trade_no']|escape:'html':'UTF-8'}</td>
							<td>{displayPrice price=$transaction_details['amount_in_currency']}</td>
							<td>{$transaction_details['trade_status']|escape:'html':'UTF-8'}</td>
							<td class="actions">
								<button class="button btn btn-default open_payment_information">
									<i class="icon-search"></i>
									{l s='Details' mod=alipay}
								</button>
							</td>
						</tr>
						<tr class="payment_information" style="display: none;">
							<td colspan="5">
								<p>
									<b>{l s='Amount in CNY' mod=alipay}</b>&nbsp;
										<i>{$transaction_details['total_fee']|escape:'htmlall':'UTF-8'} &yen;</i>
								</p>
								<p>
									<b>{l s='Description of the goods' mod=alipay}</b>&nbsp;
										<i>{$transaction_details['body']|escape:'html':'UTF-8'}</i>
								</p>
								<p>
									<b>{l s='Goods name' mod=alipay}</b>&nbsp;
										<i>{$transaction_details['subject']|escape:'html':'UTF-8'}</i>
								</p>
								<p>
									<b>{l s='Buyer email' mod=alipay}</b>&nbsp;
										<i>{$transaction_details['buyer_email']|escape:'html':'UTF-8'}</i>
								</p>
								<p>
									<b>{l s='Payment creation date' mod=alipay}</b>&nbsp;
										<i>{$transaction_details['gmt_create']|escape:'html':'UTF-8'}</i>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</form>
		<div class="clear">&nbsp;</div>
		<h4>{l s='Refund history' mod=alipay}</h4>
		<div class="row">
			<div class="col-lg-12">
				<div class="table-responsive">
					<table cellpadding="0" cellspacing="0" width="100%" class="table">
						<thead>
							<tr>
								<th><span class="title_box ">{l s='Refund date' mod=alipay}</span></th>
								<th><span class="title_box ">{l s='Refund No.' mod=alipay}</span></th>
								<th><span class="title_box ">{l s='Returned amount' mod=alipay}</span></th>
								<th><span class="title_box ">{l s='Reason' mod=alipay}</span></th>
								<th><span class="title_box "></span></th>
							</tr>
						</thead>
						<tbody>
							{foreach from=$refunds item=refund}
								<tr>
									<td>{$refund.date_add|escape:'htmlall':'UTF-8'}</td>
									<td>{$refund.refund_no|escape:'htmlall':'UTF-8'}</td>
									<td>{displayPrice price=$refund.return_amount}</td>
									<td>{$refund.reason|escape:'htmlall':'UTF-8'}</td>
									<td></td>
								</tr>
							{foreachelse}
								<tr>
									<td class="list-empty hidden-print" colspan="6">
										<div class="list-empty-msg">
											<i class="icon-warning-sign list-empty-icon"></i>
											{l s='No refunds have been made' mod=alipay}
										</div>
									</td>
								</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="clear">&nbsp;</div>
		<h4>{l s='Make a refund' mod=alipay}</h4>
		<div class="row">
			<div class="col-lg-12">
				<form id="formAddRefund" name="submitRefundForm" method="post" action="#&amp;vieworder&amp;token={$smarty.get.token|escape:'html':'UTF-8'}">
					<div class="table-responsive">
						<table class="table" cellpadding="0" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><span class="title_box ">{l s='Amount' mod=alipay}</span></th>
									<th><span class="title_box ">{l s='Reason for refund' mod=alipay}</span></th>
									<th><span class="title_box "></span></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
                                        <div class="input-group fixed-width-md">
                                            <input class="form-control fixed-width-sm pull-left" type="text" name="refund_amount" id="refund_amount" />
                                            <span class="input-group-addon">{$transaction_details.iso_code|escape:'htmlall':'UTF-8'}</span>
                                        </div>
									</td>
									<td>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="refund_reason" id="refund_reason" maxlength="100" />
                                        </div>
                                    </td>
 									<td>
                                        <div class="input-group">
                                            <button class="button btn btn-primary" type="submit" name="submitRefund">
                                                {l s='Refund' mod=alipay}
                                            </button>
                                        </div>
									</td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
        {if $prestashop_version_15 == 1}
            </fieldset>
        {/if}
    </div>
		</div>
	</div>
</div>