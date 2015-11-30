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

<div class="panel">
	<div class="row alipay-header">
		<img src="{$module_dir|escape:'html':'UTF-8'}views/img/logo.png" class="col-xs-6 col-md-3 text-center" id="payment-logo" />
		<div class="col-xs-6 col-md-6 text-center text-muted">
            <h1>
                {l s='World Leading E-Payment Provider' mod='alipay'}<br />
                {l s='with 400 Million Active Users in China' mod='alipay'}
            </h1>
		</div>
		<div class="col-xs-12 col-md-3 text-center">
			<a href="http://global.alipay.com/product/websitepayment.html?from=prestashop" class="btn btn-primary" id="create-account-btn" target="_blank">{l s='Create an account' mod='alipay'}</a><br />
			{l s='Already have one?' mod='alipay'}<a href="https://globalprod.alipay.com/login/global.htm?from=prestashop" target="_blank"> {l s='Log in' mod='alipay'}</a>
		</div>
	</div>

	<div class="alipay-content">
		<div class="row padding-top">
			<div class="col-md-6">
				<h5>{l s='What is Alipay?' mod='alipay'}</h5>
                <p>
                    <strong>{l s='Alipay is an affiliate of Alibaba Group and the leading third-party online payment solution for China.' mod='alipay'}</strong>
                </p>
                <p class="justify">
                    {l s='The significantly growing interest by Chinese shoppers in Western brands create large opportunities for Western companies with 67 of Chinese luxury spending occurring outside  Chinese borders.' mod='alipay'}
                    {l s='If you are offering maternity products, food supplements, fashion as well as beauty and jewellery, Alipay will create an astonishing opportunity for you, opening up the Chinese market.' mod='alipay'}
                </p>
                <h5 class="margin-top">{l s='Why Alipay?' mod='alipay'}</h5>
                <p class="justify">
                    {l s='Alipay has evolved into the dominant e-payment provider in China, processing roughly 50 of the total online payment transactions.' mod='alipay'}
                    {l s='Major merchants around the world already rely on Alipay as a payment solution since it is featured with state of the art technology, risk control capability and furthermore offers a secure and convenient payment solution to our users.' mod='alipay'}
                </p>
                <dl class="list-square">
                    <dd class="justify">{l s='Alipay has 400 million active users' mod='alipay'}</dd>
                    <dd class="justify">{l s='A full range of funding channels are provided to our customers for speedy and successful transactions' mod='alipay'}</dd>
                    <dd class="justify">{l s='Alipay offers reconciliation file and settlement file' mod='alipay'}</dd>
                    <dd class="justify">{l s='We support 15 foreign currencies (GBP, HKD, USD, SGD, JPY, CAD, AUD, EUR, NZD, KRW, THB, CHF, SEK, DKK, NOK)' mod='alipay'}</dd>
                </dl>
                <img src="{$module_dir|escape:'html':'UTF-8'}views/img/alipay_pictos.png" class="pictos" />
			</div>
			
			<div class="col-md-6">
                <div class="video-container">
                    <iframe src="https://www.youtube-nocookie.com/embed/e3ejxKLY1I4" width="335" height="188" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div>
            </div>
		</div>
        <div class="row">
            <div class="{if $old_version != '1'}alert alert-info{/if} info">
                <h5>{l s='How to configure your Alipay module?' mod='alipay'}</h5>
                <p><strong>{l s='1. You do not have an Alipay Business Account:' mod='alipay'}</strong></p>
                <p class="justify padding-left">
                    {l s='Visit' mod='alipay'}<br />
                    <a class="alipay-website" href="http://global.alipay.com/product/websitepayment.html?from=prestashop" target="_blank">http://global.alipay.com/product/websitepayment.html</a><br />
                    {l s='to apply for an Alipay Business Account.' mod='alipay'}<br />
                    {l s='We will email you the login details in 2 days.' mod='alipay'}
                    {l s='In the meanwhile, use the sandbox login below to see how Alipay would look like in your front office.' mod='alipay'}
                </p>
                <p><strong>{l s='2. You already have an Alipay Business Account:' mod='alipay'}</strong></p>
                <p><a href="https://globalprod.alipay.com/login/global.htm?from=prestashop" target="_blank">{l s='Sign in' mod='alipay'}</a> {l s='and follow the instructions.' mod='alipay'}</p>
            </div>
        </div>
	</div>
</div>
