jQuery(document).ready( function($){
	$('#form-search').submit(function(e){
		e.preventDefault();
		$elem= $(this);
		$search= $(':input[name="search"]', $elem);
		window.location.href= site.urls.search + '/' + encodeURIComponent($search.val());
	})
})