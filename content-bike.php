<?php
/**
 * @package Sydney
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() && ( get_theme_mod( 'index_feat_image' ) != 1 ) ) : ?>
		<div class="entry-thumb">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('sydney-large-thumb'); ?></a>
		</div>
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="title-post entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' == get_post_type() && get_theme_mod('hide_meta_index') != 1 ) : ?>
		<div class="meta-post">
			<?php sydney_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-post">
		
    <?php the_content(); ?>
    <h3>Gallery </h3>
    <h4><em>click on a photo to enlarge</em></h4>
    <?php 
        $images = get_post_meta($post->ID, 'vdw_gallery_id', true);
        $div_begin = '<div class="gallery_product col-lg-4 col-md-6 col-sm-6 col-xs-12">';
        $div_end= '</div>';
        $link_begin = "<a href='%URL%' class='grouped_elements' rel='group1'>";
        $link_end ="</a>";
        foreach ($images as $image) {
            echo $div_begin;
            $url = wp_get_attachment_url($image);
            echo str_replace('%URL%', $url, $link_begin );
            echo wp_get_attachment_image($image, 'large',false, array( "class" => "img-responsive" ));
            echo $link_end;
            //echo wp_get_attachment_link($image, 'medium',false,false,'',array( "class" => "img-responsive, grouped_elements", "rel" =>"group1" ));
            echo $div_end;
                // echo wp_get_attachment_image($image, 'large');
        }
    ?>


		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'sydney' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-post -->

	<footer class="entry-footer">
		<?php sydney_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

<script>
    jQuery(document).ready(function() {
            jQuery("a.grouped_elements").fancybox();

    });

</script>
