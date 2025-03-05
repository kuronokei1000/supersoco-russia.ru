<?
namespace Aspro\Lite\Popover;

use CLite as Solution,
	Aspro\Lite\Functions\Extensions;

class Tooltip extends Base {
	protected static function getContentClass() :string {
		return 'xpopover-content--mobile-center';
	}
	
	public function __construct() {
		return parent::__construct(
			['offset' => '-15px,-5px'],
			function ($text) {
				?>
				<?=$text?>
				<?
			}
		);
	}
}