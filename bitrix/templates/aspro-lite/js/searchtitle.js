$(document).on('click', '.dropdown-select.searchtype .dropdown-menu-item:not(.dropdown-menu-item--current)', function(){
    let $this = $(this);
    let $title = $this.closest('.dropdown-select').find('.dropdown-select__title');
    let $menu = $this.closest('.dropdown-select').find('.dropdown-select__list');
    let bVisibleMeu = $menu.is(':visible');
    let animate = !bVisibleMeu ? 'transition.slideUpIn' : 'fadeOut';
    let type = $this.data('type');
    let name = type === 'all' ? BX.message('SEARCH_IN_SITE') : BX.message('SEARCH_IN_CATALOG');

    if (!$title.hasClass('clicked')) {
        $title.addClass('clicked');

        $title.toggleClass('opened');
        $menu.stop().slideUp(300, function() {
            $title.removeClass("clicked");
        });
    }

    $.cookie('searchtitle_type', type);

    $this.closest('.dropdown-select').find('input[name=type]').val(type);

    // remove already visible results
    $('.title-search-result').hide().empty();

    // fire new search request
    BX.fireEvent($this.closest('.dropdown-select').find('input[name=type]')[0], 'change');    

    $('.dropdown-select.searchtype').each(function(){
        $(this).closest('form').attr('action', type === 'all' ? arAsproOptions.PAGES.SEARCH_PAGE_URL : arAsproOptions.PAGES.CATALOG_PAGE_URL);
        $(this).find('.dropdown-select__title>span').text(name);
        $(this).find('input[name=type]').val(type);

        $(this).find('.dropdown-menu-item').removeClass('dropdown-menu-item--current');
        $(this).find('.dropdown-menu-item[data-type=' + type + ']').addClass('dropdown-menu-item--current');
    });

    try {
        $this.closest('form').find('input[name=q]')[0].focus();
    }
    catch (e) {
    }
});