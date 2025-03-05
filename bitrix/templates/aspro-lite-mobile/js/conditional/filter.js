appAspro.filter = {};

$(document).on("click", ".bx-filter-title", function () {
  appAspro.filter.open();
});

$(document).on("click", "#mobilefilter .svg-close.close-icons", function () {
  appAspro.filter.close();
});

$(document).on("click", ".bx_filter_select_block", function (e) {
  var bx_filter_select_container = $(e.target).parents(".bx_filter_select_container");
  if (bx_filter_select_container.length) {
    var prop_id = bx_filter_select_container.closest(".bx_filter_parameters_box").attr("data-property_id");
    if ($("#smartFilterDropDown" + prop_id).length) {
      $("#smartFilterDropDown" + prop_id).css({
        "max-width": bx_filter_select_container.width(),
        "z-index": "3020",
      });
    }
  }
});

$(document).on("click", ".bx_filter_search_button", function (e) {
  // if ($(e.target).hasClass("bx_filter_search_button")) {
  appAspro.filter.close();
  // }
});

$(document).on("click", ".bx_filter_parameters_box_title", function (e) {
  $("[id^='smartFilterDropDown']").hide();
  if ($(e.target).hasClass("close-icons")) {
    appAspro.filter.close();
  }
});

mobileFilterNum = function (num, def) {
  if (def) {
    $(".bx_filter_search_button").text(num);
  } else {
    var str = "";
    var $prosLeng = $(".bx_filter_parameters_box > span");

    str += $prosLeng.data("f") + " " + +num;
    $(".bx_filter_search_button").text(str);
  }
};

declOfNumFilter = function (number, titles) {
  cases = [2, 0, 1, 1, 1, 2];
  return titles[number % 100 > 4 && number % 100 < 20 ? 2 : cases[number % 10 < 5 ? number % 10 : 5]];
};

appAspro.filter.open = function ($mobilefilter = $("#mobilefilter")) {
  appAspro.addOverlay();
  $("body").addClass("overlay-header");

  // fix body
  $("body").css({ overflow: "hidden", height: "100vh" });
  $mobilefilter.addClass("show");
};

appAspro.filter.close = function ($mobilefilter = $("#mobilefilter")) {
  $("body").removeClass("overlay-header");
  $("body").css({ overflow: "", height: "" });

  appAspro.removeOverlay();

  // hide mobile filter
  $mobilefilter.removeClass("show");
};

BX.addCustomEvent("onCloseOverlay", () => {
  appAspro.filter.close();
});
BX.addCustomEvent("onAddOverlay", () => {});
