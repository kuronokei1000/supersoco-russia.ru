var onCaptchaVerifyinvisible = function (response) {
  $(".g-recaptcha:last").each(function () {
    var id = $(this).attr("data-widgetid");
    if (typeof id !== "undefined" && response) {
      if (!$(this).closest("form").find(".g-recaptcha-response").val())
        $(this).closest("form").find(".g-recaptcha-response").val(response);
      if ($("iframe[src*=recaptcha]").length) {
        $("iframe[src*=recaptcha]").each(function () {
          var block = $(this).parent().parent();
          if (!block.hasClass("grecaptcha-badge")) block.css("width", "100%");
        });
      }
      if ($(this).closest("form").attr("name") == "form_comment") BX.submit(BX("form_comment"));
      else $(this).closest("form").submit();
    }
  });
};

var onCaptchaVerifynormal = function (response) {
  $(".g-recaptcha").each(function () {
    var id = $(this).attr("data-widgetid");
    if (typeof id !== "undefined") {
      if (grecaptcha.getResponse(id) != "") {
        $(this).closest("form").find(".recaptcha").valid();
      }
    }
  });
};

BX.addCustomEvent("onSubmitForm", function (eventdata) {
  try {
    if (!window.renderRecaptchaById || !window.asproRecaptcha || !window.asproRecaptcha.key) {
      eventdata.form.submit();
      $(eventdata.form).closest(".form").addClass("sending");
      return true;
    }
    if (window.asproRecaptcha.params.recaptchaSize == "invisible" && typeof grecaptcha != "undefined") {
      if ($(eventdata.form).find(".g-recaptcha-response").val()) {
        eventdata.form.submit();
        $(eventdata.form).closest(".form").addClass("sending");
      } else {
        grecaptcha.execute($(eventdata.form).find(".g-recaptcha").data("widgetid"));
        return false;
      }
    } else {
      eventdata.form.submit();
      $(eventdata.form).closest(".form").addClass("sending");
    }

    return true;
  } catch (e) {
    console.error(e);
    return true;
  }
});

// reload captcha
$(document).on("click", ".refresh", function (e) {
  var captcha = $(this).parents(".captcha-row");
  e.preventDefault();
  $.ajax({
    url: arAsproOptions["SITE_DIR"] + "ajax/captcha.php",
  }).done(function (text) {
    captcha.find("input[name=captcha_sid],input[name=captcha_code]").val(text);
    captcha.find("img").attr("src", "/bitrix/tools/captcha.php?captcha_sid=" + text);
    captcha.find("input[name=captcha_word]").val("").removeClass("error");
    captcha.find(".captcha_input").removeClass("error").find(".error").remove();
  });
});