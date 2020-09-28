<?php
/**
 * Related posts based on categories and tags.
 * 
 */

$digital_agency_lite_related_posts_taxonomy = get_theme_mod( 'digital_agency_lite_related_posts_taxonomy', 'category' );

$digital_agency_lite_post_args = array(
    'posts_per_page'    => absint( get_theme_mod( 'digital_agency_lite_related_posts_count', '3' ) ),
    'orderby'           => 'rand',
    'post__not_in'      => array( get_the_ID() ),
);

$digital_agency_lite_tax_terms = wp_get_post_terms( get_the_ID(), 'category' );
$digital_agency_lite_terms_ids = array();
foreach( $digital_agency_lite_tax_terms as $tax_term ) {
	$digital_agency_lite_terms_ids[] = $tax_term->term_id;
}

$digital_agency_lite_post_args['category__in'] = $digital_agency_lite_terms_ids; 

if(get_theme_mod('digital_agency_lite_related_post',true)==1){

$digital_agency_lite_related_posts = new WP_Query( $digital_agency_lite_post_args );

if ( $digital_agency_lite_related_posts->have_posts() ) : ?>
    <div class="related-post">
        <h3><?php echo esc_html(get_theme_mod('digital_agency_lite_related_post_title','Related Post'));?></h3>
        <div class="row">
            <?php while ( $digital_agency_lite_related_posts->have_posts() ) : $digital_agency_lite_related_posts->the_post(); ?>
                <?php get_template_part('template-parts/grid-layout'); ?>
            <?php endwhile; ?>
        </div>
    </div>
<?php endif;
wp_reset_postdata();

}