<?
namespace Aspro\Lite\Popover;

use CLite as Solution,
	Aspro\Lite\Functions\Extensions;

class OrderStatus extends Base {
	public static function initExtensions() {
		parent::initExtensions();

		Extensions::init(['popover.order_status']);
	}
	
	public function __construct(string $svgStatusSprite, array $arStatuses, array $arVisibleStatuses) {
		return parent::__construct(
			['offset' => '-15px,-5px', 'trigger' => 'hover,click'],
			function ($arOrder, $statusClass) use ($svgStatusSprite, $arStatuses, $arVisibleStatuses) {
				?>
				<div class="xpopover--order-status xpopover--order-status--<?=$statusClass?>">
					<div class="xpopover--order-status__steps">
						<?$bMark = true;?>
						<?foreach ($arVisibleStatuses as $statusId => $arStatus):?>
							<div class="xpopover--order-status__step<?=($bMark ? ' mark' : '')?>">
								<div class="xpopover--order-status__step-progress">
									<div class="xpopover--order-status__step-progress__dot"><?=Solution::showSpriteIconSvg($svgStatusSprite.'#dot-16-16', 'status-dot', ['WIDTH' => 16, 'HEIGHT' => 16]);?></div>
									<div class="xpopover--order-status__step-progress__line"></div>
								</div>

								<div class="xpopover--order-status__step-info">
									<div class="xpopover--order-status__step-name"><?=$arStatus['NAME']?></div>
									<?if (strlen($arStatus['DESCRIPTION'])):?>
										<div class="xpopover--order-status__step-dsc font_14 color_999"><?=$arStatus
										['DESCRIPTION']?></div>
									<?endif;?>
								</div>
							</div>
							<?
							if (
								$bMark &&
								$statusId === $arStatuses[$arOrder['STATUS_ID']]['LAST_VISIBLE']['ID']
							) {
								// do not mark next steps
								$bMark = false;
							}
							?>
						<?endforeach;?>
					</div>
				</div>
				<?
			}
		);
	}
}