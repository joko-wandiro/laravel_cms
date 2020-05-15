function Sidebar(options) {
parameters = typeof (options.parameters) == "undefined" ? {} : options.parameters;
    instance = this;
    instance.selector = options.selector;
    instance.parameters = $.extend({}, instance.parameters, parameters);
    instance.init();
}
Sidebar.prototype= {
	dropdownMenu: function(){
        $(document).on('click', this.selector, {currSidebar: this}, function (e) {
            e.preventDefault();
            currSidebar = e.data.currSidebar;
            $elem = $(this);
            $list= $elem.parent();
            $dropdownMenu= $list.find('.s-m-dropdown-menu');
            if( $list.hasClass('open') ){
				// Remove open class to current selection
				$list.removeClass('open');
                // Display child menu
                $dropdownMenu.hide();
			}else{
				// Add open class to current selection
				$list.addClass('open');
                // Display child menu
                $dropdownMenu.show();
			}
        })
	},
	init: function(){
		this.dropdownMenu();
	},
}