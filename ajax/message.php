<?
include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();

$title = isset($request['message_title']) ? \Bitrix\Main\Text\Encoding::convertEncoding($request['message_title'], 'UTF-8', SITE_CHARSET) : 'Message';
$title_description = isset($request['message_title_description']) ? \Bitrix\Main\Text\Encoding::convertEncoding($request['message_title_description'], 'UTF-8', SITE_CHARSET) : '';
$content = isset($request['message_content']) ? \Bitrix\Main\Text\Encoding::convertEncoding($request['message_content'], 'UTF-8', SITE_CHARSET) : '';
$button_title = isset($request['message_button_title']) ? \Bitrix\Main\Text\Encoding::convertEncoding($request['message_button_title'], 'UTF-8', SITE_CHARSET) : '';
$button_class = isset($request['message_button_class']) ? \Bitrix\Main\Text\Encoding::convertEncoding($request['message_button_class'], 'UTF-8', SITE_CHARSET) : 'btn btn-default btn-lg btn-wide jqmClose';
?>
<div class="flexbox">
	<div class="form popup">
		<div class="form-header">
			<div class="text">
				<div class="title switcher-title font_24 color_222"><?=htmlspecialcharsbx($title)?></div>
				<?if (strlen($title_description)):?>
					<div class="form_desc font_16"><?=htmlspecialcharsbx($title_description)?></div>
				<?endif;?>
			</div>
		</div>

		<div class="form-body"><?=$content?></div>

		<?if (strlen($button_title)):?>
			<div class="form-footer">
				<div>
					<input type="submit" class="<?=htmlspecialcharsbx($button_class)?>" value="<?=htmlspecialcharsbx($button_title)?>">
				</div>
			</div>
		<?endif;?>
	</div>
</div>
