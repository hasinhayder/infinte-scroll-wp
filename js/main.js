var post_offset, increment,loading=0;
(function($){
	$(document).ready(function(){
		$(window).bind('scroll',checkScroll);
	});

	var checkScroll = function (e){
	var elem = $(e.currentTarget);
	if($(window).scrollTop() + $(window).height() +20 > $(document).height()) {
		if(loading) return true;
		if(!loading) {
			loading=1;
			var params = {"offset":post_offset,"action":"load_more"}
			$.post("/wp-admin/admin-ajax.php",params,function(data){
				if(data){
					post_offset+=increment;
					loading=0;
					$(".posts").append(data);
					
				}

			});
	       	//now load more content
	       }
	   }
	}
	}(jQuery));