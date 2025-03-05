
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Возможности");
?>
<p>В решении поддерживается множество UI элементов, которые Вы с легкостью можете использовать для развития сайта и добавления нового функционала.</p>
<div class="row"> 						
	<div class="col-md-3 col-sm-6 col-xs-12"> 							
		<div class="more_wrapper">
			<a href="/info/more/typograpy/" class="bordered shadow-no-border-hovered outer-rounded-x"  data-toggle="tooltip" title="" data-original-title="Можно использовать Tooltip!">
				<?=TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH.'/images/svg/more_info.svg#decoration', 'fill-theme svg-inline-more_icon');?>
				<div class="title color-theme-hover">
					Оформление
				</div>
			</a>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12"> 
		<div class="more_wrapper">
			<a href="/info/more/buttons/" class="bordered shadow-no-border-hovered outer-rounded-x"  data-toggle="tooltip" title="" data-original-title="Можно использовать Tooltip!">
				<?=TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH.'/images/svg/more_info.svg#buttons', 'fill-theme svg-inline-more_icon');?>
				<div class="title color-theme-hover">
					Кнопки
				</div>
			</a>
		</div>							
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12"> 
		<div class="more_wrapper">
			<a href="/info/more/icons/" class="bordered shadow-no-border-hovered outer-rounded-x"  data-toggle="tooltip" title="" data-original-title="Можно использовать Tooltip!">
				<?=TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH.'/images/svg/more_info.svg#icons', 'fill-theme svg-inline-more_icon');?>
				<div class="title color-theme-hover">
					Иконки
				</div>
			</a>
		</div>								
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12"> 
		<div class="more_wrapper">
			<a href="/info/more/elements/" class="bordered shadow-no-border-hovered outer-rounded-x"  data-toggle="tooltip" title="" data-original-title="Можно использовать Tooltip!">
				<?=TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH.'/images/svg/more_info.svg#elements', 'fill-theme svg-inline-more_icon');?>
				<div class="title color-theme-hover">
					Элементы
				</div>
			</a>
		</div>								
	</div>
</div>
<br>
<h4>Другие решения компании Аспро</h4>
<p>IT-компания Аспро разрабатывает готовые сайты на 1С-Битрикс и облачные SaaS-системы для малого и среднего бизнеса. Команда помогает запускать интернет-магазины, организовать командную работу, контролировать финансы и продвигаться в интернете.</p>

<div class="row">					
	<div class="col-md-6 col-sm-6 col-xs-12">							
		<div><b>Готовые сайты на 1С-Битрикс</b></div><br>
		<ul>
			<li><a href="/info/more/ecommerce/">Интернет-магазины</a></li>
			<li><a href="/info/more/corp/">Корпоративные сайты</a></li>
			<li><a href="/info/more/themes/">Сайты по тематикам</a></li>
		</ul>

		<div><b>Решения</b></div><br>
		<ul>
			<li><a href="/info/more/dev/">Разработка сайтов</a></li>
			<li><a href="/info/more/licenses/">Лицензии 1С-Битрикс</a></li>
			<li><a href="/info/more/1c/">Интеграция сайта с 1С</a></li>
			<li><a href="/info/more/seo/">Оптимизация и продвижение</a></li>			
			<li><a href="/info/more/support/">Поддержка и сопровождение</a></li>			
		</ul>
	</div>
	<div class="col-md-6 col-sm-6 col-xs-12"> 	 							
		<div><b>Облачные продукты</b></div><br>
		<ul>
			<li><a href="/info/more/cloud/">Аспро.Cloud</a></li>
			<li><a href="/info/more/agile/">Аспро.Agile</a></li>
			<li><a href="/info/more/link/">Аспро.Link</a></li>
			<li><a href="/info/more/hr/">Аспро.HR</a></li>
			<li><a href="/info/more/sklad/">Аспро.Склад</a></li>
			<li><a href="/info/more/fin/">Аспро.Финансы</a></li>
			<li><a href="/info/more/kb/">Аспро.Знания</a></li>
			<li><a href="/info/more/edu/">Аспро.Обучение</a></li>
			<li><a href="/info/more/projects/">Аспро.Проекты</a></li>
			<li><a href="/info/more/crm/">Аспро.CRM</a></li>
			<li><a href="/info/more/acts/">Аспро.Счета и акты</a></li>
		</ul>
	</div>
</div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>