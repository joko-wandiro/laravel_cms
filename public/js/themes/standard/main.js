//jQuery.ajaxSetup({
//    headers: {
//        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//    }
//});
jQuery(document).ready(function ($) {
    // Sidebar
    options = {
        'selector': '#sidebar .sidebar-menu li.s-m-dropdown > a',
    }
    mySidebar = new Sidebar(options);
    // Trigger active link
    $('.sidebar-menu li.active').parent().prev().trigger('click');
    // Scaffolding
//	DKScaffoldingOptions= {
//		modifyValidationOptions: function(validationOptions){
//			return validationOptions;
//		}
//	}
    Scaffolding = $('.dk-scaffolding').DKScaffolding();
    // Define initial configuration for sidebar
    function defineInitialSidebarConfiguration() {
        // Define initial configuration for sidebar
        screenWidth = $(window).width();
        if (screenWidth > 768) {
            $('#sidebar').removeAttr('class').removeAttr('style').removeAttr('aria-expanded');
        } else {
            $sidebarNavbarToogle = $('#navbar-header-sidebar .navbar-toggle');
            $('#sidebar').attr({'class': 'collapse', 'aria-expanded': 'false', 'style': 'height: 0px;'});
        }
    }
    defineInitialSidebarConfiguration();
    $(window).resize(function () {
        defineInitialSidebarConfiguration();
    });
})