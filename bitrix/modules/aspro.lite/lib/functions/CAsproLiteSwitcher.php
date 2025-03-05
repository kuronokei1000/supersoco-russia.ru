<?
namespace Aspro\Functions;

use Bitrix\Main\Application;
use Bitrix\Main\Web\DOM\Document;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\DOM\CssParser;
use Bitrix\Main\Text\HtmlFilter;
use Bitrix\Main\IO\File;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

if(!class_exists("CAsproLiteSwitcher"))
{
	class CAsproLiteSwitcher{
		const MODULE_ID = \CLite::moduleID;
		private static $moduleClass = 'CLite';

		protected $customColorExist;
		protected $customMoreColorExist;
		protected $bUseMoreColor;

		function __construct($result) {
			$this->result = $result;

			$this->customColorExist = isset($result['BASE_COLOR']['LIST']['CUSTOM']) && isset($result['BASE_COLOR_CUSTOM']);
			$this->customMoreColorExist = isset($result['MORE_COLOR']['LIST']['CUSTOM']) && isset($result['MORE_COLOR_CUSTOM']);
			$this->bUseMoreColor = $result['USE_MORE_COLOR']['VALUE'] === 'Y';
		}

		public function showAllOptions($blockCode) {
			$this->blockCode = $blockCode;
			foreach($this->result as $optionCode => $arOption) {
				$this->optionCode = $optionCode;
				$this->arOption = $arOption;

				if($this->checkOptionBlock()):?>
					<?if($this->checkOptionSkip()) {continue;}?>

					<div class="item <?=$this->optionCode?> <?=($this->checkOptionHidden() ? 'hidden' : '')?>" <?=(($this->optionCode === 'MORE_COLOR' && !$this->bUseMoreColor) ? 'style="display:none;"' : '')?>>
						<?
						$this->showPickerWrapper();

						if($this->arOption['TYPE'] == 'checkbox'
							&& (isset($this->arOption['ONE_ROW'])
							&& $this->arOption['ONE_ROW'] == 'Y')) {
							$this->showCheckBoxOneRow();
						} else {
							$this->showParentOption();
						}

						if(isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS']) // show dependent options
						{
							foreach($arOption['DEPENDENT_PARAMS'] as $key => $arSubOptions)
							{?>
								<?if((!isset($arSubOptions['CONDITIONAL_VALUE']) || ($arSubOptions['CONDITIONAL_VALUE'] && $this->result[$optionCode]['VALUE'] == $arSubOptions['CONDITIONAL_VALUE'])) && $arSubOptions['THEME'] == 'Y')
								{?>
									<?if($arSubOptions['TYPE'] == 'checkbox' && (isset($arSubOptions['ONE_ROW']) && $arSubOptions['ONE_ROW'] == 'Y')):?>
										<div class="borders item">
											<div class="options dependent pull-left" data-code="<?=$key?>" data-ajax="<?=$arSubOptions['AJAX']?>" data-no-delay="<?=$arSubOptions['NO_DELAY']?>">
												<?=self::ShowOptions($key, $arSubOptions);?>
											</div>
											<?=self::ShowOptionsTitle($key, $arSubOptions);?>
										</div>
									<?else:?>
										<?=self::ShowOptionsTitle($key, $arSubOptions);?>
										<div class="options dependent" data-code="<?=$key;?>" data-ajax="<?=$arSubOptions['AJAX']?>" data-no-delay="<?=$arSubOptions['NO_DELAY']?>">
											<?echo self::ShowOptions($key, $arSubOptions);?>
										</div>
									<?endif;?>
								<?}
							}
						}?>
					</div>
				<?elseif((isset($arOption['OPTIONS']) && $arOption['OPTIONS']) && (isset($arOption['GROUPS_EXT']) && $arOption['GROUPS_EXT'] == 'Y') && $arOption['TYPE_BLOCK'] == $blockCode && (isset($arOption['THEME']) && $arOption['THEME'] == 'Y')): // show groups options?>
					<div class="item groups">
						<?=self::ShowOptionsTitle($blockCode, $arOption);?>
						<div class="rows options">
							<?foreach($arOption['OPTIONS'] as $key => $arValue):?>
								<?echo self::ShowOptions($key, $arValue);?>
							<?endforeach;?>
						</div>
					</div>
				<?elseif($optionCode === 'TABS' && $arOption[$blockCode]): // show groups options?>
					<div class="item groups-tab">
						<div class="tabs bottom-line" data-parent="<?=$blockCode?>">
							<ul class="nav nav-tabs">
								<?$j = 0;?>
								<?//foreach(array_keys($arOption['OPTIONS']) as $key):?>
								<?foreach(array_keys($arOption[$blockCode]) as $key):?>
									<?
									$class = '';
									if(isset($_COOKIE['styleSwitcherTabs'.$blockCode]))
									{
										if($_COOKIE['styleSwitcherTabs'.$blockCode] == $j)
											$class = 'active';
									}
									else
									{
										if(!$j)
											$class = 'active';
									}
									$j++;
									?>
									<li class="<?=$class;?>"><a href="#<?=$key?>" data-toggle="tab" class="linked colored_theme_hover_text"><?=GetMessage($key);?></a></li>
								<?endforeach;?>
							</ul>
						</div>
						<div class="tab-content">
							<?$j = 0;?>
							<?//foreach($arOption['OPTIONS'] as $key => $arGroups):?>
							<?foreach($arOption[$blockCode] as $key => $arGroups):?>
								<?
								$class = '';
								if(isset($_COOKIE['styleSwitcherTabs'.$blockCode]))
								{
									if($_COOKIE['styleSwitcherTabs'.$blockCode] == $j)
										$class = 'active';
								}
								else
								{
									if(!$j)
										$class = 'active';
								}
								$j++;
								?>
								<div class="tab-pane <?=$class;?>" id="<?=$key;?>">
									<?foreach($arGroups['OPTIONS'] as $key2 => $arGroupItem):?>
										<?if ($arGroupItem['THEME'] === 'N') continue;?>
										<div class="item <?=$key2;?>">
											<?if($arGroupItem['TYPE'] == 'checkbox' && (isset($arGroupItem['ONE_ROW']) && $arGroupItem['ONE_ROW'] == 'Y')):?>
												<div class="options pull-left" data-code="<?=$key2?>" data-ajax="<?=$arGroupItem['AJAX']?>" data-no-delay="<?=$arGroupItem['NO_DELAY']?>">
													<?=self::ShowOptions($key2, $arGroupItem);?>
												</div>
												<?=self::ShowOptionsTitle($key2, $arGroupItem);?>
												<?else:?>
													<?=self::ShowOptionsTitle($key2, $arGroupItem);?>
													<div class="options" data-code="<?=$key2;?>" data-ajax="<?=$arGroupItem['AJAX']?>" data-no-delay="<?=$arGroupItem['NO_DELAY']?>">
														<?echo self::ShowOptions($key2, $arGroupItem);?>
													</div>
											<?endif;?>
											<?if(isset($arGroupItem['DEPENDENT_PARAMS']) && $arGroupItem['DEPENDENT_PARAMS']) // show dependent options
											{
												foreach($arGroupItem['DEPENDENT_PARAMS'] as $key3 => $arSubOptions)
												{
													if((!isset($arSubOptions['CONDITIONAL_VALUE']) || ($arSubOptions['CONDITIONAL_VALUE'] && $this->result[$optionCode][$blockCode][$key]['OPTIONS'][$key2]['VALUE'] == $arSubOptions['CONDITIONAL_VALUE'])) && $arSubOptions['THEME'] == 'Y')
													{?>
														<?if($arSubOptions['TYPE'] == 'checkbox' && (isset($arSubOptions['ONE_ROW']) && $arSubOptions['ONE_ROW'] == 'Y')):?>
															<div class="borders item">
																<div class="options dependent pull-left" data-code="<?=$key?>" data-ajax="<?=$arSubOptions['AJAX']?>" data-no-delay="<?=$arSubOptions['NO_DELAY']?>">
																	<?=self::ShowOptions($key3, $arSubOptions);?>
																</div>
																<?=self::ShowOptionsTitle($key3, $arSubOptions);?>
															</div>
														<?else:?>
															<?=self::ShowOptionsTitle($key3, $arSubOptions);?>
															<div class="options dependent" data-code="<?=$key3;?>" data-ajax="<?=$arSubOptions['AJAX']?>" data-no-delay="<?=$arSubOptions['NO_DELAY']?>">
																<?=self::ShowOptions($key3, $arSubOptions);?>
															</div>
														<?endif;?>
													<?}
												}
											}?>
										</div>
									<?endforeach;?>
								</div>
							<?endforeach;?>
						</div>
					</div>
				<?endif;
			}
		}

		public static function ShowOptions($optionCode, $arOption, $arParentOption = array(), $additionalOptions = array()){
			$isRow = (isset($arOption['IS_ROW']) && $arOption['IS_ROW'] == 'Y');
			$isPreview = (isset($arOption['PREVIEW']) && $arOption['PREVIEW']);
			if($arOption['TYPE'] == 'checkbox'):?>
				<?$isChecked = ($arOption['VALUE'] == 'Y');?>
				<?if($isRow):?>
					<div class="<?=(isset($arOption['ROW_CLASS']) && $arOption['ROW_CLASS'] ? $arOption['ROW_CLASS'] : '');?>">
						<div class="link-item animation-boxs <?=(isset($arOption['POSITION_BLOCK']) && $arOption['POSITION_BLOCK'] ? $arOption['POSITION_BLOCK'] : '');?> <?=(!$isChecked ? 'disabled' : '');?>">
				<?endif;?>

					<?ob_start();?>
						<?if((!isset($arOption['HIDE_TITLE']) || $arOption['HIDE_TITLE'] != 'Y') && (isset($arOption['TITLE']) && $arOption['TITLE'])):?><span><?=$arOption['TITLE']?></span><?endif;?>
					<?$title = ob_get_contents();
					ob_end_clean();?>

					<?
					ob_start();
						if($arOption['SMALL_CHECKBOX']){
							?>
							<div class="filter form-checkbox sm">
								<input type="checkbox" id="<?=$optionCode?>" class="form-checkbox__input small_checkbox" name="<?=$optionCode?>" value="<?=$arOption['VALUE']?>" <?=($isChecked ? 'checked' : '');?> />
								<?if($arOption['LABEL']):?>
									<label for="<?=$optionCode?>" class="form-checkbox__label">
										<span><?=$arOption['LABEL']?></span>
										<span class="form-checkbox__box form-box"></span>
									</label>
								<?else:?>
									<span class="form-checkbox__box form-box"></span>
								<?endif;?>
							</div>
							<?
						}
						else{
							?>
							<input type="checkbox" id="<?=$optionCode?>" class="<?=$arOption['SMALL_CHECKBOX'] ? 'small_checkbox' : 'custom-switch'?>" name="<?=$optionCode?>" value="<?=$arOption['VALUE']?>" <?=($isChecked ? 'checked' : '')?> />

							<div class="on-off-switch<?=((isset($arOption['SMALL_TOGGLE']) && $arOption['SMALL_TOGGLE']) ? ' on-off-switch--22' : '')?><?=((isset($arOption['SMALL2_TOGGLE']) && $arOption['SMALL2_TOGGLE']) ? ' on-off-switch--16' : '')?>">
								<div class="on-off-switch-track"></div>
								<div class="on-off-switch-thumb"></div>
							</div>
							
							<?if($arOption['LABEL']):?>
								<label for="<?=$optionCode?>"><?=$arOption['LABEL']?></label>
							<?endif;?>
							<?
						}
					
					$input = ob_get_contents();
					ob_end_clean();
					?>

					<?if(isset($arOption['IMG']) && $arOption['IMG']):?>
						<?=$title;?>
						<div class="img"><img class="<?=($arOption["COLORED_IMG"] ? 'colored_theme_bg colored_bg_in_dark' : '')?>" src="<?=$arOption['IMG'];?>" alt="<?=$arOption['TITLE']?>" title="<?=$arOption['TITLE']?>"/></div>
						<div class="input"><?=$input;?></div>
					<?elseif(isset($arOption['GROUP']) && $arOption['GROUP']):?>
						<span class="inner-table-block"><?=$title;?></span>
						<span class="inner-table-block"><?=$input;?></span>
					<?else:?>
						<?if(isset($arOption['SHOW_TITLE'])):?>
							<div class="<?=$optionCode?> <?=$additionalOptions['CLASS'] ? $additionalOptions['CLASS'] : ''?> imgs">
								<div class="titles"><?=$title;?></div>
								<div class="right-option-part">
						<?endif;?>

						<?if(isset($arOption['ADDITIONAL_OPTIONS']) && $arOption['ADDITIONAL_OPTIONS']):?>
							<div class="checkbox-subs">
								<?foreach($arOption['ADDITIONAL_OPTIONS'] as $key => $arSubOption):?>
									<div class="sub-item">
										<?if(strpos($optionCode, '_TEMPLATE') !== false):?>
											<?=self::ShowOptions(str_replace('_TEMPLATE', '_', $optionCode).$key.'_'.$additionalOptions['VARIANT_CODE'], $arSubOption)?>
										<?else:?>
											<?=self::ShowOptions($key.'_'.$additionalOptions['VARIANT_CODE'], $arSubOption, array(),  array('CLASS' => 'checkbox-additional'))?>
										<?endif;?>
									</div>
								<?endforeach;?>
							</div>
						<?endif;?>

						<div class="checkbox-wrapper">
							<?=$input;?>
						</div>

						<?if(isset($arOption['SHOW_TITLE'])):?>
								</div>
							</div>
						<?endif;?>
					<?endif;?>
				<?if($isRow):?>
						</div>
					</div>
				<?endif;?>
			<?elseif($arOption['TYPE'] == 'selectbox' || $arOption['TYPE'] == 'multiselectbox'):?>
				<?if($arOption['SELECT_TYPE'] == 'SMALL_SELECT'):?>
					<div class="switcher-select">
						<?if($arOption['TITLE']):?>
							<div class="style-switcher__label style-switcher__label--font_11 switcher-select__title"><?=$arOption['TITLE']?></div>
						<?endif;?>
						<?if($arOption['LIST']):?>
							<div class="switcher-select__popup">
								<?
								$counter = 1;
								foreach($arOption['LIST'] as $listItemKey => $listItem) {
									$listItemValue = $listItemKey;
									$listItemTitle = $listItem;

									if( is_array($listItem) ) {
										$listItemTitle = $listItem['TITLE'];
									}

									if($arOption['VALUE'] == $listItemValue) {
										$currentListItem = $listItemTitle;
										$bCurrent = true;
									} else {
										$bCurrent = false;
									}
									?>

									<div class="switcher-select__popup-item style-switcher__title--xs <?=$counter == count($arOption['LIST']) ? 'switcher-select__popup-item--last' : ''?> <?=$bCurrent ? 'switcher-select__popup-item--current' : ''?>" data-value="<?=$listItemValue?>" data-title="<?=$listItemTitle?>">
										<?=$listItemTitle?>
									</div>

									<?
								$counter++;
								}
								if( !$currentListItem ) {
									$currentListItem = reset($arOption['LIST']);
									$currentListItem = $currentListItem['TITLE'] ? $currentListItem['TITLE'] : $currentListItem;
								}
								?>
							</div>
							<div class="switcher-select__current style-switcher__title style-switcher__title--xs"><?=$currentListItem;?><?=\CLite::showSpriteIconSvg('/bitrix/components/aspro/theme.lite/templates/.default/images/svg/sprite.svg#select_arrow-down', 'switcher-select__icon')?></div>
							<input type="hidden" id="<?=$optionCode?>" class="hidden switcher-select__input" name="<?=$optionCode?>" value="<?=$arOption['VALUE']?>" data-index_type="<?=$key;?>" data-index_block="<?=$key2;?>" data-dynamic="Y">
						<?endif;?>
					</div>
				<?else:?>
					<input type="hidden" id="<?=$optionCode?>" name="<?=$optionCode?>" value="<?=$arOption['VALUE']?>" />
					<?if(isset($arOption['GROUPS']) && $arOption['GROUPS'] == 'Y'):?>
						<?
						$arGroups = array();
						foreach($arOption['LIST'] as $variantCode => $arVariant)
						{
							if(isset($arVariant['HIDE']) && $arVariant['HIDE'] == 'Y') continue;
							$group = ((isset($arVariant['GROUP']) && $arVariant['GROUP']) ? $arVariant['GROUP'] : GetMessage('NO_GROUP'));
							$arGroups[$group]['LIST'][$variantCode] = array(
								'TITLE' => ((isset($arVariant['VALUE']) && $arVariant['VALUE']) ? $arVariant['VALUE'] : $arVariant['TITLE']),
								'CURRENT' => ((isset($arVariant['CURRENT']) && $arVariant['CURRENT']) ? $arVariant['CURRENT'] : 'N')
							);
						}
						if($arGroups)
						{
							foreach($arGroups as $key => $arGroup)
							{?>
								<div class="group">
									<div class="title"><?=$key;?></div>
									<div class="values">
										<div class="inner-values">
											<?foreach($arGroup['LIST'] as $variantCode => $arVariant):?>
												<span data-option-id="<?=$optionCode?>" data-option-value="<?=$variantCode?>" class="link-item animation-boxs <?=$arVariant['CURRENT'] == 'Y' ? 'current' : ''?>">
													<?if(isset($arVariant['IMG']) && $arVariant['IMG']):?>
														<span><img class="<?=($arVariant["COLORED_IMG"] ? 'colored_theme_bg colored_bg_in_dark' : '')?>" src="<?=$arVariant['IMG'];?>" alt="<?=$arVariant['TITLE']?>" title="<?=$arVariant['TITLE']?>"/></span>
													<?endif;?>
													<?if(!isset($arVariant['HIDE_TITLE']) || $arVariant['HIDE_TITLE'] != 'Y'):?><span><?=$arVariant['TITLE']?></span><?endif;?>
												</span>
											<?endforeach;?>
										</div>
									</div>
								</div>
							<?}
						}?>
					<?else:?>
						<?if($isRow):?>
							<div class="rows">
						<?endif;?>
						<?foreach($arOption['LIST'] as $variantCode => $arVariant):
							if(isset($arVariant['HIDE']) && $arVariant['HIDE'] == 'Y') continue;?>
							<?if($isRow):?>
								<div class="<?=(isset($arVariant['ROW_CLASS']) && $arVariant['ROW_CLASS'] ? $arVariant['ROW_CLASS'] : '');?>">
							<?endif;?>
							<div <?=($isPreview && (isset($arOption['PREVIEW']['SCROLL_BLOCK']) && $arOption['PREVIEW']['SCROLL_BLOCK']) ? "data-option-type='".$arOption['PREVIEW']['SCROLL_BLOCK']."'" : "");?> <?=($isPreview && isset($arOption['PREVIEW']['URL']) ? "data-option-url='".SITE_DIR.$arOption['PREVIEW']['URL']."'" : "");?> <?=(isset($arOption["AJAX_SWITCH"]) ? "data-ajax='".$arOption["AJAX_SWITCH"]."'" : "")?> data-option-id="<?=$optionCode?>" data-option-value="<?=$variantCode?>" class="link-item animation-boxs <?=(isset($arVariant['POSITION_BLOCK']) && $arVariant['POSITION_BLOCK'] ? $arVariant['POSITION_BLOCK'] : '');?> <?=$arVariant['CURRENT'] == 'Y' ? 'current' : ''?>">

								<?ob_start();?>
									<?if((!isset($arVariant['HIDE_TITLE']) || $arVariant['HIDE_TITLE'] != 'Y') && (isset($arVariant['TITLE']) && $arVariant['TITLE'])):?><span class="title"><?=$arVariant['TITLE']?></span><?endif;?>
								<?$title = ob_get_contents();
								ob_end_clean();?>

								<?ob_start();?>
									<span><img class="<?=($arVariant["COLORED_IMG"] ? 'colored_theme_bg colored_bg_in_dark' : '')?>" src="<?=$arVariant['IMG'];?>" alt="<?=$arVariant['TITLE']?>" title="<?=$arVariant['TITLE']?>"/></span>
								<?$img = ob_get_contents();
								ob_end_clean();?>

								<?if(isset($arVariant['IMG']) && $arVariant['IMG']):?>
									<?if(isset($arVariant['POSITION_TITLE']) && $arVariant['POSITION_TITLE']):?>
										<?if($arVariant['POSITION_TITLE'] == 'left'):?>
											<span class="inner-table-block" <?=((isset($arVariant['TITLE_WIDTH']) && $arVariant['TITLE_WIDTH']) ? "style='width:".$arVariant['TITLE_WIDTH']."'" : "");?>><?=$title;?></span>
											<span class="inner-table-block"><?=$img;?></span>
										<?endif;?>
									<?else:?>
										<span class="title"><?=$title;?></span>
										<?=$img;?>
									<?endif;?>
								<?else:?>
									<?=$title;?>
								<?endif;?>

							<?if(!isset($arVariant['IN_BLOCK'])):?>
							</div>
							<?endif;?>
							<?if(isset($arVariant['ADDITIONAL_OPTIONS']) && $arVariant['ADDITIONAL_OPTIONS']):?>
								<div class="subs">
									<?foreach($arVariant['ADDITIONAL_OPTIONS'] as $key => $arSubOption):?>
										<?
										if( isset($arSubOption['DEPENDS_ON']) 
											&& isset($arVariant['ADDITIONAL_OPTIONS'][$arSubOption['DEPENDS_ON']])
											&& $arVariant['ADDITIONAL_OPTIONS'][$arSubOption['DEPENDS_ON']]['VALUE'] !== 'Y'
											)
										{
											continue;
										}
										?>
										<div class="sub-item">											
											<?if(strpos($optionCode, '_TEMPLATE') !== false):?>
												<?=self::ShowOptions(str_replace('_TEMPLATE', '_', $optionCode).$key.'_'.$variantCode, $arSubOption, array(), array('VARIANT_CODE' => $variantCode))?>
											<?else:?>
												<?=self::ShowOptions($key.'_'.$variantCode, $arSubOption, array(), array('VARIANT_CODE' => $variantCode))?>
											<?endif;?>											
										</div>
									<?endforeach;?>
								</div>
							<?endif;?>


							<?if(isset($arVariant['TOGGLE_OPTIONS']) && $arVariant['TOGGLE_OPTIONS']):?>
								<div class="toggle-options">
									<div class="toggle-options__title toggle-options__link colored_theme toggle-parent"><?=$arVariant['TOGGLE_OPTIONS']['TITLE']?></div>
									<div class="toggle-options__options toggle-target option-ajax" <?=$arVariant['TOGGLE_OPTIONS']['PAGE_BLOCK'] ? 'data-page-block="'.$arVariant['TOGGLE_OPTIONS']['PAGE_BLOCK'].'"' : ''?> <?=$arVariant['TOGGLE_OPTIONS']['AJAX_CALLBACK'] ? 'data-ajax-callback="'.$arVariant['TOGGLE_OPTIONS']['AJAX_CALLBACK'].'"' : ''?> style="display: none;">
										<?$counter = 0;?>
										<?foreach($arVariant['TOGGLE_OPTIONS']['OPTIONS'] as $key => $arSubOption):?>
											<?
											$bFirst = $counter == 0;
											$bLast = $counter == count($arVariant['TOGGLE_OPTIONS']['OPTIONS']) - 1;
											?>
											<div class="toggle-options__option option-wrapper option-ajax-target <?=$bFirst ? 'toggle-options__option--first' : ''?> <?=$bLast ? 'toggle-options__option--last' : ''?> <?=$arSubOption['VALUE'] == 'Y' || $arSubOption['TYPE'] != 'checkbox' ? '' : 'disabled'?>" data-need-block="<?=$arSubOption['AJAX_PARAM'] ? $arSubOption['AJAX_PARAM'] : $key?>">
												<?if(strpos($optionCode, '_TEMPLATE') !== false):?>
													<?=self::ShowOptions(str_replace('_TEMPLATE', '_', $optionCode).$key.'_'.$variantCode, $arSubOption, array(), array('VARIANT_CODE' => $variantCode))?>
												<?else:?>
													<?=self::ShowOptions( $key.'_'.$variantCode, $arSubOption, array(), array('CLASS' => 'toggle-options__option-inner', 'VARIANT_CODE' => $variantCode) )?>
												<?endif;?>
											</div>
											<?$counter++;?>
										<?endforeach;?>
									</div>
								</div>
							<?endif;?>

							<?if(isset($arVariant['IN_BLOCK'])):?>
							</div>
							<?endif;?>

							<?if($isRow):?>
								</div>
							<?endif;?>
						<?endforeach;?>
						<?if($isRow):?>
							</div>
						<?endif;?>
					<?endif;?>
				<?endif;?>
			<?elseif($arOption['TYPE'] == 'text'):?>
				<input type="text" class="form-control" id="<?=$optionCode?>" <?=((isset($arOption['PARAMS']) && isset($arOption['PARAMS']['WIDTH'])) ? 'style="width:'.$arOption['PARAMS']['WIDTH'].'"' : '');?> name="<?=$optionCode?>" value="<?=$arOption['VALUE']?>" />
			<?elseif($arOption['TYPE'] == 'textarea'):?>
				<?// text here?>
			<?elseif($arOption['TYPE'] == 'link'):?>
				<?ob_start();?>
					<?if((!isset($arOption['HIDE_TITLE']) || $arOption['HIDE_TITLE'] != 'Y') && (isset($arOption['TITLE']) && $arOption['TITLE'])):?><span><?=$arOption['TITLE']?></span><?endif;?>
				<?$title = ob_get_contents();
				ob_end_clean();?>

				<?ob_start();?>
					<div class="<?=$arOption['CLASS'] ? $arOption['CLASS'] : 'link'?>" <?=$arOption['TAB'] ? 'data-tab="'.$arOption['TAB'].'"' : ''?>><?=$arOption['LINK_TITLE']?></div>
				<?$input = ob_get_contents();
				ob_end_clean();?>
				<?if(isset($arOption['IMG']) && $arOption['IMG']):?>
					<?=$title;?>
					<div class="img"><img class="<?=($arOption["COLORED_IMG"] ? 'colored_theme_bg colored_bg_in_dark' : '')?>" src="<?=$arOption['IMG'];?>" alt="<?=$arOption['TITLE']?>" title="<?=$arOption['TITLE']?>"/></div>
					<div class="input"><?=$input;?></div>
				<?elseif(isset($arOption['GROUP']) && $arOption['GROUP']):?>
					<span class="inner-table-block"><?=$title;?></span>
					<span class="inner-table-block"><?=$input;?></span>
				<?else:?>
					<?if(isset($arOption['SHOW_TITLE'])):?>
						<div class="<?=$optionCode?> <?=$additionalOptions['CLASS'] ? $additionalOptions['CLASS'] : ''?> imgs">
							<div class="titles">
								<?=$title;?>
							</div>
							<div class="right-option-part">
					<?endif;?>

					<?if(isset($arOption['ADDITIONAL_OPTIONS']) && $arOption['ADDITIONAL_OPTIONS']):?>
						<div class="checkbox-subs">
							<?foreach($arOption['ADDITIONAL_OPTIONS'] as $key => $arSubOption):?>
								<div class="sub-item">
									<?if(strpos($optionCode, '_TEMPLATE') !== false):?>
										<?=self::ShowOptions(str_replace('_TEMPLATE', '_', $optionCode).$key.'_'.$additionalOptions['VARIANT_CODE'], $arSubOption)?>
									<?else:?>
										<?=self::ShowOptions($key.'_'.$additionalOptions['VARIANT_CODE'], $arSubOption, array(),  array('CLASS' => 'checkbox-additional'))?>
									<?endif;?>
								</div>
							<?endforeach;?>
						</div>
					<?endif;?>

					<?=$input;?>

					<?if(isset($arOption['SHOW_TITLE'])):?>
							</div>
						</div>
					<?endif;?>
				<?endif;?>
			<?elseif($arOption['TYPE'] == 'backButton'):?>
				<div class="switcher__back-button color-theme-hover stroke-theme-hover">
					<?=\CLite::showIconSvg(' switcher__back-button-arrow', str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__).'/images/back-button-arrow.svg');?>
					<div class="switcher__back-button-title">
						<?=$arOption['TITLE']?>
					</div>
				</div>
			<?endif;?>
		<?}

		public static function ShowOptionsTitle($optionCode, $arOption){?>
			<?if(!isset($arOption['HIDE_TITLE']) || $arOption['HIDE_TITLE'] != 'Y'):?>
				<div class="title <?=((isset($arOption['TOP_BORDER']) && $arOption['TOP_BORDER'] == 'Y') ? 'with-border' : '');?>"><?=$arOption['TITLE'];?><?=((isset($arOption['HINT']) && $arOption['HINT']) ? "<span class='tooltip-link' data-placement='top' data-trigger='click' data-toggle='tooltip' data-original-title='".$arOption['HINT']."'>?</span>" : "");?></div>
			<?endif;?>
		<?}

		private function showPickerWrapper() {
			if(isset($this->arOption['TYPE_EXT'])
				&& $this->arOption['TYPE_EXT'] == 'colorpicker'):?>
				<div class="picker_wrapper picker">
			<?endif;
		}

		private function showParentOption() {
			self::ShowOptionsTitle($this->optionCode, $this->arOption);?>
			<?if($this->arOption['EXT_HINT']):?>
				<div class="ext_hint_title dark-color"><?=$this->arOption['EXT_HINT']['TITLE'];?></div>
				<?if($this->arOption['EXT_HINT']['TEXT']):?>
					<div class="ext_hint_desc"><?=$this->arOption['EXT_HINT']['TEXT'];?></div>
				<?endif;?>
			<?endif;?>
			<div class="options <?=((isset($this->arOption['REFRESH']) && $this->arOption['REFRESH'] == 'Y') ? 'refresh-block' : '');?>" data-code="<?=$this->optionCode?>" data-ajax="<?=$this->arOption['AJAX']?>" data-no-delay="<?=$this->arOption['NO_DELAY']?>">
				<?if(isset($this->arOption['TYPE_EXT']) && $this->arOption['TYPE_EXT'] == 'colorpicker'):?>
					<input type="hidden" id="<?=$this->optionCode?>" name="<?=$this->optionCode?>" value="<?=$this->arOption['VALUE']?>" />
					<?foreach($this->arOption['LIST'] as $colorCode => $arColor):?>
						<?if($colorCode !== 'CUSTOM'):?>
							<div class="<?=strtolower($this->optionCode)?> base_color <?=($arColor['CURRENT'] == 'Y' ? 'current' : '')?>" data-value="<?=$colorCode?>" data-color="<?=$arColor['COLOR']?>">
								<span class="animation-all click_block"  data-option-id="<?=$this->optionCode?>" data-option-value="<?=$colorCode?>" title="<?=$arColor['TITLE']?>" style="border-color: <?=$arColor['COLOR']?>;"><span style="background-color: <?=$arColor['COLOR']?>;"></span></span>
							</div>
						<?endif;?>
					<?endforeach;?>
				<?else:?>
					<?=self::ShowOptions($this->optionCode, $this->arOption);?>
				<?endif;?>
			</div>
			<?if(isset($this->arOption['TYPE_EXT']) && $this->arOption['TYPE_EXT'] == 'colorpicker'):?>
				</div>
				<div class="custom_block picker">
					<div class="title"><?=GetMessage("USER_CUSTOM_COLOR");?></div>
					<div class="options" data-ajax="<?=$this->arOption['AJAX']?>" data-no-delay="<?=$this->arOption['NO_DELAY']?>">
						<?if(isset($this->result[$this->optionCode]['LIST']['CUSTOM']) && isset($this->result[$this->optionCode.'_CUSTOM']) && (isset($this->result[$this->optionCode.'_CUSTOM']['PARENT_PROP']) && $this->result[$this->optionCode.'_CUSTOM']['PARENT_PROP'] == $this->optionCode)):?>
							<?$customColor = str_replace('#', '', (strlen($this->result[$this->optionCode.'_CUSTOM']['VALUE']) ? $this->result[$this->optionCode.'_CUSTOM']['VALUE'] : $this->result[$this->optionCode]['LIST'][$this->result[$this->optionCode]['DEFAULT']]['COLOR']));?>
							<?$arColor = $this->arOption['LIST']['CUSTOM'];?>
							<div class="<?=strtolower($this->optionCode)?> base_color color_custom <?=($arColor['CURRENT'] == 'Y' ? 'current' : '')?>" data-name="<?=$this->optionCode.'_CUSTOM'?>" data-value="CUSTOM" data-color="#<?=$customColor?>">
								<span class="animation-all click_block" data-option-id="<?=$this->optionCode?>" data-option-value="CUSTOM" title="<?=$arColor['TITLE']?>" style="border-color: <?=$arColor['COLOR']?>;"><span class="vals">#<?=($arColor['CURRENT'] == 'Y' ? $customColor : '')?></span><span class="bg" data-color="<?=$customColor?>" style="background-color: #<?=$customColor?>;"></span></span>
								<input type="hidden" id="<?=strtolower($this->optionCode).'_custom_picker'?>" name="<?=$this->optionCode.'_CUSTOM'?>" value="<?=$customColor?>" />
							</div>
						<?endif;?>
						<?if($this->customColorExist && (isset($this->result['CUSTOM_BGCOLOR_THEME']['PARENT_PROP']) && $this->result['CUSTOM_BGCOLOR_THEME']['PARENT_PROP'] == $this->optionCode)):?>
							<?$customColor = str_replace('#', '', (strlen($this->result['CUSTOM_BGCOLOR_THEME']['VALUE']) ? $this->result['CUSTOM_BGCOLOR_THEME']['VALUE'] : $this->result['CUSTOM_BGCOLOR_THEME']['LIST'][$this->result['CUSTOM_BGCOLOR_THEME']['DEFAULT']]['COLOR']));?>
							<?$arColor = $this->arOption['LIST']['CUSTOM'];?>
							<div class="base_color base_color_custom <?=($arColor['CURRENT'] == 'Y' ? 'current' : '')?>" data-name="CUSTOM_BGCOLOR_THEME" data-value="CUSTOM" data-color="#<?=$customColor?>">
								<span class="animation-all click_block" data-option-id="<?=$this->optionCode?>" data-option-value="CUSTOM" title="<?=$arColor['TITLE']?>" style="border-color: #<?=$customColor?>;"><span class="vals">#<?=($arColor['CURRENT'] == 'Y' ? $customColor : '')?></span><span class="bg" style="background-color: #<?=$customColor?>;"></span></span>
								<input type="hidden" id="custom_picker2" name="CUSTOM_BGCOLOR_THEME" value="<?=$customColor?>" />
							</div>
						<?endif;?>
					</div>
				</div>
			<?endif;?>
			<?if(isset($this->arOption['SUB_PARAMS']) && $this->arOption['LIST'] && (isset($this->arOption['REFRESH']) && $this->arOption['REFRESH'] == 'Y')):?>
				<div>
					<?foreach($this->arOption['LIST'] as $key => $arListOption):?>
						<?if($this->arOption['SUB_PARAMS'][$key]):?>
							<?foreach($this->arOption['SUB_PARAMS'][$key] as $key2 => $arSubOptions)
							{
								if($arSubOptions['THEME'] == 'N' || $arSubOptions['VISIBLE'] == 'N')
									unset($this->arOption['SUB_PARAMS'][$key][$key2]);
							}?>

							<?if($this->arOption['SUB_PARAMS'][$key]):?>
								<div class="sup-params options refresh-block s_<?=$key;?> <?=($key == $this->arOption['VALUE'] ? 'active' : '');?>">
									<div class="block-title"><span class="dotted-block"><?=GetMessage('SUB_PARAMS')?></span></div>
									<div class="values">
										<?$param = "SORT_ORDER_".$this->optionCode."_".$key;?>
										<?if($this->result[$param])
										{
											$arOrder = explode(",", $this->result[$param]);
											$arIndexList = array_keys($this->arOption['SUB_PARAMS'][$key]);
											$arNewBlocks = array_diff($arIndexList, $arOrder);
											if($arNewBlocks) {
												$arOrder = array_merge($arOrder, $arNewBlocks);
											}
											$arTmp = array();
											foreach($arOrder as $name)
											{
												$arTmp[$name] = $this->arOption['SUB_PARAMS'][$key][$name];
											}
											$this->arOption['SUB_PARAMS'][$key] = $arTmp;
											unset($arTmp);
										}
										?>
										<?$j = 0;?>
										<div class="inner-wrapper" data-key="<?=$key;?>">
											<?foreach($this->arOption['SUB_PARAMS'][$key] as $key2 => $arSubOptions):?>
												<?if (!$arSubOptions) continue;?>
												<?$isRow = (($arSubOptions['TYPE'] == 'checkbox' && (isset($arSubOptions['ONE_ROW']) && $arSubOptions['ONE_ROW'] == 'Y')) ? true : false);?>
												<div class="option-wrapper <?=((isset($arSubOptions['DRAG']) && $arSubOptions['DRAG'] == 'N') ? "no_drag" : "");?> <?=(($arSubOptions['VALUE'] == 'N' && $isRow) ? "disabled" : "");?>">
													<div class="drag"><?=\CLite::showSpriteIconSvg('/bitrix/components/aspro/theme.lite/templates/.default/images/svg/sprite.svg#drag', 'svg-drag')?></div>
													<?if($isRow):?>
														<table class="table_parametrs_block">
															<tr>
																<td><div class="blocks"></div></td>
																<td><div class="blocks block-title <?=((isset($arSubOptions['TEMPLATE']) && $arSubOptions['TEMPLATE']) ? 'subtitle' : '')?>"><span><?=$arSubOptions['TITLE'];?></span></div></td>
																<td>
																	<?if(isset($arSubOptions['INDEX_BLOCK_OPTIONS']) && isset($arSubOptions['INDEX_BLOCK_OPTIONS']['TOP'])):?>
																		<?foreach($arSubOptions['INDEX_BLOCK_OPTIONS']['TOP'] as $topOptionKey => $topOption):?>
																			<?$this->optionCode = $topOptionKey.'_'.$key2.'_'.$key;?>
																			<div class="filter form-checkbox sm">
																				<input
																					type="checkbox"
																					id="<?=$this->optionCode?>"
																					name="<?=$this->optionCode?>"
																					value="<?=$this->result['INDEX_BLOCK_OPTIONS'][$this->optionCode]?>"
																					<?=($this->result['INDEX_BLOCK_OPTIONS'][$this->optionCode] == 'Y' ? "checked" : "");?>
																					data-index_type="<?=$key;?>"
																					data-index_class="<?=strtolower($topOptionKey);?>"
																					data-index_block="<?=$key2;?>"
																					data-dynamic="Y"
																					class="form-checkbox__input"
																				>
																				<label
																					for="<?=$this->optionCode?>"
																					class="form-checkbox__label"
																				>
																					<span><?=\Bitrix\Main\Localization\Loc::getMessage($topOptionKey.'_BLOCK');?></span>
																					<span class="form-checkbox__box form-box"></span>
																				</label>
																			</div>
																		<?endforeach;?>
																	<?endif;?>
																	<div class="blocks value">
																		<?=self::ShowOptions($key.'_'.$key2, $arSubOptions, $this->arOption);?>
																	</div>
																</td>
															</tr>
														</table>
													<?else:?>
														<div class="block-title"><?=$arSubOptions['TITLE'];?></div>
														<div class="value">
															<?=self::ShowOptions($key.'_'.$key2, $arSubOptions);?>
														</div>
													<?endif;?>
													<?if(isset($arSubOptions['TEMPLATE']) && $arSubOptions['TEMPLATE']):?>
														<div class="template_block">
															<?$code = $key.'_'.$key2.'_TEMPLATE';?>
															<div class="item <?=str_replace('_TEMPLATE', '', $code);?>" <?=(isset($_COOKIE['STYLE_SWITCHER_TEMPLATE'.$j]) && $_COOKIE['STYLE_SWITCHER_TEMPLATE'.$j] == 'Y' ? "style='display:block;'" : "");?>>
																<div class="options" data-code="<?=$code?>">
																	<?=self::ShowOptions($code, $this->result['TEMPLATE_PARAMS'][$key][$code]);?>
																</div>
															</div>
														</div>
													<?endif;?>

													<?if(isset($arSubOptions['INDEX_BLOCK_OPTIONS']) && isset($arSubOptions['INDEX_BLOCK_OPTIONS']['BOTTOM'])):?>
														<div class="bottom-options <?=(isset($_COOKIE['STYLE_SWITCHER_TEMPLATE'.$j]) && $_COOKIE['STYLE_SWITCHER_TEMPLATE'.$j] == 'Y' ? "active" : "");?>" >
															<?foreach($arSubOptions['INDEX_BLOCK_OPTIONS']['BOTTOM'] as $bottomOptionKey => $bottomOption):?>
																<?
																$this->optionCode = $bottomOptionKey.'_'.$key2.'_'.$key;
																$currentListItem = false;
																?>
																<?if($bottomOption['TYPE'] == 'selectbox'):?>
																	<div class="switcher-select switcher-select--large">
																		<?if($bottomOption['TITLE']):?>
																			<div class="switcher-select__label style-switcher__label"><?=$bottomOption['TITLE']?></div>
																		<?endif;?>
																		<?if($bottomOption['LIST']):?>
																			<div class="switcher-select__popup">
																				<?
																				$counter = 1;
																				foreach($bottomOption['LIST'] as $listItemKey => $listItem) {
																					$listItemValue = $listItemKey;
																					$listItemTitle = $listItem;

																					if( is_array($listItem) ) {
																						$listItemTitle = $listItem['TITLE'];
																					}

																					if($this->result['INDEX_BLOCK_OPTIONS'][$this->optionCode] == $listItemValue) {
																						$currentListItem = $listItemTitle;
																						$bCurrent = true;
																					} else {
																						$bCurrent = false;
																					}
																					?>

																					<div class="switcher-select__popup-item <?=$counter == count($bottomOption['LIST']) ? 'switcher-select__popup-item--last' : ''?> <?=$bCurrent ? 'switcher-select__popup-item--current' : ''?>" data-value="<?=$listItemValue?>" data-title="<?=$listItemTitle?>">
																						<?=$listItemTitle?>
																					</div>

																					<?
																				$counter++;
																				}
																				if( !strlen($currentListItem) ) {
																					$currentListItem = $bottomOption['LIST'][ $bottomOption['DEFAULT'] ];
																				}
																				?>
																			</div>

																			<div class="switcher-select__current style-switcher__title style-switcher__title--small"><?=$currentListItem;?><?=\CLite::showSpriteIconSvg('/bitrix/components/aspro/theme.lite/templates/.default/images/svg/sprite.svg#select_arrow-down', 'switcher-select__icon')?></div>

																			<input type="hidden" id="<?=$this->optionCode?>" class="hidden switcher-select__input" name="<?=$this->optionCode?>" value="<?=$this->result['INDEX_BLOCK_OPTIONS'][$this->optionCode]?>" data-index_type="<?=$key;?>" data-index_block="<?=$key2;?>" data-dynamic="Y">
																		<?endif;?>
																	</div>
																<?elseif($bottomOption['TYPE'] == 'checkbox'):?>
																	<div class="filter form-checkbox sm">
																		<input
																			type="checkbox"
																			id="<?=$this->optionCode?>"
																			name="<?=$this->optionCode?>"
																			value="<?=$this->result['INDEX_BLOCK_OPTIONS'][$this->optionCode]?>"
																			<?=($this->result['INDEX_BLOCK_OPTIONS'][$this->optionCode] == 'Y' ? "checked" : "");?>
																			data-index_type="<?=$key;?>"
																			data-index_class="<?=strtolower($bottomOptionKey);?>"
																			data-index_block="<?=$key2;?>"
																			data-dynamic="Y"
																			class="form-checkbox__input"
																		>
																		<label
																			for="<?=$this->optionCode?>"
																			class="form-checkbox__label"
																		><span><?=$bottomOption['TITLE'];?></span><span class="form-checkbox__box form-box"></span></label>
																	</div>
																<?endif;?>
															<?endforeach;?>
														</div>
													<?endif;?>
												</div>
												<?$j++;?>
											<?endforeach;?>
										</div>
									</div>
									<input type="hidden" name="<?=$param;?>" value="<?=$this->result[$param];?>" />
								</div>
							<?endif;?>
						<?endif;?>
					<?endforeach;?>
				</div>
			<?endif;
		}

		private function showCheckBoxOneRow() {
			?>
			<div class="options pull-left" data-code="<?=$this->optionCode?>" data-ajax="<?=$this->arOption['AJAX']?>" data-no-delay="<?=$this->arOption['NO_DELAY']?>">
				<?=self::ShowOptions($this->optionCode, $this->arOption);?>
			</div>
			<?=self::ShowOptionsTitle($this->optionCode, $this->arOption);
		}

		private function checkOptionBlock() {
			return (isset($this->arOption['TYPE_BLOCK']) && $this->arOption['TYPE_BLOCK'] == $this->blockCode)
				&& (isset($this->arOption['THEME']) && $this->arOption['THEME'] == 'Y')
				&& $this->optionCode !== 'BASE_COLOR_CUSTOM'
				&& $this->optionCode !== 'MORE_COLOR_CUSTOM'
				&& $this->optionCode !== 'CUSTOM_BGCOLOR_THEME'
				&& !isset($this->arOption['GROUPS_EXT'])
				&& !isset($this->arOption['TABS']);
		}

		private function checkOptionSkip() {
			if($this->arOption['TYPE'] === 'selectbox' && $this->optionCode !== 'INDEX_TYPE') {
				$countAditionalsOptionsList = !empty($this->arOption['LIST']) ? count($this->arOption['LIST']) : '';
				$hideItemOptions = $this->arOption["ADDITIONAL_OPTIONS"] !== "Y" && $countAditionalsOptionsList <= 1;
			}
			return
				($hideItemOptions || (isset($this->arOption['TAB_GROUP_BLOCK'])) ||
				(
					$this->optionCode == 'BGCOLOR_THEME' &&
					$this->result['SHOW_BG_BLOCK']['VALUE'] != 'Y'
				));
		}

		private function checkOptionHidden() {
			return 	(
						$this->optionCode == 'CONTACTS_USE_TABS' &&
						(
							$this->result['PAGE_CONTACTS']['VALUE'] < 2 ||
							$this->result['CONTACTS_USE_MAP']['VALUE'] != 'Y'
						)
					);
		}

		public static function ShowColorPicker($arOption, $optionCode) {

		}

		public function checkList($arOption) {
			if($arOption["TYPE"] == "selectbox" && $arOption['LIST']) {
				foreach($arOption['LIST'] as $listItemKey => $listItem) {
					$this->checkAll($listItem, $listItemKey);
				}
			}
		}

		public function checkAll($arOption, $arOptionKey = '') {
			$arParamsForCheck = array(
				'DEPENDENT_PARAMS',
				'ADDITIONAL_OPTIONS',
				'TOGGLE_OPTIONS:OPTIONS',
			);

			foreach($arParamsForCheck as $param) {
				$this->checkParams($arOption, $param, $arOptionKey);
			}
		}

		public function checkParams($arOption, $needKey, $arOptionKey) {
			$arOptionKey = $arOptionKey ? '_'.$arOptionKey : '';

			$checkedParams = $this->getCurrentKey($arOption, $needKey);
			if($checkedParams)
			{
				foreach($checkedParams as $paramKey => $paramValue)
				{
					$newVal = $this->request[$paramKey.$arOptionKey."_".$this->siteID];
					if($paramValue["TYPE"] == "checkbox") {
						$newVal = $this->getCheckBoxVal($newVal);
					}

					if($paramKey == "YA_COUNTER_ID" && strlen($newVal))
						$newVal = str_replace('yaCounter', '', $newVal);

					if ($paramValue["TYPE"] == "multiselectbox") {
						$newVal = @implode(",", $newVal);
					}

					\Bitrix\Main\Config\Option::set(self::MODULE_ID, $paramKey.$arOptionKey, $newVal, $this->siteID);

					$this->checkList($paramValue);
					$this->checkAll($paramValue);
				}
			}
		}

		public function setThemeColorsOptions($optionCode) {
			if($optionCode == "BASE_COLOR_CUSTOM" || $optionCode == 'CUSTOM_BGCOLOR_THEME')
				self::$moduleClass::CheckColor($this->request[$optionCode."_".$this->siteID]);

			if($optionCode == "BASE_COLOR" && $this->request[$optionCode."_".$this->siteID] === 'CUSTOM')
				Option::set(MODULE_ID, "NeedGenerateCustomTheme", 'Y', $this->siteID);

			if($optionCode == "BGCOLOR_THEME" && $this->request[$optionCode."_".$this->siteID] === 'CUSTOM')
				Option::set(MODULE_ID, "NeedGenerateCustomThemeBG", 'Y', $this->siteID);
		}

		public function checkCustomFont($optionCode, $newVal) {
			if($optionCode == 'CUSTOM_FONT') {
				$newVal = str_replace(array('>', '<'), '', $newVal);
			}

			return $newVal;
		}

		public function getCheckBoxVal($newVal) {
			if(!strlen($newVal) || $newVal != "Y")
				$newVal = "N";

			return $newVal;
		}

		private function getCurrentKey($arOption, $needKey) {
			$result = false;

			if( strpos($needKey, ':') !== false ) {
				$needKey = explode(':', $needKey);
				if(isset($arOption[ $needKey[0] ][ $needKey[1] ]) && $arOption[ $needKey[0] ][ $needKey[1] ]) {
					$result = $arOption[ $needKey[0] ][ $needKey[1] ];
				}
			} else {
				if(isset($arOption[$needKey]) && $arOption[$needKey]) {
					$result = $arOption[$needKey];
				}
			}

			return is_array($result) ? $result : false;
		}


		public static function getBackParams(&$arValues, &$arDefaultValues, $arOption, $siteId) {
			self::getBackParamsAdditional($arValues, $arDefaultValues, $arOption, $key, $siteId);
			self::getBackParamsToggle($arValues, $arDefaultValues, $arOption, $key, $siteId);
			self::getBackParamsDepend($arValues, $arDefaultValues, $arOption, $key, $siteId);

			self::getBackParamsList($arValues, $arDefaultValues, $arOption, $siteId);
		}

		public static function getBackParamsList(&$arValues, &$arDefaultValues, $arOption, $siteId) {
			if($arOption['LIST']) {
				foreach($arOption['LIST'] as $key => $arListOption) {
					self::getBackParamsAdditional($arValues, $arDefaultValues, $arListOption, $key, $siteId);
					self::getBackParamsToggle($arValues, $arDefaultValues, $arListOption, $key, $siteId);
					self::getBackParamsDepend($arValues, $arDefaultValues, $arListOption, $key, $siteId);
				}
			}
		}

		public static function getBackParamsDepend(&$arValues, &$arDefaultValues, $arOption, $parentKey, $siteId) {
			if(isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS'])
			{
				foreach($arOption['DEPENDENT_PARAMS'] as $childKey => $childOption)
				{
					$optionCurrentKey = $childKey.'_'.$parentKey;
					$arDefaultValues[$optionCurrentKey] = $childOption['DEFAULT'];
					if($childOption['TYPE'] == 'checkbox') {
						$arValues[$optionCurrentKey] = Option::get(self::MODULE_ID, $optionCurrentKey, $childOption['DEFAULT'], $siteId);
					}

					self::getBackParamsList($arValues, $arDefaultValues, $childOption, $siteId);
				}
			}
		}

		public static function getBackParamsAdditional(&$arValues, &$arDefaultValues, $arOption, $parentKey, $siteId) {
			if(isset($arOption['ADDITIONAL_OPTIONS']) && $arOption['ADDITIONAL_OPTIONS'] && is_array($arOption['ADDITIONAL_OPTIONS']))
			{
				foreach($arOption['ADDITIONAL_OPTIONS'] as $childKey => $childOption)
				{
					$optionCurrentKey = $childKey.'_'.$parentKey;
					$arDefaultValues[$optionCurrentKey] = $childOption['DEFAULT'];
					if($childOption['TYPE'] == 'checkbox') {
						$arValues[$optionCurrentKey] = Option::get(self::MODULE_ID, $optionCurrentKey, $childOption['DEFAULT'], $siteId);
					}

					self::getBackParamsList($arValues, $arDefaultValues, $childOption, $siteId);
				}
			}
		}

		public static function getBackParamsToggle(&$arValues, &$arDefaultValues, $arOption, $parentKey, $siteId) {
			if(isset($arOption['TOGGLE_OPTIONS']) && $arOption['TOGGLE_OPTIONS']['OPTIONS'])
			{
				foreach($arOption['TOGGLE_OPTIONS']['OPTIONS'] as $childKey => $childOption)
				{
					$optionCurrentKey = $childKey.'_'.$parentKey;
					$arDefaultValues[$optionCurrentKey] = $childOption['DEFAULT'];
					if($childOption['TYPE'] == 'checkbox') {
						$arValues[$optionCurrentKey] = Option::get(self::MODULE_ID, $optionCurrentKey, $childOption['DEFAULT'], $siteId);
					}

					self::getBackParamsList($arValues, $arDefaultValues, $childOption, $siteId);
				}
			}
		}

	}
}?>