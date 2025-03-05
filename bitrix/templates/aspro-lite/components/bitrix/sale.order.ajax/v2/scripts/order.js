readyDOM(function () {
  const $order_wrapper = document.getElementById("bx-soa-order-main");
  if ($order_wrapper) {
    $order_wrapper.addEventListener("click", function (e) {
      //change block
      if (e.target.classList.contains("change-info")) {
        let $parent = e.target.closest(".bx-soa-section");
        $parent.classList.remove("bx-step-completed");
        $parent.classList.remove("bx-selected");

        BX.Sale.OrderAjaxComponent.opened[$parent.id] = true;
      }

      // change city
      if (e.target.classList.contains("change_city")) {
        $order_wrapper.querySelector(".bx-soa-section-location").classList.toggle("opened");
        $($order_wrapper.querySelector(".bx-soa-section-location")).slideToggle();
      }
    });
  }
});
