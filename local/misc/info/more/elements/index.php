<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Визуальные элементы");

\Aspro\Lite\Functions\Extensions::init([
	'fancybox',
	'font-awesome',
	'bootstrap',
	'swiper',
	'easing',
]);
?>
<div class="row">
	<div class="col-md-6">
		<h2>Labels</h2>
		<span class="label label-default">Default</span>
		<span class="label label-primary">Primary</span>
		<span class="label label-success">Success</span>
		<span class="label label-info">Info</span>
		<span class="label label-warning">Warning</span>
		<span class="label label-danger">Danger</span>
		<h2 class="spaced">Tooltips</h2>
		<p>Tight pants next level keffiyeh <a rel="tooltip" href="#" data-original-title="Default tooltip">you probably</a> haven't heard of them. Photo booth beard raw denim letterpress vegan messenger bag stumptown. Farm-to-table seitan, mcsweeney's fixie sustainable quinoa 8-bit american apparel <a title="Another tooltip" rel="tooltip" href="#">have a</a> terry richardson vinyl chambray. </p>
	</div>
	<div class="col-md-6">
		<h2>Tabs</h2>
		<div class="tabs">
			<ul class="nav nav-tabs">
				<li class="bordered rounded-4 active"><a href="#popularPosts" data-toggle="tab">Popular</a></li>
				<li class="bordered rounded-4"><a href="#recentPosts" data-toggle="tab">Recent</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="popularPosts">
					Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor.
				</div>
				<div class="tab-pane" id="recentPosts">
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum
				</div>
			</div>
		</div>
	</div>
</div>
<hr class="tall" />
<div class="row">
	<div class="col-md-12">
		<h2>Accordion type 1</h2>					
		<div class="accordion-type-1">
			<div class="item-accordion-wrapper shadow-hovered shadow-no-border-hovered">
				<div class="accordion-head accordion-close stroke-theme-hover" data-toggle="collapse" data-parent="#accordion1" href="#accordion1">
					<span class="switcher-title color_222 font_18">Pricing Tables</span>
					<i class="svg inline  svg-inline-right-arrow" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 1V15M1 8H15" stroke="#999999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></i>
				</div>
				<div id="accordion1" class="panel-collapse collapse">
					<div class="accordion-body color_666">
						<div class="accordion-preview">
							Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor.
						</div>
					</div>
				</div>
			</div>
			<div class="item-accordion-wrapper shadow-hovered shadow-no-border-hovered">
				<div class="accordion-head accordion-close stroke-theme-hover" data-toggle="collapse" data-parent="#accordion2" href="#accordion2">
					<span class="switcher-title color_222 font_18">Pricing Tables</span>
						<i class="svg inline  svg-inline-right-arrow" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 1V15M1 8H15" stroke="#999999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></i>
				</div>
				<div id="accordion2" class="panel-collapse collapse">
					<div class="accordion-body color_666">
						<div class="accordion-preview">
							Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor.
						</div>
					</div>
				</div>
			</div>
			<div class="item-accordion-wrapper shadow-hovered shadow-no-border-hovered">
				<div class="accordion-head accordion-close stroke-theme-hover" data-toggle="collapse" data-parent="#accordion3" href="#accordion3">
					<span class="switcher-title color_222 font_18">Pricing Tables</span>
						<i class="svg inline  svg-inline-right-arrow" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 1V15M1 8H15" stroke="#999999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></i>
				</div>
				<div id="accordion3" class="panel-collapse collapse">
					<div class="accordion-body color_666">
						<div class="accordion-preview">
							Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<hr class="tall" />
<div class="row">	
	<div class="col-md-12">
		<h2>Accordion type 2</h2>
		<div class="accordion-type-2">
			<div class="item-accordion-wrapper shadow-hovered shadow-no-border-hovered">
				<div class="accordion-head accordion-close stroke-theme-hover" data-toggle="collapse" data-parent="#accordion4" href="#accordion4">
					<span class="switcher-title color_222 font_18">Pricing Tables</span>
					<i class="svg inline  svg-inline-right-arrow" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 1V15M1 8H15" stroke="#999999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></i>
				</div>
				<div id="accordion4" class="panel-collapse collapse">
					<div class="accordion-body color_666">
						<div class="accordion-preview">
							Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor.
						</div>
					</div>
				</div>
			</div>
			<div class="item-accordion-wrapper shadow-hovered shadow-no-border-hovered">
				<div class="accordion-head accordion-close stroke-theme-hover" data-toggle="collapse" data-parent="#accordion5" href="#accordion5">
					<span class="switcher-title color_222 font_18">Pricing Tables</span>
						<i class="svg inline  svg-inline-right-arrow" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 1V15M1 8H15" stroke="#999999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></i>
				</div>
				<div id="accordion5" class="panel-collapse collapse">
					<div class="accordion-body color_666">
						<div class="accordion-preview">
							Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor.
						</div>
					</div>
				</div>
			</div>
			<div class="item-accordion-wrapper shadow-hovered shadow-no-border-hovered">
				<div class="accordion-head accordion-close stroke-theme-hover" data-toggle="collapse" data-parent="#accordion6" href="#accordion6">
					<span class="switcher-title color_222 font_18">Pricing Tables</span>
						<i class="svg inline  svg-inline-right-arrow" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 1V15M1 8H15" stroke="#999999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></i>
				</div>
				<div id="accordion6" class="panel-collapse collapse">
					<div class="accordion-body color_666">
						<div class="accordion-preview">
							Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<hr class="tall" />
<div class="row">
	<div class="col-md-6">
		<h2>Toggle</h2>
		<div class="toogle">
			<section class="toggle active">
				<label>Curabitur eget leo at velit imperdiet vague iaculis vitaes?</label>
				<div class="toggle-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc <a href="#">vehicula</a> lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet.</p>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc <a href="#">vehicula</a> lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. In eu justo a felis faucibus ornare vel id metus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; </p>
				</div>
			</section>
			<section class="toggle">
				<label>Curabitur eget leo at imperdiet vague iaculis vitaes?</label>
				<div class="toggle-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur eget leo at velit imperdiet varius. In eu ipsum vitae velit congue iaculis vitae at risus. Nullam tortor nunc, bibendum vitae semper a, volutpat eget massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fringilla, orci sit amet posuere auctor, orci eros pellentesque odio, nec pellentesque erat ligula nec massa. Aenean consequat lorem ut felis ullamcorper posuere gravida tellus faucibus. Maecenas dolor elit, pulvinar eu vehicula eu, consequat et lacus. Duis et purus ipsum. In auctor mattis ipsum id molestie. Donec risus nulla, fringilla a rhoncus vitae, semper a massa. Vivamus ullamcorper, enim sit amet consequat laoreet, tortor tortor dictum urna, ut egestas urna ipsum nec libero. Nulla justo leo, molestie vel tempor nec, egestas at massa. Aenean pulvinar, felis porttitor iaculis pulvinar, odio orci sodales odio, ac pulvinar felis quam sit.</p>
				</div>
			</section>
			<section class="toggle">
				<label>Curabitur eget leo at velit imperdiet vague iaculis vitaes?</label>
				<div class="toggle-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur eget leo at velit imperdiet varius. In eu ipsum vitae velit congue iaculis vitae at risus. Nullam tortor nunc, bibendum vitae semper a, volutpat eget massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fringilla, orci sit amet posuere auctor, orci eros pellentesque odio, nec pellentesque erat ligula nec massa. Aenean consequat lorem ut felis ullamcorper posuere gravida tellus faucibus. Maecenas dolor elit, pulvinar eu vehicula eu, consequat et lacus. Duis et purus ipsum. In auctor mattis ipsum id molestie. Donec risus nulla, fringilla a rhoncus vitae, semper a massa. Vivamus ullamcorper, enim sit amet consequat laoreet, tortor tortor dictum urna, ut egestas urna ipsum nec libero. Nulla justo leo, molestie vel tempor nec, egestas at massa. Aenean pulvinar, felis porttitor iaculis pulvinar, odio orci sodales odio, ac pulvinar felis quam sit.</p>
				</div>
			</section>
		</div>
	</div>
	<div class="col-md-6">
		<h2>One Toggle Open At A Time</h2>
		<div class="toogle toogle-accordion">
			<section class="toggle active">
				<label>Curabitur eget leo at velit imperdiet vague iaculis vitaes?</label>
				<div class="toggle-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc <a href="#">vehicula</a> lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. In eu justo a felis faucibus ornare vel id metus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In eu libero ligula. Fusce eget metus lorem, ac viverra leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc <a href="#">vehicula</a> lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. In eu justo a felis faucibus ornare vel id metus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In eu libero ligula. Fusce eget metus lorem, ac viverra leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc <a href="#">vehicula</a> lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. In eu justo a felis faucibus ornare vel id metus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In eu libero ligula. Fusce eget metus lorem, ac viverra leo. </p>
				</div>
			</section>
			<section class="toggle">
				<label>Curabitur eget leo at velit imperdiet vague iaculis vitaes?</label>
				<div class="toggle-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur eget leo at velit imperdiet varius. In eu ipsum vitae velit congue iaculis vitae at risus. Nullam tortor nunc, bibendum vitae semper a, volutpat eget massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fringilla, orci sit amet posuere auctor, orci eros pellentesque odio, nec pellentesque erat ligula nec massa. Aenean consequat lorem ut felis ullamcorper posuere gravida tellus faucibus. Maecenas dolor elit, pulvinar eu vehicula eu, consequat et lacus. Duis et purus ipsum. In auctor mattis ipsum id molestie. Donec risus nulla, fringilla a rhoncus vitae, semper a massa. Vivamus ullamcorper, enim sit amet consequat laoreet, tortor tortor dictum urna, ut egestas urna ipsum nec libero. Nulla justo leo, molestie vel tempor nec, egestas at massa. Aenean pulvinar, felis porttitor iaculis pulvinar, odio orci sodales odio, ac pulvinar felis quam sit.</p>
				</div>
			</section>
			<section class="toggle">
				<label>Curabitur eget leo at velit imperdiet vague iaculis vitaes?</label>
				<div class="toggle-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur eget leo at velit imperdiet varius. In eu ipsum vitae velit congue iaculis vitae at risus. Nullam tortor nunc, bibendum vitae semper a, volutpat eget massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer fringilla, orci sit amet posuere auctor, orci eros pellentesque odio, nec pellentesque erat ligula nec massa. Aenean consequat lorem ut felis ullamcorper posuere gravida tellus faucibus. Maecenas dolor elit, pulvinar eu vehicula eu, consequat et lacus. Duis et purus ipsum. In auctor mattis ipsum id molestie. Donec risus nulla, fringilla a rhoncus vitae, semper a massa. Vivamus ullamcorper, enim sit amet consequat laoreet, tortor tortor dictum urna, ut egestas urna ipsum nec libero. Nulla justo leo, molestie vel tempor nec, egestas at massa. Aenean pulvinar, felis porttitor iaculis pulvinar, odio orci sodales odio, ac pulvinar felis quam sit.</p>
				</div>
			</section>
		</div>
	</div>
</div>
<hr class="tall" />
<div class="row">
	<div class="col-md-12">
		<h2 class="short">Alerts</h2>
		<p>Styles for success, info, warning, and error messages</p>
		<div class="alert alert-success">
			<strong>Well done!</strong> You successfully read this important alert message.
		</div>
		<div class="alert alert-info">
			<strong>Heads up!</strong> This alert needs your attention, but it's not super important.
		</div>
		<div class="alert alert-warning">
			<strong>Warning!</strong> Best check yo self, you're not looking too good.
		</div>
		<div class="alert alert-danger">
			<strong>Oh snap!</strong> Change a few things up and try submitting again.
		</div>
	</div>
</div>
<hr class="tall" />
<div class="row">
	<div class="col-md-12">
		<h2 class="short">Progress Bar</h2>
		<div class="progress-bars">
			<div class="progress-label">
				<span>HTML/CSS</span>
			</div>
			<div class="progress">
				<div class="progress-bar progress-bar-primary" data-appear-progress-animation="100%">
					<span class="progress-bar-tooltip">100%</span>
				</div>
			</div>
			<div class="progress-label">
				<span>Design</span>
			</div>
			<div class="progress">
				<div class="progress-bar progress-bar-primary" data-appear-progress-animation="85%" data-appear-animation-delay="300">
					<span class="progress-bar-tooltip">85%</span>
				</div>
			</div>
			<div class="progress-label">
				<span>WordPress</span>
			</div>
			<div class="progress">
				<div class="progress-bar progress-bar-primary" data-appear-progress-animation="75%" data-appear-animation-delay="600">
					<span class="progress-bar-tooltip">75%</span>
				</div>
			</div>
			<div class="progress-label">
				<span>Photoshop</span>
			</div>
			<div class="progress">
				<div class="progress-bar progress-bar-primary" data-appear-progress-animation="85%" data-appear-animation-delay="900">
					<span class="progress-bar-tooltip">85%</span>
				</div>
			</div>
		</div>
		<div class="progress">
			<div class="progress-bar progress-bar-success" style="width: 40%">
				<span class="sr-only">40% Complete (success)</span>
			</div>
		</div>
		<div class="progress">
			<div class="progress-bar progress-bar-info" style="width: 20%">
				<span class="sr-only">20% Complete</span>
			</div>
		</div>
		<div class="progress">
			<div class="progress-bar progress-bar-warning" style="width: 60%">
				<span class="sr-only">60% Complete (warning)</span>
			</div>
		</div>
		<div class="progress">
			<div class="progress-bar progress-bar-danger" style="width: 80%">
				<span class="sr-only">80% Complete</span>
			</div>
		</div>
	</div>
</div>
<hr class="tall" />
<div class="row">
	<div class="col-md-12">
		<h2 class="short">Forms</h2>
		<p>Individual form controls automatically receive some global styling.</p>
		<form action="" type="post">
			<div class="row">
				<div class="form-group">
					<div class="col-md-6">
						<label>Your name <span class="required-star">*</span></label>
						<input type="text" value="" maxlength="100" class="form-control" name="name" id="name">
					</div>
					<div class="col-md-6">
						<label>Your email address <span class="required-star">*</span></label>
						<input type="email" value="" maxlength="100" class="form-control" name="email" id="email">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-12">
						<label>Comment <span class="required-star">*</span></label>
						<textarea maxlength="5000" rows="10" class="form-control" name="comment" id="comment" style="height: 138px;"></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<input type="submit" value="Post Comment" class="btn btn-default btn-lg" data-loading-text="Loading...">
				</div>
			</div>
		</form>
	</div>
</div>
<hr class="tall" />
<div class="row"> 						 
	<div class="col-md-12">
		<div class="rounded-x grey-bg order-block__wrapper">
			<div class="order-info-block">
				<div class="line-block line-block--align-normal line-block--40">
					<div class="line-block__item flex-1">
						<h3>Пример формы</h3>
						<span>Произвольный текст</span>
					</div>
					<div class="line-block__item order-info-btns">
						<div class="line-block line-block--align-normal line-block--12">
							<div class="line-block__item">
								<span class="btn btn-default btn-lg min_width--300 " data-event="jqm"
									data-param-id="<?=\TSolution::getFormID("aspro_lite_question"); ?>"
									data-name="question">
									<span>Пример формы</span>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<blockquote class="info">Text</blockquote>
	</div>
	<div class="col-md-12">
		<blockquote class="danger">Text</blockquote>
	</div>
	<div class="col-md-12">
		<blockquote class="code">Text</blockquote>
	</div>
</div>
<hr class="tall" />
<div class="row">
	<div class="col-md-6">
		<h2 class="short">Carousel</h2>
		<p>Swiper - The Most Modern Mobile Touch Slider.</p>
		<div class="swiper slider-solution" data-plugin-options='{"direction":"horizontal","init":false,"keyboard":{"enabled":true},"loop":false,"pagination":{"enabled":true,"el":".gallery-slider__slider-pagination-main"},"slidesPerView":1}'
		>
			<div class="swiper-wrapper">
				<div class="swiper-slide"><a href="<?=SITE_DIR?>images/inner1.jpg" class="fancy" data-fancybox="gallery2"><img alt="" class="img-responsive" src="/images/inner1.jpg"></a></div>
				
				<div class="swiper-slide"><a href="<?=SITE_DIR?>images/inner2.jpg" class="fancy" data-fancybox="gallery2"><img alt="" class="img-responsive" src="/images/inner2.jpg"></a></div>
			
				<div class="swiper-slide"><a href="<?=SITE_DIR?>images/inner3.jpg" class="fancy" data-fancybox="gallery2"><img alt="" class="img-responsive" src="/images/inner3.jpg"></a></div>
			</div>
			<div class="slider-nav swiper-button-next">
				<?= TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH . '/images/svg/arrows.svg#right-7-12', 'stroke-dark-light', [
					'WIDTH' => 7, 
					'HEIGHT' => 12
				]); ?>
			</div>
			<div class="slider-nav swiper-button-prev">
				<?= TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH . '/images/svg/arrows.svg#left-7-12', 'stroke-dark-light', [
					'WIDTH' => 7, 
					'HEIGHT' => 12
				]); ?>
			</div>
		</div>
		<div class="gallery-slider__slider-pagination-main"></div>
	</div>
	<div class="col-md-6">
		<h2>Fancybox</h2>
		<div class="item zomm_wrapper-block">
			<a class="dark_block_animate fancy" data-fancybox="gallery" href="<?=SITE_TEMPLATE_PATH?>/images/project.jpg">
				<img class="img-responsive" src="<?=SITE_TEMPLATE_PATH?>/images/project.jpg">
			</a>
		</div>
	</div>
</div>
<hr class="tall" />
<div class="row">
	<div class="col-md-12">
		<h2>Images</h2>
		<img class="img-rounded" alt="165x165" style="width: 165px; height: 165px;" src="<?=SITE_TEMPLATE_PATH?>/images/holder.png">
		<img class="img-circle" alt="165x165" style="width: 165px; height: 165px;" src="<?=SITE_TEMPLATE_PATH?>/images/holder.png">
		<img class="img-thumbnail" alt="165x165" style="width: 165px; height: 165px;" src="<?=SITE_TEMPLATE_PATH?>/images/holder.png">
	</div>
	<div class="col-md-6">
		<h2 class="spaced">Thumbnails List</h2>
		<div class="row">
			<div class="col-md-4">
				<a class="thumbnail" href="#">
					<img src="<?=SITE_TEMPLATE_PATH?>/images/holder.png">
				</a>
			</div>
			<div class="col-md-4">
				<a class="thumbnail" href="#">
					<img src="<?=SITE_TEMPLATE_PATH?>/images/holder.png">
				</a>
			</div>
			<div class="col-md-4">
				<a class="thumbnail" href="#">
					<img src="<?=SITE_TEMPLATE_PATH?>/images/holder.png">
				</a>
			</div>
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>