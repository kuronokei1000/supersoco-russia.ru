<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Кнопки");

\Aspro\Lite\Functions\Extensions::init('font-awesome');
?>
<div class="row">
	<div class="col-md-6">
		<h2>Buttons</h2>
		<button type="button" class="btn btn-default mb-10 mr-10">Default</button>
		<button type="button" class="btn btn-default btn-primary mb-10 mr-10">Primary</button>
		<button type="button" class="btn btn-default btn-success mb-10 mr-10">Success</button>
		<button type="button" class="btn btn-default btn-info mb-10 mr-10">Info</button>
		<button type="button" class="btn btn-default btn-warning mb-10 mr-10">Warning</button>
		<button type="button" class="btn btn-default btn-danger mb-10 mr-10">Danger</button>
		<button type="button" class="btn btn-transparent mb-10 mr-10">Transparent</button>
		<button type="button" class="btn btn-default mb-10 mr-10 btn-transparent-bg">Transparent with border</button>
		<button type="button" class="btn btn-default mb-10 mr-10 btn-transparent-border">Transparent border</button>
		<button type="button" class="btn btn-link mb-10 mr-10">Link</button>
		<h2 class="spaced">Buttons Disabled</h2>
		<button type="button" class="btn btn-default mb-10 mr-10 " disabled="disabled">Default Button</button>
		<button type="button" class="btn btn-primary mb-10 mr-10" disabled="disabled">Primary button</button>			
	</div>

	<div class="col-md-6">
		<h2>Buttons Sizes</h2>
		<p>
			<button type="button" class="btn btn-default mb-10 mr-10 btn-elg">Extra large button</button>
			<button type="button" class="btn btn-default mb-10 mr-10 btn-elg" disabled="disabled">Extra large button</button>
			<br />
			<button type="button" class="btn btn-default mb-10 mr-10 btn-lg">Large button</button>
			<button type="button" class="btn btn-default mb-10 mr-10 btn-lg" disabled="disabled">Large button</button>
			<br />
			<button type="button" class="btn btn-default mb-10 mr-10">Default button</button>
			<button type="button" class="btn btn-default mb-10 mr-10" disabled="disabled">Default button</button>
			<br />
			<button type="button" class="btn btn-default mb-10 mr-10 btn-sm">Small button</button>
			<button type="button" class="btn btn-default mb-10 mr-10 btn-sm" disabled="disabled">Small button</button>
			<br />
			<button type="button" class="btn btn-default mb-10 mr-10 btn-xs">Extra small button</button>
			<button type="button" class="btn btn-default mb-10 mr-10 btn-xs" disabled="disabled">Extra small button</button>
		</p>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>