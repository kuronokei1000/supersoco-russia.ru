appAspro.headerPhones = {};

$(document).on("click", ".mobileheader .phones", function (e) {
  e.stopPropagation();

  $("#mobilephones")
    .stop()
    .slideToggle("fast", function () {
      if ($(this).is(":visible")) {
        appAspro.addOverlay();
      } else {
        appAspro.removeOverlay();
      }
    });
});

$(document).on("click", ".mobilephones__menu-item a,.mobilephones__close", function (e) {
  e.stopPropagation();

  appAspro.headerPhones.close();
});
appAspro.headerPhones.close = function (cb) {
  $("#mobilephones").slideUp("fast", function () {
    appAspro.removeOverlay();

    if (typeof cb === "function") {
      cb();
    }
  });
};

// hide only phones, remain overlay
appAspro.headerPhones.hide = function (cb) {
  appAspro.headerPhones.close();
};
