<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) { die(); } ?>

<? /* 

#ORDER_ID# - код заказа
#ORDER_REAL_ID# - реальный ID заказа
#ORDER_DATE# - дата заказа
#ORDER_USER# - заказчик
#PRICE# - сумма заказа
#EMAIL# - E-Mail заказчика
#ORDER_LIST# - состав заказа


<? EventMessageThemeCompiler::includeComponent(
    "upside:emailnotification",
    "neworder",
    [ 
        "ORDER_ID" =>"{#ORDER_ID#}",
        "ORDER_REAL_ID" =>"{#ORDER_REAL_ID#}",
        "ORDER_DATE" =>"{#ORDER_DATE#}",
        "ORDER_USER" =>"{#ORDER_USER#}",
        "PRICE" =>"{#PRICE#}",
        "EMAIL" =>"{#EMAIL#}",
        "ORDER_LIST" =>"{#ORDER_LIST#}"
    ]
); ?>

<?= $arParams["EMAIL"] ?>

*/ ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" style="background:#f3f3f3!important">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Order</title>
	<style>
	@media only screen {
		html {
			min-height: 100%;
			background: #fefefe
		}
	}
	
	@media only screen and (max-width:616px) {
		table.body img {
			width: auto;
			height: auto
		}
		table.body center {
			min-width: 0!important
		}
		table.body .container {
			width: 90%!important
		}
		table.menu {
			width: 100%!important
		}
		table.menu td,
		table.menu th {
			width: auto!important;
			display: inline-block!important
		}
		table.menu[align=center] {
			width: auto!important
		}
	}
	</style>
</head>


<body style="-moz-box-sizing:border-box;-ms-text-size-adjust:100%;-webkit-box-sizing:border-box;-webkit-text-size-adjust:100%;Margin:0;background:#f3f3f3!important;box-sizing:border-box;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;min-width:100%;padding:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;width:100%!important"><span class="preheader" style="color:#fefefe;display:none!important;font-size:1px;line-height:1px;max-height:0;max-width:0;mso-hide:all!important;opacity:0;overflow:hidden;visibility:hidden"></span>
	<table class="body" style="Margin:0;background:#f3f3f3!important;border-collapse:collapse;border-spacing:0;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;height:100%;line-height:1.3;margin:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;width:100%">
		<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
			<td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">
				<center style="min-width:600px;width:100%">
					<table align="center" class="container float-center" style="Margin:0 auto;background:'none';border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:center;vertical-align:top;width:600px">
						<tbody>
							<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
								<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">
									<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;width:100%">
										<tbody>
											<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
												<td height="25" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:25px;font-weight:400;hyphens:auto;line-height:25px;margin:0;mso-line-height-rule:exactly;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">&nbsp;</td>
											</tr>
										</tbody>
									</table>
									<center style="min-width:600px;width:100%"><img src="../assets/img/tervolina_logo.png" alt align="center" class="float-center" style="-ms-interpolation-mode:bicubic;Margin:0 auto;clear:both;display:block;float:none;margin:0 auto;max-width:100%;outline:0;text-align:center;text-decoration:none;width:auto">
										<table align="center" class="menu menu-header float-center" style="Margin:0 auto;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:center;vertical-align:top;width:auto">
											<tbody>
												<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
													<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">
														<table style="border-collapse:collapse;border-spacing:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
															<tbody>
																<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
																	<th class="menu-item float-center" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0 auto;border-collapse:collapse!important;color:#8F8E8E;float:none;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0 auto;padding:14px 5px!important;padding-bottom:0;padding-left:0;padding-right:10px;padding-top:0;text-align:center;vertical-align:top;word-wrap:break-word"><a href="https://tervolina.ru/catalog/zhenshchinam/?newOnly=1" style="color:#8F8E8E!important;font-family:Arial;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none;text-transform:uppercase">Новинки</a></th>
																	<th class="menu-item float-center" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0 auto;border-collapse:collapse!important;color:#8F8E8E;float:none;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0 auto;padding:14px 5px!important;padding-bottom:0;padding-left:0;padding-right:10px;padding-top:0;text-align:center;vertical-align:top;word-wrap:break-word"><a href="https://tervolina.ru/catalog/zhenshchinam/obuv_90/" style="color:#8F8E8E!important;font-family:Arial;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none;text-transform:uppercase">Обувь</a></th>
																	<th class="menu-item float-center" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0 auto;border-collapse:collapse!important;color:#8F8E8E;float:none;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0 auto;padding:14px 5px!important;padding-bottom:0;padding-left:0;padding-right:10px;padding-top:0;text-align:center;vertical-align:top;word-wrap:break-word"><a href="https://tervolina.ru/catalog/zhenshchinam/sumki_90/" style="color:#8F8E8E!important;font-family:Arial;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none;text-transform:uppercase">Сумки</a></th>
																	<th class="menu-item float-center" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0 auto;border-collapse:collapse!important;color:#8F8E8E;float:none;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0 auto;padding:14px 5px!important;padding-bottom:0;padding-left:0;padding-right:10px;padding-top:0;text-align:center;vertical-align:top;word-wrap:break-word"><a href="https://tervolina.ru/catalog/zhenshchinam/aksessuary_90/" style="color:#8F8E8E!important;font-family:Arial;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none;text-transform:uppercase">Аксессуары</a></th>
																	<th class="menu-item float-center" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0 auto;border-collapse:collapse!important;color:#8F8E8E;float:none;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0 auto;padding:14px 5px!important;padding-bottom:0;padding-left:0;padding-right:10px;padding-top:0;text-align:center;vertical-align:top;word-wrap:break-word"><a href="https://tervolina.ru/buyers/actions/" style="color:#8F8E8E!important;font-family:Arial;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none;text-transform:uppercase">Скидки %</a></th>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</center>
									<p class="hr" style="Margin:0;Margin-bottom:5px;background:#8F8E8E;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;height:1px;line-height:20px;margin:6px 0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left"></p>
									<table class="first-block" style="border-collapse:collapse;border-spacing:0;margin:23px 0 12px 0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
										<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
											<td class="order-id" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Arial;font-size:24px;font-style:normal;font-weight:700;hyphens:auto;line-height:19px;margin:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;width:100%;word-wrap:break-word">Заказ <?= $arParams["ORDER_ID"] ?></td>
											<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">
												<p class="order-status" style="Margin:0;Margin-bottom:5px;background:#A99E9E;border-radius:5px;color:#FFF;display:block;font-family:Arial;font-size:11px;font-style:normal;font-weight:400;line-height:9px;margin:0;margin-bottom:5px;margin-top:-3px;padding-bottom:7px;padding-left:10px;padding-right:10px;padding-top:7px;text-align:left">Оформлен</p>
											</td>
										</tr>
										<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
											<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word"></td>
										</tr>
									</table>
									<p style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">Доставка: <?= $arResult['DELIVERY_COST'] ?>. <a href="https://tervolina.ru/buyers/delivery/" class="red-link" style="color:#B25454!important;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;padding:0;text-align:left;text-decoration:underline">Условия доставки.</a></p>
									<p style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">Способ оплаты: <?= $arResult['PAY_METHOD'] ?></p>
									<p class="bold-title" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:700;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">Итого к оплате: <?= $arParams["PRICE"] ?></p>
									<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;width:100%">
										<tbody>
											<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
												<td height="16" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">&nbsp;</td>
											</tr>
										</tbody>
									</table>
									<p style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">После согласования деталей заказа вам поступит сообщение с подтверждением заказа.
										<br>Спасибо за выбор нашего интернет-магазина.</p>
									<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;width:100%">
										<tbody>
											<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
												<td height="6" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:6px;font-weight:400;hyphens:auto;line-height:6px;margin:0;mso-line-height-rule:exactly;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">&nbsp;</td>
											</tr>
										</tbody>
									</table>
									<p class="hr" style="Margin:0;Margin-bottom:5px;background:#8F8E8E;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;height:1px;line-height:20px;margin:6px 0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left"></p>
									<table class="two-block" width="100%" style="border-collapse:collapse;border-spacing:0;margin-top:20px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
										<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
											<td width="50%" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">
												<p class="title" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:12px;font-style:normal;font-weight:400;letter-spacing:.1em;line-height:18px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;text-transform:uppercase">Покупатель:</p>
												<p class="small" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:12px;font-style:normal;font-weight:400;line-height:18px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left"><?= $arParams["ORDER_USER"] ?></p>
												<? /* <p class="small" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:12px;font-style:normal;font-weight:400;line-height:18px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">Владимиров</p> */ ?>
											</td>
											<td width="50%" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">
												<p class="title" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:12px;font-style:normal;font-weight:400;letter-spacing:.1em;line-height:18px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;text-transform:uppercase">Контакты:</p>
												<? /* <p class="small" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:12px;font-style:normal;font-weight:400;line-height:18px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">+79037999999</p> */ ?>
												<p class="small" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:12px;font-style:normal;font-weight:400;line-height:18px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left"><?= $arParams["EMAIL"] ?></p>
											</td>
										</tr>
									</table>
									<table class="two-block" width="100%" style="border-collapse:collapse;border-spacing:0;margin-top:20px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
										<tr width="100%" style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
											<td width="100%" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">
												<p class="title" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:12px;font-style:normal;font-weight:400;letter-spacing:.1em;line-height:18px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;text-transform:uppercase">адрес доставки:</p>
												<p class="small" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:12px;font-style:normal;font-weight:400;line-height:18px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left"><?= $arResult['DELIVERY_ADRESS'] ?></p>
											</td>
										</tr>
									</table>

                                    <? /* Товары в корзине */ ?>
                                    <? foreach ($arResult["BASKET_ITEMS"] as $bItem) { ?>

                                        <table class="product-block" width="100%" style="background:#FAF6F6;border-collapse:collapse;border-spacing:0;margin-top:18px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
                                            <tr width="100%" style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
                                                <td width="60%" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding-bottom:20px;padding-left:20px;padding-right:20px;padding-top:20px;text-align:left;vertical-align:top;word-wrap:break-word">
                                                    <p class="bold-title" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:700;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left"><?= $bItem["NAME"] ?></p>
                                                    <ul class="list" style="font-size:12px;list-style:none;padding:0">
                                                        <? if ($bItem['QUANTITY']) { ?>
                                                            <li style="margin:5px 0">Количество: <?= $bItem['QUANTITY'] ?></li>
                                                        <? } ?>
                                                        <? if ($bItem['SIZE']) { ?>
                                                            <li style="margin:5px 0">Размер: <?= $bItem['SIZE'] ?></li>
                                                        <? } ?>
                                                        <? if ($bItem['COLOR']) { ?>
                                                            <li style="margin:5px 0">Цвет: <?= $bItem['COLOR'] ?></li>
                                                        <? } ?>
                                                        <? if ($bItem['ARTICLE']) { ?>                                   
                                                            <li style="margin:5px 0">Артикул: <?= $bItem['ARTICLE'] ?></li>
                                                        <? } ?>
                                                        
                                                        
                                                        
                                                    </ul>
                                                    <p class="price" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:700;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left"><?= $bItem["FINAL_PRICE"] ?> <? /* <span style="font-weight:400;text-decoration:line-through">14 935 руб.</span> */ ?></p>
                                                </td>
                                                <td width="40%" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding-bottom:20px;padding-left:20px;padding-right:20px;padding-top:20px;text-align:left;vertical-align:top;word-wrap:break-word">
                                                    <p class="text-right" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:right"><img src="../assets/img/product.png" alt style="-ms-interpolation-mode:bicubic;clear:both;display:block;margin-left:auto;max-width:100%;outline:0;text-decoration:none;width:auto"></p>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                    <? } ?>
									
									<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;width:100%">
										<tbody>
											<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
												<td height="18" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:18px;font-weight:400;hyphens:auto;line-height:18px;margin:0;mso-line-height-rule:exactly;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">&nbsp;</td>
											</tr>
										</tbody>
									</table>
									<p style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">Доставка: <?= $arResult['DELIVERY_COST'] ?>. <a href="https://tervolina.ru/buyers/delivery/" class="red-link" style="color:#B25454!important;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;padding:0;text-align:left;text-decoration:underline">Условия доставки.</a></p>
									<p style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">Способ оплаты: <?= $arResult['PAY_METHOD'] ?></p>
									<p class="bold-title" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:700;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">Итого к оплате:</p>
									<p class="big-price" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:24px;font-style:normal;font-weight:700;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left"><?= $arParams["PRICE"] ?></p>
									<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;width:100%">
										<tbody>
											<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
												<td height="6" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:6px;font-weight:400;hyphens:auto;line-height:6px;margin:0;mso-line-height-rule:exactly;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">&nbsp;</td>
											</tr>
										</tbody>
									</table>
									<p class="hr" style="Margin:0;Margin-bottom:5px;background:#8F8E8E;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;height:1px;line-height:20px;margin:6px 0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left"></p>
									<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;width:100%">
										<tbody>
											<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
												<td height="16" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">&nbsp;</td>
											</tr>
										</tbody>
									</table>
									<p style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">Посмотреть всю информацию по заказу, а также проверить статус отправления можно в <a href="https://tervolina.ru/" class="red-link" style="color:#B25454!important;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;padding:0;text-align:left;text-decoration:underline">личном кабинете</a></p>
									<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;width:100%">
										<tbody>
											<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
												<td height="16" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">&nbsp;</td>
											</tr>
										</tbody>
									</table>
									<p class="bold-title" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:700;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">Нужна помощь с заказом?</p>
									<p class="title" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:12px;font-style:normal;font-weight:400;letter-spacing:.1em;line-height:18px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;text-transform:uppercase">позвоните нам</p><a href="tel:88006003440" class="red-link" style="color:#B25454!important;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;padding:0;text-align:left;text-decoration:underline">8 800 600 34 40</a>
									<p style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">Бесплатно по России</p>
									<p style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;line-height:20px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">Служба поддержки клиентов с 8:00 до 20:00</p>
									<p class="title" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:12px;font-style:normal;font-weight:400;letter-spacing:.1em;line-height:18px;margin:0;margin-bottom:5px;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;text-transform:uppercase">Напишите нам</p><a href="#" class="red-link" style="color:#B25454!important;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;padding:0;text-align:left;text-decoration:underline">info@tervolina.ru</a>
									<table class="spacer" style="border-collapse:collapse;border-spacing:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;width:100%">
										<tbody>
											<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
												<td height="26" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:26px;font-weight:400;hyphens:auto;line-height:26px;margin:0;mso-line-height-rule:exactly;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">&nbsp;</td>
											</tr>
										</tbody>
									</table>
									<table class="menu menu-header" style="border-collapse:collapse;border-spacing:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;width:600px">
										<tbody>
											<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
												<td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#8F8E8E;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top;word-wrap:break-word">
													<table style="border-collapse:collapse;border-spacing:0;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
														<tbody>
															<tr style="padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left;vertical-align:top">
																<th class="menu-item float-center" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0 auto;border-collapse:collapse!important;color:#8F8E8E;float:none;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0 auto;padding:14px 5px!important;padding-bottom:0;padding-left:0!important;padding-right:10px;padding-top:0;text-align:center;vertical-align:top;word-wrap:break-word">
																	<a href="https://www.instagram.com/tervolinaofficial/" style="color:#8F8E8E!important;font-family:Arial;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none;text-transform:uppercase">
																		<svg width="30" height="30" viewbox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path d="M8.71359 2.24997H21.8473C25.0599 2.24997 27.6642 4.85427 27.6642 8.06683V21.2006C27.6642 24.4131 25.0599 27.0174 21.8473 27.0174H8.71359C5.50103 27.0174 2.89673 24.4131 2.89673 21.2006V8.06683C2.89673 4.85427 5.50103 2.24997 8.71359 2.24997Z" stroke="#958B85" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
																			<path d="M20.1661 13.9091C20.3185 14.9368 20.1429 15.9863 19.6645 16.9085C19.186 17.8306 18.4289 18.5784 17.5009 19.0455C16.573 19.5125 15.5214 19.6751 14.4957 19.5101C13.47 19.345 12.5224 18.8607 11.7878 18.1261C11.0532 17.3915 10.569 16.444 10.4039 15.4183C10.2389 14.3926 10.4014 13.341 10.8685 12.413C11.3356 11.4851 12.0834 10.728 13.0055 10.2495C13.9277 9.77103 14.9772 9.5955 16.0048 9.74789C17.0531 9.90333 18.0235 10.3918 18.7729 11.1411C19.5222 11.8904 20.0106 12.8609 20.1661 13.9091Z" stroke="#958B85" stroke-linecap="round" stroke-linejoin="round" />
																			<path d="M23.4693 7.60927C23.5119 7.89692 23.4628 8.19069 23.3289 8.4488C23.1949 8.70691 22.983 8.91622 22.7233 9.04696C22.4635 9.17769 22.1692 9.2232 21.8821 9.177C21.595 9.1308 21.3298 8.99525 21.1242 8.78963C20.9185 8.58401 20.783 8.3188 20.7368 8.0317C20.6906 7.7446 20.7361 7.45025 20.8668 7.19051C20.9976 6.93076 21.2069 6.71886 21.465 6.58493C21.7231 6.451 22.0169 6.40187 22.3045 6.44452C22.5979 6.48803 22.8696 6.62475 23.0793 6.83449C23.289 7.04423 23.4258 7.31587 23.4693 7.60927Z" fill="#958B85" />
																		</svg>
																	</a>
																</th>
																<th class="menu-item float-center" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0 auto;border-collapse:collapse!important;color:#8F8E8E;float:none;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0 auto;padding:14px 5px!important;padding-bottom:0;padding-left:0;padding-right:10px;padding-top:0;text-align:center;vertical-align:top;word-wrap:break-word">
																	<a href="https://vk.com/club46207567" style="color:#8F8E8E!important;font-family:Arial;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none;text-transform:uppercase">
																		<svg width="30" height="30" viewbox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path d="M15.1494 18.6669H15.8793C15.8793 18.6669 16.0996 18.6411 16.2123 18.5124C16.3159 18.3941 16.3126 18.172 16.3126 18.172C16.3126 18.172 16.2983 17.1324 16.7528 16.9793C17.2009 16.8284 17.7762 17.9841 18.386 18.4285C18.8471 18.7647 19.1976 18.6911 19.1976 18.6911L20.8282 18.6669C20.8282 18.6669 21.6811 18.611 21.2767 17.899C21.2436 17.8409 21.041 17.3724 20.0642 16.4098C19.0417 15.4023 19.1788 15.5653 20.4104 13.8226C21.1605 12.7613 21.4603 12.1134 21.3666 11.8359C21.2773 11.5715 20.7256 11.6413 20.7256 11.6413L18.8897 11.6534C18.8897 11.6534 18.7535 11.6337 18.6526 11.6978C18.5539 11.7605 18.4906 11.9069 18.4906 11.9069C18.4906 11.9069 18.1999 12.7281 17.8125 13.4266C16.995 14.9004 16.668 14.9784 16.5344 14.8867C16.2235 14.6734 16.3012 14.03 16.3012 13.5728C16.3012 12.1445 16.5052 11.549 15.9038 11.3948C15.7043 11.3437 15.5573 11.3099 15.0469 11.3044C14.3918 11.2973 13.8374 11.3065 13.5234 11.4698C13.3146 11.5784 13.1534 11.8203 13.2516 11.8342C13.373 11.8514 13.6477 11.913 13.7934 12.1234C13.9816 12.3952 13.975 13.0054 13.975 13.0054C13.975 13.0054 14.0831 14.6867 13.7225 14.8955C13.4751 15.0387 13.1356 14.7463 12.4067 13.4092C12.0333 12.7244 11.7513 11.9673 11.7513 11.9673C11.7513 11.9673 11.697 11.8258 11.6 11.7501C11.4824 11.6583 11.318 11.6292 11.318 11.6292L9.57333 11.6413C9.57333 11.6413 9.31147 11.6491 9.21526 11.77C9.12966 11.8776 9.20842 12.1 9.20842 12.1C9.20842 12.1 10.5742 15.4925 12.1209 17.2021C13.5392 18.7698 15.1494 18.6669 15.1494 18.6669Z" fill="#958B85" />
																			<path d="M8.71359 2.25H21.8473C25.0599 2.25 27.6642 4.8543 27.6642 8.06686V21.2006C27.6642 24.4131 25.0599 27.0174 21.8473 27.0174H8.71359C5.50103 27.0174 2.89673 24.4131 2.89673 21.2006V8.06686C2.89673 4.8543 5.50103 2.25 8.71359 2.25Z" stroke="#958B85" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
																		</svg>
																	</a>
																</th>
																<th class="menu-item float-center" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0 auto;border-collapse:collapse!important;color:#8F8E8E;float:none;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0 auto;padding:14px 5px!important;padding-bottom:0;padding-left:0;padding-right:0!important;padding-top:0;text-align:center;vertical-align:top;word-wrap:break-word">
																	<a href="https://www.facebook.com/tervolinaofficial/" style="color:#8F8E8E!important;font-family:Arial;font-size:12px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none;text-transform:uppercase">
																		<svg width="30" height="30" viewbox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
																			<path d="M17.6668 9.40204H16.2123C15.5693 9.40204 14.9527 9.683 14.498 10.1831C14.0434 10.6832 13.788 11.3615 13.788 12.0688V13.1355H12.3334V15.2688H13.788V20.069H15.7274V15.2688H17.182L17.6668 13.1355H15.7274V12.0688C15.7274 11.9273 15.7785 11.7917 15.8694 11.6916C15.9603 11.5916 16.0837 11.5354 16.2123 11.5354H17.6668V9.40204Z" fill="#958B85" />
																			<path d="M8.71359 2.25H21.8473C25.0599 2.25 27.6642 4.8543 27.6642 8.06686V21.2006C27.6642 24.4131 25.0599 27.0174 21.8473 27.0174H8.71359C5.50103 27.0174 2.89673 24.4131 2.89673 21.2006V8.06686C2.89673 4.8543 5.50103 2.25 8.71359 2.25Z" stroke="#958B85" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
																		</svg>
																	</a>
																</th>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
									<p class="hidden" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;line-height:20px;margin:0;margin-bottom:5px;opacity:.6;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">Это письмо было отправлено на email test@test.com
										<br>Чтобы не пропустить ни одного письма от нас, добавьте адрес aaa@tervolina.ru в адресную книгу.</p>
									<p class="hidden" style="Margin:0;Margin-bottom:5px;color:#8F8E8E;font-family:Arial;font-size:14px;font-style:normal;font-weight:400;line-height:20px;margin:0;margin-bottom:5px;opacity:.6;padding-bottom:0;padding-left:0;padding-right:0;padding-top:0;text-align:left">Отписаться от рассылки</p>
								</td>
							</tr>
						</tbody>
					</table>
				</center>
			</td>
		</tr>
	</table>
	<!-- prevent Gmail on iOS font size manipulation -->
	<div style="display:none;white-space:nowrap;font:15px courier;line-height:0">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</div>
</body>


</html>