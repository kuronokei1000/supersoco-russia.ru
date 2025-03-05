appAspro.headerMenu = {};

BX.ready(() => {
  $("#mobileheader .burger").on("click", function () {
    // SwipeMobileMenu();
    const $menu = $("#mobilemenu");
    appAspro.headerPhones.close(() => {
      if ($menu.hasClass("opened")) {
        appAspro.headerMenu.close($menu);
      } else {
        appAspro.loadScript([arAsproOptions["SITE_TEMPLATE_PATH"] + "/vendor/jquery.cookie.js"]);
        appAspro.headerMenu.open($menu);
      }
    });
  });
  $(".mobilemenu__close").on("click", function () {
    appAspro.headerMenu.close();
  });
});

appAspro.headerMenu.moveMobileMenuWrapNext = function ($menu) {
  const $scroller = $menu.find(".mobilemenu");
  const $inner = $menu.find(".mobilemenu__inner");
  if ($inner.length) {
    const params = $inner.data("params");
    const $dropdownNext = $menu.find(".mobilemenu__menu-item--expanded>.mobilemenu__menu-dropdown").eq(params.depth);
    if ($dropdownNext.length) {
      // save scroll position
      params.scroll[params.depth] = parseInt($menu.scrollTop());

      // height while move animating
      params.height[params.depth + 1] = Math.max(
        $dropdownNext.height(),
        !params.depth
          ? $inner.height()
          : $menu
              .find(".mobilemenu__menu-item--expanded>.mobilemenu__menu-dropdown")
              .eq(params.depth - 1)
              .height()
      );
      $scroller.css("height", params.height[params.depth + 1] + "px");

      // inc depth
      ++params.depth;

      // translateX for move
      $inner.css("transform", "translateX(" + -100 * params.depth + "%)");

      // scroll to top
      setTimeout(function () {
        $menu.animate({ scrollTop: 0 }, 200);
      }, 100);

      // height on enimating end
      const h =
        $dropdownNext.height() +
        parseInt($dropdownNext.css("padding-top")) +
        parseInt($dropdownNext.css("padding-bottom"));
      setTimeout(function () {
        if (h) {
          $scroller.css("height", h + "px");
        } else {
          $scroller.css("height", "");
        }
      }, 200);
    }

    $inner.data("params", params);
  }
};

appAspro.headerMenu.moveMobileMenuWrapPrev = function ($menu) {
  const $scroller = $menu.find(".mobilemenu");
  const $inner = $menu.find(".mobilemenu__inner");
  if ($inner.length) {
    const params = $inner.data("params");
    if (params.depth > 0) {
      const $dropdown = $menu.find(".mobilemenu__menu-item--expanded>.mobilemenu__menu-dropdown").eq(params.depth - 1);
      if ($dropdown.length) {
        // height while move animating
        $scroller.css("height", params.height[params.depth] + "px");

        // dec depth
        --params.depth;

        // translateX for move
        $inner.css("transform", "translateX(" + -100 * params.depth + "%)");

        // restore scroll position
        setTimeout(function () {
          $menu.animate({ scrollTop: params.scroll[params.depth] }, 200);
        }, 100);

        // height on enimating end
        const h = !params.depth
          ? false
          : $menu
              .find(".mobilemenu__menu-item--expanded>.mobilemenu__menu-dropdown")
              .eq(params.depth - 1)
              .height();
        setTimeout(function () {
          if (h) {
            $scroller.css("height", h + "px");
          } else {
            $scroller.css("height", "");
          }
        }, 200);
      }
    }

    $inner.data("params", params);
  }
};

appAspro.headerMenu.open = ($menu = $("#mobilemenu")) => {
  if ($("body").hasClass("mmenu_leftside")) {
    // blur body
    $("#mobileheader").addClass("filter-none");
    $("#mobilemenu").addClass("filter-none");

    appAspro.removeOverlay();
    appAspro.addOverlay("#mobileheader");
  } else {
    // set menu top = bottom of header
    $menu.css({
      top: +($("#mobileheader")[0].getBoundingClientRect().top + $("#mobileheader").height()) + "px",
    });
    // change burger icon
    // $('#mobileheader .burger').addClass('c');
  }
  // show menu
  appAspro.loadCSS("/bitrix/templates/aspro-lite/css/left-menu.css");
  $menu.addClass("show");

  const $inner = $menu.find(".mobilemenu__inner");
  let params = $inner.data("params");
  if (typeof params === "undefined") {
    params = {
      depth: 0,
      scroll: {},
      height: {},
    };
  }
  $inner.data("params", params);
};

appAspro.headerMenu.close = ($menu = $("#mobilemenu")) => {
  $menu.removeClass("show");

  appAspro.removeOverlay();
};

$(document).on("click", ".mobilemenu__menu .toggle_block", function (e) {
  e.stopPropagation();

  const $menu = $("#mobilemenu");

  const $this = $(this);
  const $item = $this.closest(".mobilemenu__menu-item");

  if ($item.hasClass("mobilemenu__menu-item--parent")) {
    e.preventDefault();

    $item.addClass("mobilemenu__menu-item--expanded");
    appAspro.headerMenu.moveMobileMenuWrapNext($menu);
  }
});

$(document).on("click", ".mobilemenu__menu a", function (e) {
  const $menu = $("#mobilemenu");
  const $this = $(this);
  const $item = $this.closest(".mobilemenu__menu-item");

  if ($item.hasClass("mobilemenu__menu-item--back")) {
    e.preventDefault();

    appAspro.headerMenu.moveMobileMenuWrapPrev($menu);
    setTimeout(function () {
      $item.closest(".mobilemenu__menu-item--expanded").removeClass("mobilemenu__menu-item--expanded");
    }, 400);
  } else {
    const href = $this.attr("href");
    if (typeof href !== "undefined") {
      if (href.length) {
        window.location.href = href;
        //window.location.reload();
      } else {
        if ($item.hasClass("mobilemenu__menu-item--parent")) {
          e.preventDefault();

          $item.addClass("mobilemenu__menu-item--expanded");
          appAspro.headerMenu.moveMobileMenuWrapNext($menu);

          return;
        } else if ($item.hasClass("mobilemenu__menu-item--title")) {
          e.preventDefault();

          return;
        }
      }
    }

    appAspro.headerMenu.close();
  }
});
