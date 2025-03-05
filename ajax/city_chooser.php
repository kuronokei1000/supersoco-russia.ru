<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php');

global $APPLICATION, $arRegion;

$arThemePopup = TSolution::GetFrontParametrsValues(SITE_ID);
$url = '';


if(isset($_GET['url']) && $_GET['url'])
	$url = htmlspecialchars($_GET['url']);?>

<div class="form popup popup--regions">
<div class="wrap">
	<div class="form-header">
		<div class="text">
			<div class="title font_24 color_333 font_weight--500"><?=\Bitrix\Main\Localization\Loc::getMessage('CITY_CHOISE');?></div>
		</div>
	</div>
	<?
	$template = strtolower($arThemePopup["REGIONALITY_VIEW"]);
	?>
	<?if($template == "select"):?>
		<?$template = "popup_regions_small";?>
	<?endif;?>
	<?$arUrl = $APPLICATION->IncludeComponent(
		"aspro:regionality.list.lite",
		$template,
		Array(
			"URL" => $url,
			"POPUP" => "Y",
			"FORM_TYPE" => $arThemePopup["FORM_TYPE"],
		)
	);
	?>

	<script type="text/javascript">
		if($('.popup_regions .dropdown').length)
		{
			$(window).resize(function(){
				var _this = $('.popup_regions .dropdown:visible'),
					dropdownOffset = 65,
					positionTop = 0;
				if(_this.length)
				{
					positionTop = _this.closest('.items_block').position().top;
					if(positionTop+_this.find('.wrap .inner-wrap').actual('outerHeight')+dropdownOffset > $('.form.popup > .wrap > div').height())
						_this.addClass('to-top');
					else
						_this.removeClass('to-top');
				}
			})
		}

		$('.js-region').on('click', function(){
			var _this = $(this),
				positionTop = _this.parent().position().top,
				dropdownOffset = 65;

			$('.popup_regions .dropdown').fadeOut(100);
			var dropdown = _this.siblings('.dropdown');

			if(positionTop+dropdown.find('.wrap .inner-wrap').actual('outerHeight')+dropdownOffset > $('.form.popup > .wrap > div').height())
				dropdown.addClass('to-top');
			else
				dropdown.removeClass('to-top');

			if(dropdown.is(':visible'))
				dropdown.fadeOut(100);
			else
				dropdown.fadeIn(100);
		})

		/* close search block */
		$("html, body").on('mousedown', function(e){
			e.stopPropagation();
			if(!$(e.target).hasClass('dropdown'))
				$('.popup_regions .dropdown').fadeOut(100);
		});
		$('.items_block').find('*').on('mousedown', function(e){
			e.stopPropagation();
		});

		if($("#search").length)
		{
			if(arLiteOptions['THEME']['REGIONALITY_SEARCH_ROW'] == 'Y')
			{
				$("#search").autocomplete({
					minLength: 2,
					source: function(request, response){
						$.getJSON( arLiteOptions['SITE_DIR']+'ajax/city_select.php', {
				            term: request.term,
				            url: '<?=$url;?>'
				          }, response );
						
					},
					appendTo : $(".js-autocomplete-block"),
					select: function(event, ui) {
						$.removeCookie('current_region');
						$.cookie('current_region', ui.item.ID, {path: '/',domain: arLiteOptions['SITE_ADDRESS']});
						$("#search").val(ui.item.label);
						return false;
					}
				}).data("ui-autocomplete")._renderItem = function(ul, item){
					var region = (item.REGION ? " ("+item.REGION +")" : "");
			    	return $("<li>")
			       		.append("<a href='" + item.HREF + "' class='cityLink dark_link font_14' data-id='"+item.ID+"'>" + item.label +region +"</a>")
			        	.appendTo(ul);
			    }
			}
			else
			{
				$("#search").autocomplete({
					minLength: 2,
					source: (typeof arRegions === 'object' ? arRegions : {}),
					appendTo : $(".js-autocomplete-block"),
					select: function(event, ui) {
						$.removeCookie('current_region');
						$.cookie('current_region', ui.item.ID, {path: '/',domain: arLiteOptions['SITE_ADDRESS']});
						$("#search").val(ui.item.label);
						return false;
					}
				}).data("ui-autocomplete")._renderItem = function(ul, item){
					var region = (item.REGION ? " ("+item.REGION +")" : "");
			    	return $("<li>")
			       		.append("<a href='" + item.HREF + "' class='cityLink dark_link font_14 js-change-region' data-id='"+item.ID+"'>" + item.label +region +"</a>")
			        	.appendTo(ul);
			    }
			}
		}

	    var current_region_item = $('.city .items_block .cities__item.current'),
	    	current_region_obl = '';
	    $('.city .cities__item:not(.current)').each(function(){
	    	if($(this).data('id') == current_region_item.data('id'))
	    		$(this).addClass('shown');
	    })

	    if($('.popup_regions .parent_block').length)
	    {
	    	$('.popup_regions .parent_block').each(function(){
	    		var _this = $(this),
	    			item = '';
	    		item = _this.find('.cities__item[data-id='+current_region_item.data('id')+']');
	    		if(item.length)
	    		{
		    		item.addClass('current');
		    		current_region_obl = item.parent();
		    		current_region_obl.addClass('current shown');
		    		if(_this.closest('.items_block').find('.js-region').length)
	    				_this.closest('.items_block').find('.js-region span').text(current_region_obl.find('.current').text());
		    	}
	    	})
	    }
	    if($('.popup_regions .block.regions').length)
	    {
	    	$('.popup_regions .block.regions').each(function(){
	    		var _this = $(this),
	    			obl_block = _this.find('.parent_block'),
	    			item = '';
	    		if(!obl_block.length)
	    		{
	    			if(current_region_obl)
	    			{
	    				_this.find('.cities__item[data-id='+current_region_obl.data('id')+']').addClass('current');
	    				if(_this.find('.js-region').length && current_region_obl)
	    					_this.find('.js-region span').text(_this.find('.cities__item[data-id='+current_region_obl.data('id')+']').text());
	    			}
	    			else
	    			{
	    				item = _this.find('.cities__item[data-id='+current_region_item.data('id')+']');
			    		if(item.length)
			    		{
			    			if(_this.find('.js-region').length)
	    						_this.find('.js-region span').text(item.text());
				    		item.addClass('current');
				    		current_region_obl = item.parent();
				    		current_region_obl.addClass('current shown');
				    	}
	    			}
	    		}
	    	})
	    	$('.popup_regions .block.regions .cities__item').on('click', function(){
	    		var _this = $(this),
	    			obl_block = _this.parent('.parent_block');
    			_this.siblings().removeClass('current');
    			_this.addClass('current');

    			if(_this.closest('.block').find('.js-region').length)
    			{
	    			_this.closest('.block').find('.js-region span').text(_this.text());
	    			_this.closest('.block').find('.dropdown').fadeOut(100);
	    		}

	    		if(obl_block.length)
	    		{
	    			$('.city .cities__item').siblings().removeClass('current shown');
	    			$('.city .cities__item[data-id='+_this.data('id')+']').addClass('current shown');
	    		}
	    		else
	    		{
	    			if($('.popup_regions .parent_block').length)
	    			{
	    				var parent_block = $('.popup_regions .parent_block[data-id='+_this.data('id')+']')
	    				$('.popup_regions .parent_block').siblings().removeClass('current shown');
	    				parent_block.addClass('current shown');

	    				if(parent_block.find('.cities__item.current').length)
	    					parent_block.find('.cities__item.current').trigger('click');
	    				else
	    					parent_block.find('.cities__item:first-child').trigger('click');
	    			}
	    			else
	    			{
	    				$('.city .cities__item').siblings().removeClass('current shown');
	    				$('.city .cities__item[data-id='+_this.data('id')+']').addClass('current shown');
	    			}
	    		}
    			if(_this.closest('.block').find('.js-region').length)
				{
    				$('.city').addClass('with-check');
    				$('.city .js-region span').text(BX.message('CITY_CHOISE_TEXT'));
    			}
	    	})
	    }

	    $(document).on('click', '.popup_regions .js-change-region', function(e){
	    	e.preventDefault();
	    	var _this = $(this);

	    	if(_this.closest('.block').find('.js-region').length)
			{
				_this.closest('.block').removeClass('with-check');
    			_this.closest('.block').find('.js-region span').text(_this.text());
    			_this.closest('.block').find('.dropdown').fadeOut(100);
    		}

	    	$.removeCookie('current_region');
			
	    	if(arLiteOptions['SITE_ADDRESS'].indexOf(',') != '-1')
			{
				var arDomains = arLiteOptions['SITE_ADDRESS'].split(',');
				if(arDomains)
				{
					for(var i in arDomains)
					{
						var domain_name = arDomains[i].replace("\n", "");
							domain_name = arDomains[i].replace("'", "");
						$.cookie('current_region', _this.data('id'), {path: '/',domain: domain_name});
					}
				}
			}
			else
				$.cookie('current_region', _this.data('id'), {path: '/',domain: arLiteOptions['SITE_ADDRESS']});
			location.href = _this.attr('href');
	    })

	    $('.search-page .wrapper .btn-search').on('click', function(){
			var block = $(this).closest('.wrapper').find('#search');
			if(block.length)
			{
				block.trigger('focus');
				block.data('ui-autocomplete').search(block.val());
			}
		})
		$('#search').on('focus', function(){
			block = $(this);
			block.data('ui-autocomplete').search(block.val());
		})
	</script>
</div>
</div>
