<?
include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
	die();
}

$iblockId = isset($_REQUEST['iblock_id']) ? intval($_REQUEST['iblock_id']) : false;
$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : false;
?>
<?if(
	$iblockId > 0 && 
	$id > 0 &&
	\Bitrix\Main\Loader::includeModule(VENDOR_MODULE_ID)
):?>
	<?
	global $APPLICATION, $arRegion, $arTheme;
	$arRegion = TSolution\Regionality::getCurrentRegion();
	$arTheme = TSolution::GetFrontParametrsValues(SITE_ID);
	$url = htmlspecialcharsbx(urldecode($_GET['item_href']));
	?>
	<script data-skip-moving="true">
		var objUrl = parseUrlQuery(),
			add_url = '<?=(strpos($url, '?') !== false ? '&' : '?')?>FAST_VIEW=Y';

		if('clear_cache' in objUrl){
			if(objUrl.clear_cache == 'Y'){
				add_url += '&clear_cache=Y';
			}
		}

		if(!$('.fast_view_frame .form.sending').length){
			$('.fast_view_frame').addClass('loading-state');
		}

		BX.ajax({
			url: <?var_export($url)?>+add_url,
			method: 'POST',
			data: BX.ajax.prepareData({'FAST_VIEW':'Y'}),
			dataType: 'html',
			processData: true,
			start: true,
			headers: [{'name': 'X-Requested-With', 'value': 'XMLHttpRequest'}],
			onfailure: function(data) {
				alert('Error connecting server');
			},
			onsuccess: function(html){
				var ob = BX.processHTML(html);
				BX.ajax.processScripts(ob.SCRIPT);

				// wait while not loaded template`s css
				var timer = setInterval(function(){
					if($('.fast_view_frame.popup .jqmClose.top-close').css('z-index') == 2){
						clearInterval(timer);
						BX('fast_view_item').innerHTML = ob.HTML;

						$('#fast_view_item').closest('.form').addClass('init');
						$('.fast_view_frame').removeClass('loading-state');

						initCountdown();

						if (typeof window.JItemAction === 'function') {
							JItemActionBasket.markBadges();
							JItemActionBasket.markItems();

							JItemActionCompare.markBadges();
							JItemActionCompare.markItems();

							JItemActionFavorite.markBadges();
							JItemActionFavorite.markItems();

							JItemActionSubscribe.markItems();
						}

						InitFancyBox();
						InitFancyBoxVideo();

						if (typeof showItemStoresAmount === 'function') {
							showItemStoresAmount()
						}
						
						if (typeof initSwiperSlider === 'function') {
							initSwiperSlider()
						}

						$('#fast_view_item .animate-load').click(function(){
							$(this).parent().addClass('loadings');
						});

						$('#fast_view_item .counter_block input[type=text]').numeric({allow:"."});

						$('.navigation-wrapper-fast-view .fast-view-nav').removeClass('noAjax');

					}
				}, 100);
			}
		});

		$(document).on('click', '.jqmClose', function(e){
			e.preventDefault();
			$(this).closest('.jqmWindow').jqmHide();
		});
	</script>
	<div id="fast_view_item"></div>
<?endif;?>
