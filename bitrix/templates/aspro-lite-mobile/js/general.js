/* common */
const funcDefined = func => {
  try {
    if (typeof func == "function") return true;
    else return typeof window[func] === "function";
  } catch (e) {
    return false;
  }
};
const readyDOM = callback => {
  if (document.readyState !== "loading") {
    callback();
  } else {
    document.addEventListener("DOMContentLoaded", callback);
  }
};
const typeofExt = item => {
  const _toString = Object.prototype.toString;
  return _toString.call(item).slice(8, -1).toLowerCase();
};
const throttle = (f, t) => {
  let throttled, saveThis, saveArgs;
  const wrapper = function () {
    if (throttled) {
      saveThis = this;
      saveArgs = arguments;
      return;
    }
    throttled = true;
    f.apply(this, arguments);
    setTimeout(function () {
      throttled = false;
      if (saveArgs) {
        wrapper.apply(saveThis, saveArgs);
        saveThis = saveArgs = null;
      }
    }, t);
  };
  return wrapper;
};

const debounce = (f, t) => {
  return function (args) {
    var previousCall = this.lastCall;
    this.lastCall = Date.now();
    if (previousCall && this.lastCall - previousCall <= t) {
      clearTimeout(this.lastCallTimer);
    }
    this.lastCallTimer = setTimeout(function () {
      f(args);
    }, t);
  };
};

/* stub */
const InitFancyBox = () => {};
const InitFancyBoxVideo = () => {};
const initCountdown = () => {};

/* custom load */
appAspro.loadScript = (path, cb) => {
  BX.loadScript(path, function () {
    if (typeof cb === "function") {
      cb();
    }
  });
};
appAspro.loadCSS = (path, cb) => {
  BX.loadCSS(path);
};

/* overlay */
appAspro.addOverlay = (selector = "body", overlayClass = "overlay") => {
  if (!document.querySelector(`.${overlayClass}`)) {
    const node = document.createElement("div");
    node.classList.add(overlayClass);
    document.querySelector(selector).append(node);

    $("body").addClass(`jqm-initied`);

    BX.onCustomEvent("onAddOverlay");
  }
};
appAspro.removeOverlay = (selectorClass = "overlay") => {
  const $node = document.querySelector(`.${selectorClass}`);
  if ($node) {
    $node.remove();

    $("body").removeClass("jqm-initied");
  }
};
appAspro.closeOverlay = cb => {
  appAspro.headerPhones.close(() => {
    appAspro.headerMenu.close();
    appAspro.headerSearch.close();

    BX.onCustomEvent("onCloseOverlay");

    if (typeof cb === "function") {
      cb();
    }
  });
};
$(document).on("click", ".overlay", function (e) {
  appAspro.closeOverlay();
});

/* get url query params */
function parseUrlQuery() {
  const data = {};
  if (location.search) {
    const pair = location.search.substr(1).split("&");
    for (let i = 0; i < pair.length; i++) {
      const param = pair[i].split("=");
      data[param[0]] = param[1];
    }
  }
  return data;
}

/* ajax more items */
$(document).on("click", ".ajax_load_btn", function () {
  let url = $(this).closest(".bottom_nav").find(".arrows-pagination__next").attr("href");
  const th = $(this).find(".more_text_ajax"),
    _this = $(this);

  let bottom_nav = $(this).closest(".bottom_nav");

  if (!th.hasClass("loadings")) {
    th.addClass("loadings");

    const objUrl = parseUrlQuery(),
      obData = { AJAX_REQUEST: "Y", ajax_get: "Y", bitrix_include_areas: "N" };

    if ("clear_cache" in objUrl && objUrl.clear_cache == "Y") {
      obData.clear_cache = "Y";
    }

    //index page
    // if ($(".body.index").length) {

    // get mainblock action file
    let $dragBlock = th.closest(".drag-block");
    if ($dragBlock.length) {
      let class_block = $dragBlock.data("class").replace("_drag", "");
      class_block = class_block.replace(/\s/g, "");
      obData.BLOCK = class_block;

      $jsParamsBlock = $dragBlock[0].querySelector(".js-request-data");
      if ($jsParamsBlock) {
        if ($jsParamsBlock.dataset.action) {
          let action = new URL(window.location.origin + url);
          url = window.location.origin + $jsParamsBlock.dataset.action + action.search;
        }
      }
    }
    // }

    if (_this.closest(".ajax-pagination-wrapper").length) {
      obData.BLOCK = _this.closest(".ajax-pagination-wrapper").data("class");
    }

    if ($(".banners-small.front").length) {
      obData.MD = $(".banners-small.front").find(".items").data("colmd");
      obData.SM = $(".banners-small.front").find(".items").data("colsm");
    }

    // tabs block
    if (_this.closest(".tab-content-block").length) {
      var filter = th.closest(".tab-content-block").data("filter");
      obData.GLOBAL_FILTER = filter;
    }

    $.ajax({
      url: url,
      data: BX.ajax.prepareData(obData),
      success: function (html) {
        var html = html.trim();
        var mobileBottomClicked = bottom_nav.hasClass("mobile_slider");
        var hasMobileBottomNav = $(html).find(".bottom_nav.mobile_slider");
        var bottomNav = hasMobileBottomNav.length ? hasMobileBottomNav : $(html).find(".bottom_nav");
        var bottomNavHtml = bottomNav.html();
        var bottomNavScrollClass = bottomNav.data("scroll-class");
        var hasBottomNav = $(html).find(".ajax_load_btn").length;

        var eventdata = { action: "ajaxContentLoaded", content: html };

        if ($(".banners-small.front").length) {
          $(".banners-small .items.row").append(html);
          $(".bottom_nav").html($(".banners-small .items.row .bottom_nav").html());
          $(".banners-small .items.row .bottom_nav").remove();
        } else if (bottom_nav.data("append") !== undefined && bottom_nav.data("parent") !== undefined) {
          var $slider = th.closest(bottom_nav.data("parent")).find(".owl-carousel");
          if ($slider.length) {
            var obData = BX.processHTML(html);
            html = obData.HTML;

            var $slides = $("<div>" + html + "</div>").find(">*");
            $slides.each(function () {
              if (!$(this).hasClass("wrap_nav")) {
                $slider.trigger("add.owl.carousel", [$(this).wrap("<div></div>").parent().html()]);
              }
            });
            $slider.trigger("refresh.owl.carousel");

            setTimeout(function () {
              BX.ajax.processScripts(obData.SCRIPT);
            }, 100);
          } else {
            var target = html;
            if (bottom_nav.data("target") !== undefined) {
              target = $(html).find(bottom_nav.data("target"));
            }
            if (mobileBottomClicked || hasMobileBottomNav.length) {
              var mobileSliderNav = th.closest(bottom_nav.data("parent")).find(".bottom_nav.mobile_slider");
              if (mobileSliderNav.length) {
                mobileSliderNav.before(target);
              } else {
                bottom_nav.before(target);
              }
            } else {
              th.closest(bottom_nav.data("parent")).find(bottom_nav.data("append")).append(target);
            }
            th.closest(bottom_nav.data("parent")).find(bottom_nav.data("append")).find(".bottom_nav_wrapper").remove();

            if (hasBottomNav) {
              if (bottomNavScrollClass !== undefined) {
                th.closest(bottom_nav.data("parent")).find(bottomNavScrollClass).addClass("has-bottom-nav");
              }
              th.closest(bottom_nav.data("parent")).find(bottom_nav.data("append")).addClass("has-bottom-nav");
            } else {
              if (bottomNavScrollClass !== undefined) {
                th.closest(bottom_nav.data("parent")).find(bottomNavScrollClass).removeClass("has-bottom-nav");
              }
              th.closest(bottom_nav.data("parent")).find(bottom_nav.data("append")).removeClass("has-bottom-nav");
            }
          }

          bottom_nav = th.closest(bottom_nav.data("parent")).find(".bottom_nav");
          bottom_nav.html(bottomNavHtml);
          var icon = bottom_nav.find(".svg-inline-bottom_nav-icon");
          icon.css("display", "");

          eventdata.container = th.closest(bottom_nav.data("parent"));
        } else {
          $(html).insertBefore($(".blog .bottom_nav"));
          $(".bottom_nav").html($(".blog .bottom_nav:hidden").html());
          $(".blog .bottom_nav:hidden").remove();
        }

        setTimeout(function () {
          BX.onCustomEvent("onCompleteAction", [eventdata, th[0]]);
          th.removeClass("loading");
        }, 100);
      },
    });
  }
});

/* appear */
$.fn.iAppear = function (callback, options) {
  if (typeof $.fn.iAppear.useObserver === "undefined") {
    $.fn.iAppear.useObserver = typeof window["IntersectionObserver"] === "function";
  }

  if ($.fn.iAppear.useObserver) {
    var options = $.extend(
      {
        root: null,
        rootMargin: "150px 0px 150px 0px",
        threshold: 0.0,
      },
      options
    );

    $(this).each(function (i, appearBlock) {
      var observer = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (element) {
          if (element.intersectionRatio > 0 && !element.target.dataset.iAppeared) {
            element.target.dataset.iAppeared = true;

            if (typeof callback === "function") {
              callback.call(element.target);
            }
          }
        });
      }, options);

      observer.observe(appearBlock);
    });
  }
};

/* scroll block */
scrollToBlock = block => {
  if ($(block).length) {
    scrollToBlock.last = block;

    if (typeof $(block).data("toggle") !== "undefined") {
      $(block).click();
    }

    var offset = -81 - 64;
    if (typeof $(block).data("offset") !== "undefined") {
      offset = $(block).data("offset");
    } else {
      if (typeof arAsproOptions !== "undefined") {
        offset =
          arAsproOptions.THEME.HEADER_MOBILE_FIXED !== "Y" || arAsproOptions.THEME.HEADER_MOBILE_SHOW !== "ALWAYS"
            ? -43
            : -62 - 43;

        if ($(block).hasClass("drag-block")) {
          offset += 43;
        }
      }
    }

    offset = $(block).offset().top + offset;

    $("body").addClass("scrolling-state");
    $("body, html").animate({ scrollTop: offset }, 500);
    setTimeout(function () {
      $("body").removeClass("scrolling-state");
    }, 500);
  }
};

/* print */
$(document).on("click", ".print-link", function () {
  window.print();
});

BX.ready(() => {
  // js-load-block appear
  if ($(".js-load-block").length) {
    var objUrl = parseUrlQuery();
    var bClearCache = false;
    if ("clear_cache" in objUrl) {
      if (objUrl.clear_cache == "Y") {
        bClearCache = true;
      }
    }

    var items = [];
    var bIdle = true;
    var insertNextBlockContent = function () {
      if (bIdle) {
        if (items.length) {
          bIdle = false;
          var item = items.pop();

          item.content = $.trim(item.content);

          // remove /bitrix/js/main/core/core_window.js if it was loaded already
          if (item.content.indexOf("/bitrix/js/main/core/core_window.") !== -1 && BX.WindowManager) {
            item.content = item.content.replace(
              /<script src="\/bitrix\/js\/main\/core\/core_window\.[^>]*><\/script>/gm,
              ""
            );
          }

          // remove /bitrix/js/currency/core_currency.js if it was loaded already
          if (
            item.content.indexOf("/bitrix/js/currency/core_currency.") !== -1 &&
            typeof BX.Currency === "object" &&
            BX.Currency.defaultFormat
          ) {
            item.content = item.content.replace(
              /<script src="\/bitrix\/js\/currency\/core_currency\.[^>]*><\/script>/gm,
              ""
            );
          }

          // remove /bitrix/js/main/pageobject/pageobject.js if it was loaded already
          if (item.content.indexOf("/bitrix/js/main/pageobject/pageobject.") !== -1 && BX.PageObject) {
            item.content = item.content.replace(
              /<script src="\/bitrix\/js\/main\/pageobject\/pageobject\.[^>]*><\/script>/gm,
              ""
            );
          }

          // remove /bitrix/js/main/polyfill/promise/js/promise.js if it not need
          if (
            item.content.indexOf("/bitrix/js/main/polyfill/promise/js/promise.") !== -1 &&
            typeof window.Promise !== "undefined" &&
            window.Promise.toString().indexOf("[native code]") !== -1
          ) {
            item.content = item.content.replace(
              /<script src="\/bitrix\/js\/main\/polyfill\/promise\/js\/promise\.[^>]*><\/script>/gm,
              ""
            );
          }

          var ob = BX.processHTML(item.content);

          // stop ya metrika webvisor DOM indexer
          // pauseYmObserver();

          item.block.removeAttr("data-file").removeClass("loader_circle");

          if (item.block.data("appendTo")) {
            item.block.find(item.block.data("appendTo"))[0].innerHTML = ob.HTML;
          } else {
            if (item.block.find('> div[id*="bx_incl_"]').length) {
              item.block.find('> div[id*="bx_incl_"]')[0].innerHTML = ob.HTML;
            } else {
              item.block[0].innerHTML = ob.HTML;
            }
          }

          BX.ajax.processScripts(ob.SCRIPT);

          var eventdata = { action: "jsLoadBlock" };
          BX.onCustomEvent("onCompleteAction", [eventdata, item.block]);

          // resume ya metrika webvisor
          // 500ms
          // setTimeout(resumeYmObserver, 500);

          bIdle = true;
          insertNextBlockContent();
        }
      }
    };

    $(".js-load-block").iAppear(
      function () {
        var $this = $(this);

        if ($this.data("file")) {
          var add_url = bClearCache ? "?clear_cache=Y" : "";
          if ($this.data("block")) {
            add_url += (bClearCache ? "&" : "?") + "BLOCK=" + $this.data("block");
          }

          // get content
          $.get($this.data("file") + add_url).done(function (html) {
            items.push({
              block: $this,
              content: html,
            });

            if (items.length == 1) {
              setTimeout(insertNextBlockContent, 100);
            }
          });
        }
      },
      {
        rootMargin: "300px 0px 300px 0px",
        accX: 0,
        accY: 300,
      }
    );
  }

  $(".appear-block").iAppear(function () {
    $(this).removeClass("appear-block");
    initSwiperSlider();
  });

  // adaptive table
  $("table.table").each(function () {
    var _this = $(this),
      first_td = _this.find("thead tr th");
    if (!first_td.length) first_td = _this.find("thead tr td");
    if (first_td.length) {
      _this.find("tbody tr:not(.nomobile)").each(function (i) {
        $(this)
          .find("td")
          .each(function (i) {
            if (typeof first_td[i] !== "undefined")
              $('<div class="th-mobile">' + first_td[i].textContent + "</div>").appendTo($(this));
          });
      });
    }
  });
});
