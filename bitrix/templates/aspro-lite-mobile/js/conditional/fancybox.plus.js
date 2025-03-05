$(document).on("click", ".fancy-plus", function (e) {
  e.preventDefault();
  appAspro.loadCSS([
    `${arAsproOptions["SITE_TEMPLATE_PATH"]}/css/jquery.fancybox.min.css`,
    `${arAsproOptions["SITE_TEMPLATE_PATH"]}/css/fancybox-gallery.min.css`,
  ]);
  appAspro.loadScript(`${arAsproOptions["SITE_TEMPLATE_PATH"]}/js/jquery.fancybox.min.js`, () => {
    const $target = $(this)[0];
    const $itemsContainer = $target.closest("[data-additional_items]");

    if ($itemsContainer) {
      const arItems = $itemsContainer.dataset.additional_items
        ? JSON.parse($itemsContainer.dataset.additional_items)
        : false;

      if (arItems && arItems.length) {
        const index = Array.prototype.slice.call($target.parentNode.children).indexOf($target);

        $.fancybox.open(arItems, { loop: false, buttons: ["close"] }, index);
      }
    }

    /*if (!$(this).hasClass("initied")) {
      $(this).addClass("initied");
      $(this).click();
    }*/
  });
});
