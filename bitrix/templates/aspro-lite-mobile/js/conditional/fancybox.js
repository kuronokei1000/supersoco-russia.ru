$(document).on("click", ".fancy, .fancybox", function (e) {
  e.preventDefault();
  appAspro.loadCSS([
    `${arAsproOptions["SITE_TEMPLATE_PATH"]}/css/jquery.fancybox.min.css`,
    `${arAsproOptions["SITE_TEMPLATE_PATH"]}/css/fancybox-gallery.min.css`,
  ]);
  appAspro.loadScript(`${arAsproOptions["SITE_TEMPLATE_PATH"]}/js/jquery.fancybox.min.js`, () => {
    $(".fancy, .fancybox").fancybox({
      padding: [40, 40, 64, 40],
      openEffect: "fade",
      closeEffect: "fade",
      nextEffect: "fade",
      prevEffect: "fade",
      opacity: true,
      tpl: {
        closeBtn:
          '<span title="' +
          BX.message("FANCY_CLOSE") +
          '" class="fancybox-item fancybox-close inline svg"><svg class="svg svg-close" width="14" height="14" viewBox="0 0 14 14"><path data-name="Rounded Rectangle 568 copy 16" d="M1009.4,953l5.32,5.315a0.987,0.987,0,0,1,0,1.4,1,1,0,0,1-1.41,0L1008,954.4l-5.32,5.315a0.991,0.991,0,0,1-1.4-1.4L1006.6,953l-5.32-5.315a0.991,0.991,0,0,1,1.4-1.4l5.32,5.315,5.31-5.315a1,1,0,0,1,1.41,0,0.987,0.987,0,0,1,0,1.4Z" transform="translate(-1001 -946)"></path></svg></span>',
        next:
          '<a title="' +
          BX.message("FANCY_NEXT") +
          '" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
        prev:
          '<a title="' +
          BX.message("FANCY_PREV") +
          '" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>',
      },
      touch: "enabled",
      // buttons: ["close"],
      backFocus: false,
      beforeShow: function (event) {
        var bCurrentSrc =
          typeof event.current !== "undefined" && event.current.contentType == "html" && $(event.current.src).length;
        var video_block = [];

        if (bCurrentSrc) {
          video_block = $(event.current.src).find("source.video-content");
        }
        if (video_block.length) {
          if (video_block.attr("src") == "#") {
            var video_block_wrapper = video_block.closest("video");
            var video_block_clone = video_block_wrapper.clone();

            video_block_clone.find("source").attr("src", video_block_clone.find("source").data("src"));
            video_block.attr("src", video_block.data("src"));
            video_block_clone.insertAfter(video_block_wrapper);
            video_block_clone.siblings("video").remove();
          }
        }
        var video_block_frame = [];
        if (bCurrentSrc) {
          video_block_frame = $(event.current.src).find(".company-item__video-iframe");
        }
        if (video_block_frame.length) {
          var data_src_iframe = video_block_frame.attr("data-src");
          video_block_frame.attr("src", data_src_iframe);
          video_block_frame.attr("allow", "autoplay");
        }
      },
      afterShow: function (event) {
        var bCurrentSrc =
          typeof event.current !== "undefined" && event.current.contentType == "html" && $(event.current.src).length;
        var companyVideo = [];
        if (bCurrentSrc) {
          companyVideo = event.current.src[0].getElementsByClassName("company-item__video");
        }

        if (companyVideo.length) {
          setTimeout(function () {
            $(".fancybox-wrap video").resize();
            setTimeout(function () {
              $(".fancybox-wrap").addClass("show_video");
              if (companyVideo[0].currentTime === 0 || companyVideo[0].paused) {
                companyVideo[0].currentTime = 0;
                companyVideo[0].play();
              }
            }, 300);
          }, 150);
        } else if ($(".fancybox-wrap iframe").length) {
          $(".fancybox-inner").height("100%");
        }
      },
      beforeClose: function (event) {
        $(".fancybox-overlay").fadeOut();
        var bCurrentSrc =
          typeof event.current !== "undefined" && event.current.contentType == "html" && $(event.current.src).length;
        var companyVideo = [];
        if (bCurrentSrc) {
          companyVideo = event.current.src[0].getElementsByClassName("company-item__video");
        }

        if (companyVideo.length) {
          companyVideo[0].currentTime = 0;
        }

        $("html").removeClass("overflow_html");
        var video_block_frame = [];
        if (bCurrentSrc) {
          video_block_frame = $(event.current.src).find(".company-item__video-iframe");
        }

        if (video_block_frame.length) {
          video_block_frame.attr("src", "");
        }
      },
      onClosed: function (event) {
        var companyVideo = [];
        if (bCurrentSrc) {
          companyVideo = event.current.src[0].getElementsByClassName("company-item__video");
        }

        if (companyVideo.length) {
          companyVideo[0].pause();
        }
      },
    });

    if (!$(this).hasClass("initied")) {
      $(this).addClass("initied");
      $(this).click();
    }
  });
});
