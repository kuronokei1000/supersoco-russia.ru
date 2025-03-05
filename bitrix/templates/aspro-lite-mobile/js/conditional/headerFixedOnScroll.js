BX.ready(() => {
  let mheaderFixed = false,
    mheaderStartScroll = 0;
  const mfixed = $(".header-container"),
    mheaderFixedHeight = mfixed.height(),
    mheaderTop = $("#panel:visible").height() || 0;

  $(window).scroll(function () {
    let scrollTop = $(window).scrollTop();
    if (!mheaderFixed) {
      if (scrollTop > mheaderTop + mheaderFixedHeight && scrollTop < mheaderStartScroll) {
        mfixed.addClass("fixed");
        mheaderFixed = true;
      }
    } else {
      if (scrollTop <= mheaderTop || scrollTop > mheaderStartScroll) {
        mfixed.removeClass("fixed");
        mheaderFixed = false;
      }
    }

    mheaderStartScroll = scrollTop;
  });
});
