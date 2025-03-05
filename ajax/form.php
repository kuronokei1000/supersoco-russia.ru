<?
use Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\SystemException;

include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();

try {
	if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
		throw new SystemException('Error include solution constants');
	}
	
	if (!Loader::includeModule(VENDOR_MODULE_ID)) {
		throw new SystemException('Error include module '.VENDOR_MODULE_ID);
	}

	TSolution\Extensions::register();
	if (class_exists('TSolution\ExtensionsMobile')) {
		TSolution\ExtensionsMobile::register();
	}
	
	$APPLICATION->ShowAjaxHead();
	TSolution\Extensions::init(['validate', 'stars']);
	$arTheme = TSolution::GetFrontParametrsValues(SITE_ID);

	$form_id = $request['form_id'] ?? false;
	$type = $request['type'] ?? false;
	$id = $request['id'] ?? false;
	?>
	<span class="jqmClose top-close fill-theme-hover fill-use-svg-999" onclick="window.b24form = false;" title="<?=Loc::getMessage('CLOSE_BLOCK');?>"><?=TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH.'/images/svg/header_icons.svg#close-14-14', '', ['WIDTH' => 14,'HEIGHT' => 14]);?></span>
	<?
	if($form_id == 'fast_view'){
		@include('fast_view.php');
	}
	elseif($form_id == 'fast_view_sale'){
		@include('fast_view_sale.php');
	}
	elseif($form_id == 'marketing'){
		@include('marketing.php');
	}
	elseif($form_id == 'city_chooser'){
		@include('city_chooser.php');
	}
	elseif($form_id == 'share_basket'){
		@include('share_basket.php');
	}
	elseif($form_id == 'vote'){
		@include('vote.php');
	}
	elseif($form_id == 'replenishment'){
		@include('replenishment.php');
	}
	elseif($form_id == 'change_payment'){
		@include('change_payment.php');
	}
	elseif($form_id == 'subscribe'){
		@include('subscribe.php');
	}
	elseif($form_id == 'wizard_solution'){
		@include('wizard_solution.php');
	}
	elseif($form_id == 'message'){
		@include('message.php');
	}
	elseif($form_id === 'delivery'){
		@include('delivery.php');
	}
	elseif($form_id === 'stores'){
		@include('stores.php');
	}
	elseif($form_id == 'ocb'){
		@include('oneclickbuy.php');
	}
	elseif($form_id === 'include_block'){
		$include_block_content = isset($request['url']) && $request['url'] ? $_SERVER['DOCUMENT_ROOT'] . $request['url'] : '';
		$include_block_title = htmlspecialcharsbx($request['block_title'] ?? '');

		TSolution\Extensions::init(['bootstrap.lite', 'tabs']);
	?>
		<?if(
			$include_block_content &&
			strpos(realpath($include_block_content), $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include') === 0 &&
			file_exists($include_block_content)
		):?>
			<div class="form popup">
				<?if($include_block_title):?>
					<div class="form-header">
						<h2 class="line-block--gap"><?=$include_block_title;?></h2>
					</div>
				<?endif;?>
				<div class="form-body">
					<?include $include_block_content;?>
				</div>
			</div>
		<?else:?>
			<?throw new SystemException(Loc::getMessage('ERROR_ID_FORM'));?>
		<?endif;?>
	<?
	}
	elseif($type == 'review'){
		@include('review.php');
	}
	elseif($type == 'auth'){
		@include('auth.php');
	}
	elseif($type == 'subscribe'){
		@include('subscribe_news.php');
	}
	else {
		if(!$id){
			throw new SystemException(Loc::getMessage('ERROR_ID_FORM'));
		}

		$isCallBack = $id == TSolution::getFormID(VENDOR_PARTNER_NAME.'_'.VENDOR_SOLUTION_NAME.'_callback');
		$isOCB = $id == TSolution::getFormID(VENDOR_PARTNER_NAME.'_'.VENDOR_SOLUTION_NAME.'_quick_buy');
		$successMessage = ($isCallBack ? '<p>Наш менеджер перезвонит вам в ближайшее время.</p>' : ($isOCB ? '<p>Наш менеджер свяжется с вами в ближайшее время.</p>' : 'Ваше сообщение отправлено!'));
		?>
		<?$APPLICATION->IncludeComponent(
			"aspro:form.lite", "popup",
			Array(
				"IBLOCK_TYPE" => "aspro_lite_form",
				"IBLOCK_ID" => $id,
				"AJAX_MODE" => "Y",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "N",
				"AJAX_OPTION_HISTORY" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "100000",
				"AJAX_OPTION_ADDITIONAL" => "",
				"SUCCESS_MESSAGE" => $successMessage,
				"SEND_BUTTON_NAME" => "Отправить",
				"SEND_BUTTON_CLASS" => "btn btn-default",
				"DISPLAY_CLOSE_BUTTON" => "Y",
				"POPUP" => "Y",
				"CLOSE_BUTTON_NAME" => "Закрыть",
				"CLOSE_BUTTON_CLASS" => "jqmClose btn btn-default bottom-close"
			)
		);?>
		<?
	}
}
catch(SystemException $e) {
	?>
	<div style="padding: 35px 32px 15px 32px;">
		<div class="alert alert-danger"><?=$e->getMessage()?></div>
	</div>
	<?
}
