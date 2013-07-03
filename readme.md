## Infinite Scrolling in WordPress

The purpose of infinite scrolling is that you don't have to navigate through old and new posts by reloading the page each times with them. Rather, old posts will load automatically in the bottom as you scroll down. 

### Challenges
1. Event listener for scrolling
2. Making an Ajax call to load more posts
3. In the backend, keep track of offsets and deliver posts prior to it
4. Discard repeated calls with same parameters to avoid redundant data lading


#### Event Listener
With jQuery it's pretty easy to create an event listener which listens to window or any dom element's scrolling. In the listener routine, you need to check whether they user has scrolled to the bottom, and that is done little differently for window object and DOM elements. 

**For Window Scrolling**

	$(document).ready(function(){
		$(window).bind('scroll',checkScroll);
	});

	var checkScroll = function (e){
	if($(window).scrollTop() + $(window).height() +20 > $(document).height()) {
		//user scrolled to the bottom
		//now load more contents
	}
	
**For DOM elements**

 	$(document).ready(function(){
		$(".selector").bind('scroll',checkScroll);
	});

	var checkScroll = function (e){
	var elem = $(e.currentTarget);
	if (elem[0].scrollHeight - elem.scrollTop() -elem.outerHeight()<20)
		//user scrolled to the bottom of this DOM element
		//now load more contents
	}
	
#### Making an Ajax Call to load the contents
This is divided in two parts. One, in the WordPress functions file which creates an actio hook and listens for incoming Ajax requests and then serve the data and second, from the javascript to make a HTTP POST Ajax call. 

**In WordPress**
	
	<?php
	function load_more(){
		$offset = $_POST['offset'];
		$number_of_posts = get_option( "posts_per_page");
		$args = array("posts_per_page"=>$number_of_posts,"offset"=>$offset);
		$posts = get_posts($args);
		foreach($posts as $post){
			setup_postdata( $post );
			global $post;
			?>
			<div class="post">
				<div class="title"><?php the_title();?></div>
				<div class="content"><?php the_excerpt();?></div>
			</div>
			<?
		}
		die();
	}

	add_action("wp_ajax_nopriv_load_more","load_more");
	add_action("wp_ajax_load_more","load_more");
	?>
	
WordPress ```get_posts()``` takes many parameters, but for our task we need to pass only **two** and leave other's with default values. In those two variables, **offset** is an important one. It is not an id of any post, rather it's the value from where we want to start loading the previous posts. So for example, if there are 10 posts displayed in our homepage, the **offset** will be 10, because we want to load previous posts before these **tenth** post. 

One more important thing to note here, and that is how we populated the value of **posts_per_page**. We used get_option() API to retrieve the actual value of posts per page set by the blog admin from Settings->Reading section of the WordPress admin panel. 

The reason for setting th same action hook callbak for both **wp_ajax_no_priv** and **wp_ajax** is to make sure that it works for both admin (logged in) and general viewers (non logged in)

**In javascript**
	
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
	}


