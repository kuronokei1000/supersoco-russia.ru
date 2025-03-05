appAspro.mapContact = {};
$(document).on("click", ".map-mobile-opener", function () {
  if ($(this).hasClass("closer")) {
    appAspro.mapContact.closeFullscreenMap();
  } else {
    appAspro.mapContact.openFullsreenMap(this);
  }
});

appAspro.mapContact.openFullsreenMap = element => {
  var $this = $(element);
  if ($this.hasClass("closer")) return;

  var currentMap = $this.parents(".bx-map-view-layout");
  var mapId = currentMap.find(".bx-yandex-map").attr("id");
  window.openedYandexMapFrame = mapId;
  var mapContainer = $('<div data-mapId="' + mapId + '"></div>');

  if (!$("div[data-mapId=" + mapId + "]").length) {
    currentMap.after(mapContainer);
  }

  var addClass = "";
  if ($this.parents(".contacts-page-map-top").length) {
    addClass += " contacts-map-top-frame ";
  }

  var yandexMapFrame = $('<div class="yandex-map__frame filter-none' + addClass + '"></div>');
  $(".layout").append(yandexMapFrame);
  currentMap.appendTo(yandexMapFrame);
  currentMap.find(".map-mobile-opener").addClass("closer");

  if (typeof map === "object" && map !== null && "container" in map) {
    window.map.container.fitToViewport();
  }
};

appAspro.mapContact.closeFullscreenMap = () => {
  var yandexMapFrame = $(".yandex-map__frame");
  if (yandexMapFrame.length) {
    var currentMap = yandexMapFrame.find(".bx-map-view-layout");
    var yandexMapContainer = $("div[data-mapId=" + window.openedYandexMapFrame + "]");

    if (document.querySelector(".ymaps-b-balloon__close")) {
      document.querySelector(".ymaps-b-balloon__close").click();
    }

    currentMap.appendTo(yandexMapContainer);
    yandexMapFrame.remove();
    currentMap.find(".map-mobile-opener").removeClass("closer");

    if (typeof map === "object" && map !== null && "container" in map) {
      window.map.container.fitToViewport();
    }
    
  }
};
