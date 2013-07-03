<?php get_template_part( "header" );?>

<div class="posts">
	<?php
	while(have_posts()){ the_post();
	?>
	<div class="post">
		<div class="title"><?php the_title();?></div>
		<div class="content"><?php the_excerpt();?></div>
	</div>
	<?php } ?>
</div>

<script type="text/javascript">
post_offset = increment = <?php echo get_option( 'posts_per_page' );?>;
</script>

<?php get_template_part( "footer" );?>
