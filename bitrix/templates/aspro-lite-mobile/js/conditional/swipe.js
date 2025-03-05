BX.ready(() => {
  const target = document.querySelector("body");
  const gesture = new TinyGesture(target);

  gesture.on("swiperight", event => {
    // header menu
    if (
      [
        ".mobile-scrolled",
        ".swiper",
        ".swipeignore",
        ".section-gallery-wrapper",
        ".header-search",
        ".notice",
        ".mobileheader",
      ].every(className => !event.target.closest(className))
    ) {
      appAspro.closeOverlay(() => {
        appAspro.headerMenu && appAspro.headerMenu.open();
      });
    }

    // gallery in section list
    if (event.target.closest(".section-gallery-wrapper")) {
      const $imageWrapper = event.target.closest(".image-list-wrapper");
      if ($imageWrapper.querySelector(".section-gallery-nav__wrapper")) {
        const $lastNav = $imageWrapper.querySelector(".section-gallery-nav__wrapper").lastElementChild;
        const $previous = $imageWrapper.querySelector(".section-gallery-nav__item.active").previousElementSibling;

        if ($previous) {
          $previous.click();
        } else {
          $lastNav.click();
        }
      }
    }
  });
  gesture.on("swipeleft", event => {
    // header menu
    if ([".swipeignore"].every(className => !event.target.closest(className))) {
      appAspro.closeOverlay();
    }

    // gallery in section list
    if (event.target.closest(".section-gallery-wrapper")) {
      const $imageWrapper = event.target.closest(".image-list-wrapper");
      if ($imageWrapper.querySelector(".section-gallery-nav__wrapper")) {
        const $firstNav = $imageWrapper.querySelector(".section-gallery-nav__wrapper").firstElementChild;
        const $nextActiveNav = $imageWrapper.querySelector(".section-gallery-nav__item.active").nextElementSibling;

        if ($nextActiveNav) {
          $nextActiveNav.click();
        } else {
          $firstNav.click();
        }
      }
    }
  });
  gesture.on("swipeup", event => {
    // The gesture was a up swipe.
  });
});
