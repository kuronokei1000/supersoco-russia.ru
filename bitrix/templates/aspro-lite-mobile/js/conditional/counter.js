$(document).on("click", ".counter__action--plus", function () {
  //for corporate basket
  if ($(this).closest(".counter--basket").length) return;
  
  let $input = $(this).closest(".counter").find(".counter__count");
  let currentValue = $input.val();

  let $buyBlock = $(this).closest(".buy_block");
  let $toCart = $buyBlock.find(".item-action .to_cart");

  let isFloatRatio = $toCart.data("float_ratio");
  let ratio = $toCart.data("ratio");
  ratio = isFloatRatio ? parseFloat(ratio) : parseInt(ratio, 10);
  if (isFloatRatio) {
    ratio = Math.round(ratio * arAsproOptions.JS_ITEM_CLICK.precisionFactor) / arAsproOptions.JS_ITEM_CLICK.precisionFactor;
  }

  let maxValue = parseFloat($toCart.data("max") ? $toCart.data("max") : ($(this).data("max") ? $(this).data("max") : 0));

  let newValue = isFloatRatio ? parseFloat(currentValue) : parseInt(currentValue, 10);
  newValue += ratio;
  if (isFloatRatio) {
    newValue = Math.round(newValue * arAsproOptions.JS_ITEM_CLICK.precisionFactor) / arAsproOptions.JS_ITEM_CLICK.precisionFactor;
  }
  
  if (
    parseFloat(maxValue) > 0 &&
    newValue > maxValue
  ) {
    newValue = maxValue;
  }

  $input.val(newValue);
  $input.change();
});

$(document).on("click", ".counter__action--minus", function () {
  //for corporate basket
  if ($(this).closest(".counter--basket").length) return;

  let $input = $(this).closest(".counter").find(".counter__count");
  let currentValue = $input.val();

  let $buyBlock = $(this).closest(".buy_block");
  let $toCart = $buyBlock.find(".item-action .to_cart");

  let isFloatRatio = $toCart.data("float_ratio");
  let ratio = $toCart.data("ratio");
  ratio = isFloatRatio ? parseFloat(ratio) : parseInt(ratio, 10);
  if (isFloatRatio) {
    ratio = Math.round(ratio * arAsproOptions.JS_ITEM_CLICK.precisionFactor) / arAsproOptions.JS_ITEM_CLICK.precisionFactor;
  }
  if (ratio <= 0) {
    ratio = 1;
  }

  let minValue = parseFloat($toCart.data("min") ? $toCart.data("min") : ($(this).data("min") ? $(this).data("min") : 0));
  if (minValue < ratio) {
    minValue = ratio;
  }

  let newValue = isFloatRatio ? parseFloat(currentValue) : parseInt(currentValue, 10);
  newValue -= ratio;
  if (isFloatRatio) {
    newValue = Math.round(newValue * arAsproOptions.JS_ITEM_CLICK.precisionFactor) / arAsproOptions.JS_ITEM_CLICK.precisionFactor;
  }

  if (
    parseFloat(minValue) > 0 &&
    newValue < minValue
  ) {
    // remove from basket
    newValue = 0;
  }

  $input.val(newValue);
  $input.change();
});

$(document).on("focus", ".counter__count", function (e) {
  $(this).addClass("focus");
});

$(document).on("blur", ".counter__count", function (e) {
  $(this).removeClass("focus");
});

var timerChangeCounterValue = false;
$(document).on("change", ".counter__count", function (e) {
  //for corporate basket
  if ($(this).closest(".counter--basket").length) return;

  let $buyBlock = $(this).closest(".buy_block");
  let $toCart = $buyBlock.find(".item-action .to_cart");
  let itemAction = JItemAction.factory($toCart[0]);

  let currentValue = $(this).val();
  // remove from cart
  if (parseFloat(currentValue) === 0) {
    itemAction.state = false;
    itemAction.resetQuantity();

    BX.onCustomEvent('onCounterGoals', [{
      goal: itemAction.getStateGoalCode(false),
      params: {
        id: itemAction.node.getAttribute('data-id'),
      }
    }]);

    // remove notice
    if (typeof JNoticeSurface === 'function') {
      JNoticeSurface.get().node.remove()
    }

    return itemAction.updateState();
  }

  let isFloatRatio = $toCart.data("float_ratio");
  let ratio = $toCart.data("ratio");
  ratio = isFloatRatio ? parseFloat(ratio) : parseInt(ratio, 10);

  let diff = currentValue % ratio;

  if (isFloatRatio) {
    ratio = Math.round(ratio * arAsproOptions.JS_ITEM_CLICK.precisionFactor) / arAsproOptions.JS_ITEM_CLICK.precisionFactor;

    if (Math.round(diff * arAsproOptions.JS_ITEM_CLICK.precisionFactor) / arAsproOptions.JS_ITEM_CLICK.precisionFactor == ratio) {
      diff = 0;
    }
  }

  if ($(this).hasClass("focus")) {
    intCount = Math.round(Math.round((currentValue * arAsproOptions.JS_ITEM_CLICK.precisionFactor) / ratio) / arAsproOptions.JS_ITEM_CLICK.precisionFactor) || 1;
    if (parseFloat(currentValue) !== 0) {
      currentValue = intCount <= 1 ? ratio : intCount * ratio;
    }
    currentValue = Math.round(currentValue * arAsproOptions.JS_ITEM_CLICK.precisionFactor) / arAsproOptions.JS_ITEM_CLICK.precisionFactor;
  }

  let maxValue = $toCart.data("max") ? $toCart.data("max") : $(this).closest(".counter").find(".counter__action--plus").data("max");
  if (maxValue && parseFloat(maxValue) > 0) {
    if (currentValue > parseFloat(maxValue)) {
      currentValue = parseFloat(maxValue);
    }
  }

  let minValue = (typeof $toCart.data("min") !== 'undefined') ? $toCart.data("min") : $(this).closest(".counter").find(".counter__action--minus").data("min");
  if (minValue && parseFloat(minValue) > 0) {
    if (currentValue < parseFloat(minValue)) {
      currentValue = parseFloat(minValue);
    }
  }

  if (timerChangeCounterValue) {
    clearTimeout(timerChangeCounterValue);
  }

  if (currentValue < ratio) {
    currentValue = ratio;
  } else if (!parseFloat(currentValue)) {
    currentValue = 1;
  }

  $toCart.attr("data-quantity", currentValue);

  $(this).val(currentValue);

  itemAction.abortPrevRequest();
  timerChangeCounterValue = setTimeout(function(){
    itemAction.updateState();
    timerChangeCounterValue = false;
  }, 700);

  BX.onCustomEvent(
    "onCounterProductAction", 
    [
      {
        type: "change",
        params: {
          id: $(this),
          value: currentValue,
        }
      }
    ]
  );

  if (
    $(this).closest(".complect-block").length &&
    typeof setNewPriceComplect === "function"
  ) {
    setNewPriceComplect();
  }
});
