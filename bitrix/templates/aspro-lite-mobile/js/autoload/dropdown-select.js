// dropdown-select
$(document).on("click", ".dropdown-select--with-dropdown .dropdown-select__title", function () {
  var _this = $(this),
    menu = _this.parent().find("> .dropdown-select__list");
  if (!_this.hasClass("clicked") && menu.length) {
    _this.addClass("clicked");

    _this.toggleClass("opened");
    menu.stop().slideToggle(100, function () {
      _this.removeClass("clicked");
    });
  }
});

// dropdown-select change item
$(document).on("click", ".dropdown-select__list .mixitup-item", function () {
  var $select = $(this).closest(".dropdown-select");
  $select.find(".dropdown-select__title span").text($(this).text());
  $select.find(".dropdown-select__title.opened").click();
});

// close select
$("html, body").on("mousedown", function (e) {
  if (typeof e.target.className == "string" && e.target.className.indexOf("adm") < 0) {
    e.stopPropagation();
    $(".dropdown-select .dropdown-select__title.opened").each(function () {
      if ($(this).closest("#mobilefilter").length) return;
      var $select = $(this).closest(".dropdown-select");
      if ($select.data("visible_by_class") && $($select.data("visible_by_class")).is(":visible")) return;
      if (!$(e.target).closest($select).length) {
        $(this).click();
      }
    });
  }
});
