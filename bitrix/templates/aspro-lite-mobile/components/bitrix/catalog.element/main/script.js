document.addEventListener("DOMContentLoaded", function () {
  const $jsBlocks = document.querySelectorAll("[data-js-block]");

  if ($jsBlocks.length) {
    for (let i = 0; i < $jsBlocks.length; i++) {
      const $container = $jsBlocks[i];
      const $block = $container.dataset.jsBlock ? document.querySelector($container.dataset.jsBlock) : false;

      if ($block) {
        $container.appendChild($block);
        $container.removeAttribute(["data-js-block"]);
      }
    }
  }
  $(".choise").on("click", function () {
    var $this = $(this);
    if (typeof $this.data("block") !== "undefined") {
      var block = $this.attr("data-block");

      try {
        var $block = BX(block) ? $("#" + block) : $(".detail-block." + block);
      } catch (e) {
        var $block = $(block);
      }

      if ($block.length) {
        if ($block.closest(".tab-pane").length) {
          var offset = -206;
          if (typeof arAsproOptions !== "undefined") {
            offset = window.matchMedia("(max-width:991px)").matches
              ? arAsproOptions.THEME.HEADER_MOBILE_FIXED !== "Y" || arAsproOptions.THEME.HEADER_MOBILE_SHOW !== "ALWAYS"
                ? -187 + 62
                : -187
              : arAsproOptions.THEME.TOP_MENU_FIXED !== "Y"
              ? -206 + 81
              : -206;
          }

          $block.data("offset", offset);
          $('.ordered-block a[href="#' + $block.closest(".tab-pane").attr("id") + '"]').click();
        }

        scrollToBlock($block);
      }
    }
  });
});
