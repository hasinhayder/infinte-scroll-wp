<?php
show_admin_bar( false );
add_filter("wp_enqueue_scripts","scripts_and_styles");
function scripts_and_styles(){
	wp_enqueue_script( "jquery" );
	wp_register_script( "app-js", get_template_directory_uri(). "/js/main.js", "jquery", "2.0" );
	wp_enqueue_script( "app-js" );

	wp_enqueue_style( "app-style", get_template_directory_uri()."/css/app.css" );
	wp_enqueue_style( "lato", '//fonts.googleapis.com/css?family=Lato:400', false, null);
	wp_enqueue_style('fontawesome', '//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css', false, null);

}

function load_more(){
	$offset = $_POST['offset'];
	$number_of_posts = get_option( "posts_per_page");
	$args = array("number_of_posts"=>$number_of_posts,"offset"=>$offset);
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