$(document).ready(function() {
	
	// load items on button click
	$('.pjs-mm-archive .wrapper > .btns a').click(function() {
		loadMore();
	});
	
	// load initial items
	loadMore();
	
	// loadMore function
	function loadMore() {
		var lmBtn = $('.pjs-mm-archive .wrapper > .btns');
		var loader = $('.pjs-mm-archive .wrapper .loader');
		var container = $('.pjs-mm-archive .wrapper .cards');
		var postType = container.attr('post-type');
		var page = container.attr('page');
		var total = container.attr('total');
		
		lmBtn.slideUp(300);
		loader.slideDown(300);
		
		$.ajax({
			type: 'POST',
			url: '/wp-content/plugins/pjs-media-manager/includes/ajax/load-more.php',
			data: {post_type:postType, paged:page}
		}).done(function(results) {
			
			results = $.parseJSON(results);
			
			container.append(results.cards);
			container.attr('page', results.page);
			loader.slideUp(300);
			
			$('.pjs-mm-archive .wrapper .cards .card').each(function() {
				$(this).removeClass('hidden');
			});
			
			var totalShowing = $('.pjs-mm-archive .wrapper .cards .card').length;
			
			if (total > totalShowing) {
				lmBtn.slideDown(300);
			}
			
		});
	}
	
});