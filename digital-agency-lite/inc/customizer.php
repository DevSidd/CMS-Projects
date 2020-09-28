<?php
/**
 * Digital Agency Lite Theme Customizer
 *
 * @package Digital Agency Lite
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

function digital_agency_lite_custom_controls() {
	load_template( trailingslashit( get_template_directory() ) . '/inc/custom-controls.php' );
}
add_action( 'customize_register', 'digital_agency_lite_custom_controls' );

function digital_agency_lite_customize_register( $wp_customize ) {

	load_template( trailingslashit( get_template_directory() ) . 'inc/customize-homepage/class-customize-homepage.php' );

	load_template( trailingslashit( get_template_directory() ) . '/inc/icon-picker.php' );

	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage'; 
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'blogname', array( 
		'selector' => '.logo .site-title a', 
	 	'render_callback' => 'digital_agency_lite_customize_partial_blogname', 
	)); 

	$wp_customize->selective_refresh->add_partial( 'blogdescription', array( 
		'selector' => 'p.site-description', 
		'render_callback' => 'digital_agency_lite_customize_partial_blogdescription', 
	));

	//add home page setting pannel
	$DigitalAgencyLiteParentPanel = new Digital_Agency_Lite_WP_Customize_Panel( $wp_customize, 'digital_agency_lite_panel_id', array(
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title' => 'VW Settings',
		'priority' => 10,
	));

	// Layout
	$wp_customize->add_section( 'digital_agency_lite_left_right', array(
    	'title'      => __( 'General Settings', 'digital-agency-lite' ),
		'panel' => 'digital_agency_lite_panel_id'
	) );

	$wp_customize->add_setting('digital_agency_lite_width_option',array(
        'default' => __('Full Width','digital-agency-lite'),
        'sanitize_callback' => 'digital_agency_lite_sanitize_choices'
	));
	$wp_customize->add_control(new Digital_Agency_Lite_Image_Radio_Control($wp_customize, 'digital_agency_lite_width_option', array(
        'type' => 'select',
        'label' => __('Width Layouts','digital-agency-lite'),
        'description' => __('Here you can change the width layout of Website.','digital-agency-lite'),
        'section' => 'digital_agency_lite_left_right',
        'choices' => array(
            'Full Width' => get_template_directory_uri().'/assets/images/full-width.png',
            'Wide Width' => get_template_directory_uri().'/assets/images/wide-width.png',
            'Boxed' => get_template_directory_uri().'/assets/images/boxed-width.png',
    ))));

	$wp_customize->add_setting('digital_agency_lite_theme_options',array(
        'default' => __('Right Sidebar','digital-agency-lite'),
        'sanitize_callback' => 'digital_agency_lite_sanitize_choices'
	));
	$wp_customize->add_control('digital_agency_lite_theme_options',array(
        'type' => 'select',
        'label' => __('Post Sidebar Layout','digital-agency-lite'),
        'description' => __('Here you can change the sidebar layout for posts. ','digital-agency-lite'),
        'section' => 'digital_agency_lite_left_right',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','digital-agency-lite'),
            'Right Sidebar' => __('Right Sidebar','digital-agency-lite'),
            'One Column' => __('One Column','digital-agency-lite'),
            'Three Columns' => __('Three Columns','digital-agency-lite'),
            'Four Columns' => __('Four Columns','digital-agency-lite'),
            'Grid Layout' => __('Grid Layout','digital-agency-lite')
        ),
	) );

	$wp_customize->add_setting('digital_agency_lite_page_layout',array(
        'default' => __('One Column','digital-agency-lite'),
        'sanitize_callback' => 'digital_agency_lite_sanitize_choices'
	));
	$wp_customize->add_control('digital_agency_lite_page_layout',array(
        'type' => 'select',
        'label' => __('Page Sidebar Layout','digital-agency-lite'),
        'description' => __('Here you can change the sidebar layout for pages. ','digital-agency-lite'),
        'section' => 'digital_agency_lite_left_right',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','digital-agency-lite'),
            'Right Sidebar' => __('Right Sidebar','digital-agency-lite'),
            'One Column' => __('One Column','digital-agency-lite')
        ),
	) );

	$wp_customize->add_setting( 'digital_agency_lite_header_search',array(
    	'default' => 1,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ));  
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_header_search',array(
      	'label' => esc_html__( 'Show / Hide Search','digital-agency-lite' ),
      	'section' => 'digital_agency_lite_left_right'
    )));

    $wp_customize->add_setting('digital_agency_lite_search_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_search_font_size',array(
		'label'	=> __('Search Font Size','digital-agency-lite'),
		'description'	=> __('Enter a value in pixels. Example:20px','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_left_right',
		'type'=> 'text'
	));

    //Sticky Header
	$wp_customize->add_setting( 'digital_agency_lite_sticky_header',array(
        'default' => 0,
        'transport' => 'refresh',
        'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ) );
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_sticky_header',array(
        'label' => esc_html__( 'Sticky Header','digital-agency-lite' ),
        'section' => 'digital_agency_lite_left_right'
    )));

    $wp_customize->add_setting('digital_agency_lite_sticky_header_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_sticky_header_padding',array(
		'label'	=> __('Sticky Header Padding','digital-agency-lite'),
		'description'	=> __('Enter a value in pixels. Example:20px','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_left_right',
		'type'=> 'text'
	));

    //Woocommerce Shop Page Sidebar
	$wp_customize->add_setting( 'digital_agency_lite_woocommerce_shop_page_sidebar',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ) );
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_woocommerce_shop_page_sidebar',array(
		'label' => esc_html__( 'Shop Page Sidebar','digital-agency-lite' ),
		'section' => 'digital_agency_lite_left_right'
    )));

    //Woocommerce Single Product page Sidebar
	$wp_customize->add_setting( 'digital_agency_lite_woocommerce_single_product_page_sidebar',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ) );
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_woocommerce_single_product_page_sidebar',array(
		'label' => esc_html__( 'Single Product Sidebar','digital-agency-lite' ),
		'section' => 'digital_agency_lite_left_right'
    )));

    //Pre-Loader
	$wp_customize->add_setting( 'digital_agency_lite_loader_enable',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ) );
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_loader_enable',array(
        'label' => esc_html__( 'Pre-Loader','digital-agency-lite' ),
        'section' => 'digital_agency_lite_left_right'
    )));

	$wp_customize->add_setting('digital_agency_lite_loader_icon',array(
        'default' => __('Two Way','digital-agency-lite'),
        'sanitize_callback' => 'digital_agency_lite_sanitize_choices'
	));
	$wp_customize->add_control('digital_agency_lite_loader_icon',array(
        'type' => 'select',
        'label' => __('Pre-Loader Type','digital-agency-lite'),
        'section' => 'digital_agency_lite_left_right',
        'choices' => array(
            'Two Way' => __('Two Way','digital-agency-lite'),
            'Dots' => __('Dots','digital-agency-lite'),
            'Rotate' => __('Rotate','digital-agency-lite')
        ),
	) );
    
	//Slider
	$wp_customize->add_section( 'digital_agency_lite_slidersettings' , array(
    	'title'      => __( 'Slider Settings', 'digital-agency-lite' ),
		'panel' => 'digital_agency_lite_panel_id'
	) );

	$wp_customize->add_setting( 'digital_agency_lite_slider_arrows',array(
    	'default' => 0,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ));  
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_slider_arrows',array(
      	'label' => esc_html__( 'Show / Hide Slider','digital-agency-lite' ),
      	'section' => 'digital_agency_lite_slidersettings'
    )));

    //Selective Refresh
    $wp_customize->selective_refresh->add_partial('digital_agency_lite_slider_arrows',array(
		'selector'        => '#slider .carousel-caption h1',
		'render_callback' => 'digital_agency_lite_customize_partial_digital_agency_lite_slider_arrows',
	));

	for ( $count = 1; $count <= 4; $count++ ) {
		$wp_customize->add_setting( 'digital_agency_lite_slider_page' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'digital_agency_lite_sanitize_dropdown_pages'
		) );
		$wp_customize->add_control( 'digital_agency_lite_slider_page' . $count, array(
			'label'    => __( 'Select Slider Page', 'digital-agency-lite' ),
			'description' => __('Slider image size (435 x 435)','digital-agency-lite'),
			'section'  => 'digital_agency_lite_slidersettings',
			'type'     => 'dropdown-pages'
		) );
	}

	//content layout
	$wp_customize->add_setting('digital_agency_lite_slider_content_option',array(
        'default' => __('Left','digital-agency-lite'),
        'sanitize_callback' => 'digital_agency_lite_sanitize_choices'
	));
	$wp_customize->add_control(new Digital_Agency_Lite_Image_Radio_Control($wp_customize, 'digital_agency_lite_slider_content_option', array(
        'type' => 'select',
        'label' => __('Slider Content Layouts','digital-agency-lite'),
        'section' => 'digital_agency_lite_slidersettings',
        'choices' => array(
            'Left' => get_template_directory_uri().'/assets/images/slider-content1.png',
            'Center' => get_template_directory_uri().'/assets/images/slider-content2.png',
            'Right' => get_template_directory_uri().'/assets/images/slider-content3.png',
    ))));

    //Slider excerpt
	$wp_customize->add_setting( 'digital_agency_lite_slider_excerpt_number', array(
		'default'              => 20,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'digital_agency_lite_slider_excerpt_number', array(
		'label'       => esc_html__( 'Slider Excerpt length','digital-agency-lite' ),
		'section'     => 'digital_agency_lite_slidersettings',
		'type'        => 'range',
		'settings'    => 'digital_agency_lite_slider_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );
 
	//Services
	$wp_customize->add_section('digital_agency_lite_services',array(
		'title'	=> __('Services Section','digital-agency-lite'),
		'panel' => 'digital_agency_lite_panel_id',
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'digital_agency_lite_section_title', array( 
		'selector' => '.heading-text h2', 
		'render_callback' => 'digital_agency_lite_customize_partial_digital_agency_lite_section_title',
	));

	$wp_customize->add_setting('digital_agency_lite_section_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('digital_agency_lite_section_title',array(
		'label'	=> __('Section Title','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( 'OUR SERVICES', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_services',
		'type'=> 'text'
	));

	$wp_customize->add_setting('digital_agency_lite_section_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('digital_agency_lite_section_text',array(
		'label'	=> __('Section Text','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_services',
		'type'=> 'text'
	));	

	$categories = get_categories();
		$cat_posts = array();
			$i = 0;
			$cat_posts[]='Select';	
		foreach($categories as $category){
			if($i==0){
			$default = $category->slug;
			$i++;
		}
		$cat_posts[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('digital_agency_lite_services_category',array(
		'default'	=> 'select',
		'sanitize_callback' => 'digital_agency_lite_sanitize_choices',
	));
	$wp_customize->add_control('digital_agency_lite_services_category',array(
		'type'    => 'select',
		'choices' => $cat_posts,
		'label' => __('Select Category to display Latest Post','digital-agency-lite'),
		'section' => 'digital_agency_lite_services',
	));

	//Services excerpt
	$wp_customize->add_setting( 'digital_agency_lite_services_excerpt_number', array(
		'default'              => 20,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'digital_agency_lite_services_excerpt_number', array(
		'label'       => esc_html__( 'Services Excerpt length','digital-agency-lite' ),
		'section'     => 'digital_agency_lite_services',
		'type'        => 'range',
		'settings'    => 'digital_agency_lite_services_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	//Blog Post
	$wp_customize->add_panel( $DigitalAgencyLiteParentPanel );

	$BlogPostParentPanel = new Digital_Agency_Lite_WP_Customize_Panel( $wp_customize, 'blog_post_parent_panel', array(
		'title' => __( 'Blog Post Settings', 'digital-agency-lite' ),
		'panel' => 'digital_agency_lite_panel_id',
	));

	$wp_customize->add_panel( $BlogPostParentPanel );

	// Add example section and controls to the middle (second) panel
	$wp_customize->add_section( 'digital_agency_lite_post_settings', array(
		'title' => __( 'Post Settings', 'digital-agency-lite' ),
		'panel' => 'blog_post_parent_panel',
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('digital_agency_lite_toggle_postdate', array( 
		'selector' => '.post-main-box h2 a', 
		'render_callback' => 'digital_agency_lite_customize_partial_digital_agency_lite_toggle_postdate', 
	));

	$wp_customize->add_setting( 'digital_agency_lite_toggle_postdate',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ) );
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_toggle_postdate',array(
        'label' => esc_html__( 'Post Date','digital-agency-lite' ),
        'section' => 'digital_agency_lite_post_settings'
    )));

    $wp_customize->add_setting( 'digital_agency_lite_toggle_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ) );
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_toggle_author',array(
		'label' => esc_html__( 'Author','digital-agency-lite' ),
		'section' => 'digital_agency_lite_post_settings'
    )));

    $wp_customize->add_setting( 'digital_agency_lite_toggle_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ) );
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_toggle_comments',array(
		'label' => esc_html__( 'Comments','digital-agency-lite' ),
		'section' => 'digital_agency_lite_post_settings'
    )));

    $wp_customize->add_setting( 'digital_agency_lite_toggle_tags',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
	));
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_toggle_tags', array(
		'label' => esc_html__( 'Tags','digital-agency-lite' ),
		'section' => 'digital_agency_lite_post_settings'
    )));

    $wp_customize->add_setting( 'digital_agency_lite_excerpt_number', array(
		'default'              => 30,
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'digital_agency_lite_excerpt_number', array(
		'label'       => esc_html__( 'Excerpt length','digital-agency-lite' ),
		'section'     => 'digital_agency_lite_post_settings',
		'type'        => 'range',
		'settings'    => 'digital_agency_lite_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	//Blog layout
    $wp_customize->add_setting('digital_agency_lite_blog_layout_option',array(
        'default' => __('Default','digital-agency-lite'),
        'sanitize_callback' => 'digital_agency_lite_sanitize_choices'
    ));
    $wp_customize->add_control(new Digital_Agency_Lite_Image_Radio_Control($wp_customize, 'digital_agency_lite_blog_layout_option', array(
        'type' => 'select',
        'label' => __('Blog Layouts','digital-agency-lite'),
        'section' => 'digital_agency_lite_post_settings',
        'choices' => array(
            'Default' => get_template_directory_uri().'/assets/images/blog-layout1.png',
            'Center' => get_template_directory_uri().'/assets/images/blog-layout2.png',
            'Left' => get_template_directory_uri().'/assets/images/blog-layout3.png',
    ))));

    $wp_customize->add_setting('digital_agency_lite_excerpt_settings',array(
        'default' => __('Excerpt','digital-agency-lite'),
        'transport' => 'refresh',
        'sanitize_callback' => 'digital_agency_lite_sanitize_choices'
	));
	$wp_customize->add_control('digital_agency_lite_excerpt_settings',array(
        'type' => 'select',
        'label' => __('Post Content','digital-agency-lite'),
        'section' => 'digital_agency_lite_post_settings',
        'choices' => array(
        	'Content' => __('Content','digital-agency-lite'),
            'Excerpt' => __('Excerpt','digital-agency-lite'),
            'No Content' => __('No Content','digital-agency-lite')
        ),
	) );

	$wp_customize->add_setting('digital_agency_lite_excerpt_suffix',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_excerpt_suffix',array(
		'label'	=> __('Add Excerpt Suffix','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '[...]', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_post_settings',
		'type'=> 'text'
	));

    // Button Settings
	$wp_customize->add_section( 'digital_agency_lite_button_settings', array(
		'title' => __( 'Button Settings', 'digital-agency-lite' ),
		'panel' => 'blog_post_parent_panel',
	));

	$wp_customize->add_setting('digital_agency_lite_button_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_button_padding_top_bottom',array(
		'label'	=> __('Padding Top Bottom','digital-agency-lite'),
		'description'	=> __('Enter a value in pixels. Example:20px','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_button_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('digital_agency_lite_button_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_button_padding_left_right',array(
		'label'	=> __('Padding Left Right','digital-agency-lite'),
		'description'	=> __('Enter a value in pixels. Example:20px','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_button_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'digital_agency_lite_button_border_radius', array(
		'default'              => '',
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'digital_agency_lite_button_border_radius', array(
		'label'       => esc_html__( 'Button Border Radius','digital-agency-lite' ),
		'section'     => 'digital_agency_lite_button_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('digital_agency_lite_button_text', array( 
		'selector' => '.post-main-box .more-btn a', 
		'render_callback' => 'digital_agency_lite_customize_partial_digital_agency_lite_button_text', 
	));

    $wp_customize->add_setting('digital_agency_lite_button_text',array(
		'default'=> 'READ MORE',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_button_text',array(
		'label'	=> __('Add Button Text','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( 'READ MORE', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_button_settings',
		'type'=> 'text'
	));

	// Related Post Settings
	$wp_customize->add_section( 'digital_agency_lite_related_posts_settings', array(
		'title' => __( 'Related Posts Settings', 'digital-agency-lite' ),
		'panel' => 'blog_post_parent_panel',
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('digital_agency_lite_related_post_title', array( 
		'selector' => '.related-post h3', 
		'render_callback' => 'digital_agency_lite_customize_partial_digital_agency_lite_related_post_title', 
	));

    $wp_customize->add_setting( 'digital_agency_lite_related_post',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ) );
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_related_post',array(
		'label' => esc_html__( 'Related Post','digital-agency-lite' ),
		'section' => 'digital_agency_lite_related_posts_settings'
    )));

    $wp_customize->add_setting('digital_agency_lite_related_post_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_related_post_title',array(
		'label'	=> __('Add Related Post Title','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( 'Related Post', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_related_posts_settings',
		'type'=> 'text'
	));

   	$wp_customize->add_setting('digital_agency_lite_related_posts_count',array(
		'default'=> '3',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_related_posts_count',array(
		'label'	=> __('Add Related Post Count','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '3', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_related_posts_settings',
		'type'=> 'number'
	));

    //404 Page Setting
	$wp_customize->add_section('digital_agency_lite_404_page',array(
		'title'	=> __('404 Page Settings','digital-agency-lite'),
		'panel' => 'digital_agency_lite_panel_id',
	));	

	$wp_customize->add_setting('digital_agency_lite_404_page_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('digital_agency_lite_404_page_title',array(
		'label'	=> __('Add Title','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '404 Not Found', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_404_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('digital_agency_lite_404_page_content',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('digital_agency_lite_404_page_content',array(
		'label'	=> __('Add Text','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( 'Looks like you have taken a wrong turn, Dont worry, it happens to the best of us.', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_404_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('digital_agency_lite_404_page_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_404_page_button_text',array(
		'label'	=> __('Add Button Text','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( 'GO BACK', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_404_page',
		'type'=> 'text'
	));

	//Social Icon Setting
	$wp_customize->add_section('digital_agency_lite_social_icon_settings',array(
		'title'	=> __('Social Icons Settings','digital-agency-lite'),
		'panel' => 'digital_agency_lite_panel_id',
	));	

	$wp_customize->add_setting('digital_agency_lite_social_icon_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_social_icon_font_size',array(
		'label'	=> __('Icon Font Size','digital-agency-lite'),
		'description'	=> __('Enter a value in pixels. Example:20px','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('digital_agency_lite_social_icon_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_social_icon_padding',array(
		'label'	=> __('Icon Padding','digital-agency-lite'),
		'description'	=> __('Enter a value in pixels. Example:20px','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('digital_agency_lite_social_icon_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_social_icon_width',array(
		'label'	=> __('Icon Width','digital-agency-lite'),
		'description'	=> __('Enter a value in pixels. Example:20px','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('digital_agency_lite_social_icon_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_social_icon_height',array(
		'label'	=> __('Icon Height','digital-agency-lite'),
		'description'	=> __('Enter a value in pixels. Example:20px','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'digital_agency_lite_social_icon_border_radius', array(
		'default'              => '',
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'digital_agency_lite_social_icon_border_radius', array(
		'label'       => esc_html__( 'Icon Border Radius','digital-agency-lite' ),
		'section'     => 'digital_agency_lite_social_icon_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Responsive Media Settings
	$wp_customize->add_section('digital_agency_lite_responsive_media',array(
		'title'	=> __('Responsive Media','digital-agency-lite'),
		'panel' => 'digital_agency_lite_panel_id',
	));

    $wp_customize->add_setting( 'digital_agency_lite_stickyheader_hide_show',array(
      'default' => 0,
      'transport' => 'refresh',
      'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ));  
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_stickyheader_hide_show',array(
      'label' => esc_html__( 'Sticky Header','digital-agency-lite' ),
      'section' => 'digital_agency_lite_responsive_media'
    )));

    $wp_customize->add_setting( 'digital_agency_lite_resp_slider_hide_show',array(
      'default' => 0,
      'transport' => 'refresh',
      'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ));  
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_resp_slider_hide_show',array(
      'label' => esc_html__( 'Show / Hide Slider','digital-agency-lite' ),
      'section' => 'digital_agency_lite_responsive_media'
    )));

	$wp_customize->add_setting( 'digital_agency_lite_metabox_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ));  
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_metabox_hide_show',array(
      'label' => esc_html__( 'Show / Hide Metabox','digital-agency-lite' ),
      'section' => 'digital_agency_lite_responsive_media'
    )));

    $wp_customize->add_setting( 'digital_agency_lite_sidebar_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ));  
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_sidebar_hide_show',array(
      'label' => esc_html__( 'Show / Hide Sidebar','digital-agency-lite' ),
      'section' => 'digital_agency_lite_responsive_media'
    )));

    $wp_customize->add_setting( 'digital_agency_lite_resp_scroll_top_hide_show',array(
      'default' => 0,
      'transport' => 'refresh',
      'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ));  
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_resp_scroll_top_hide_show',array(
      'label' => esc_html__( 'Show / Hide Scroll To Top','digital-agency-lite' ),
      'section' => 'digital_agency_lite_responsive_media'
    )));

    $wp_customize->add_setting('digital_agency_lite_res_menu_open_icon',array(
		'default'	=> 'fas fa-bars',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Digital_Agency_Lite_Fontawesome_Icon_Chooser(
        $wp_customize,'digital_agency_lite_res_menu_open_icon',array(
		'label'	=> __('Add Open Menu Icon','digital-agency-lite'),
		'transport' => 'refresh',
		'section'	=> 'digital_agency_lite_responsive_media',
		'setting'	=> 'digital_agency_lite_res_menu_open_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('digital_agency_lite_res_menu_close_icon',array(
		'default'	=> 'fas fa-times',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Digital_Agency_Lite_Fontawesome_Icon_Chooser(
        $wp_customize,'digital_agency_lite_res_menu_close_icon',array(
		'label'	=> __('Add Close Menu Icon','digital-agency-lite'),
		'transport' => 'refresh',
		'section'	=> 'digital_agency_lite_responsive_media',
		'setting'	=> 'digital_agency_lite_res_menu_close_icon',
		'type'		=> 'icon'
	)));

	//Content Creation
	$wp_customize->add_section( 'digital_agency_lite_content_section' , array(
    	'title' => __( 'Customize Home Page Settings', 'digital-agency-lite' ),
		'priority' => null,
		'panel' => 'digital_agency_lite_panel_id'
	) );

	$wp_customize->add_setting('digital_agency_lite_content_creation_main_control', array(
		'sanitize_callback' => 'esc_html',
	) );

	$homepage= get_option( 'page_on_front' );

	$wp_customize->add_control(	new digital_agency_lite_Content_Creation( $wp_customize, 'digital_agency_lite_content_creation_main_control', array(
		'options' => array(
			esc_html__( 'First select static page in homepage setting for front page.Below given edit button is to customize Home Page. Just click on the edit option, add whatever elements you want to include in the homepage, save the changes and you are good to go.','digital-agency-lite' ),
		),
		'section' => 'digital_agency_lite_content_section',
		'button_url'  => admin_url( 'post.php?post='.$homepage.'&action=edit'),
		'button_text' => esc_html__( 'Edit', 'digital-agency-lite' ),
	) ) );

	//Footer Text
	$wp_customize->add_section('digital_agency_lite_footer',array(
		'title'	=> __('Footer Settings','digital-agency-lite'),
		'panel' => 'digital_agency_lite_panel_id',
	));	

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('digital_agency_lite_footer_text', array( 
		'selector' => '.copyright p', 
		'render_callback' => 'digital_agency_lite_customize_partial_digital_agency_lite_footer_text', 
	));
	
	$wp_customize->add_setting('digital_agency_lite_footer_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('digital_agency_lite_footer_text',array(
		'label'	=> __('Copyright Text','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( 'Copyright 2019, .....', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('digital_agency_lite_copyright_alingment',array(
        'default' => __('center','digital-agency-lite'),
        'sanitize_callback' => 'digital_agency_lite_sanitize_choices'
	));
	$wp_customize->add_control(new Digital_Agency_Lite_Image_Radio_Control($wp_customize, 'digital_agency_lite_copyright_alingment', array(
        'type' => 'select',
        'label' => __('Copyright Alignment','digital-agency-lite'),
        'section' => 'digital_agency_lite_footer',
        'settings' => 'digital_agency_lite_copyright_alingment',
        'choices' => array(
            'left' => get_template_directory_uri().'/assets/images/copyright1.png',
            'center' => get_template_directory_uri().'/assets/images/copyright2.png',
            'right' => get_template_directory_uri().'/assets/images/copyright3.png'
    ))));

    $wp_customize->add_setting('digital_agency_lite_copyright_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_copyright_padding_top_bottom',array(
		'label'	=> __('Copyright Padding Top Bottom','digital-agency-lite'),
		'description'	=> __('Enter a value in pixels. Example:20px','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'digital_agency_lite_footer_scroll',array(
    	'default' => 0,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'digital_agency_lite_switch_sanitization'
    ));  
    $wp_customize->add_control( new Digital_Agency_Lite_Toggle_Switch_Custom_Control( $wp_customize, 'digital_agency_lite_footer_scroll',array(
      	'label' => esc_html__( 'Show / Hide Scroll to Top','digital-agency-lite' ),
      	'section' => 'digital_agency_lite_footer'
    )));

    //Selective Refresh
	$wp_customize->selective_refresh->add_partial('digital_agency_lite_scroll_to_top_icon', array( 
		'selector' => '.scrollup i', 
		'render_callback' => 'digital_agency_lite_customize_partial_digital_agency_lite_scroll_to_top_icon', 
	));

    $wp_customize->add_setting('digital_agency_lite_scroll_to_top_icon',array(
		'default'	=> 'fas fa-long-arrow-alt-up',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Digital_Agency_Lite_Fontawesome_Icon_Chooser(
        $wp_customize,'digital_agency_lite_scroll_to_top_icon',array(
		'label'	=> __('Add Scroll to Top Icon','digital-agency-lite'),
		'transport' => 'refresh',
		'section'	=> 'digital_agency_lite_footer',
		'setting'	=> 'digital_agency_lite_scroll_to_top_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('digital_agency_lite_scroll_to_top_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_scroll_to_top_font_size',array(
		'label'	=> __('Icon Font Size','digital-agency-lite'),
		'description'	=> __('Enter a value in pixels. Example:20px','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('digital_agency_lite_scroll_to_top_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_scroll_to_top_padding',array(
		'label'	=> __('Icon Top Bottom Padding','digital-agency-lite'),
		'description'	=> __('Enter a value in pixels. Example:20px','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('digital_agency_lite_scroll_to_top_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_scroll_to_top_width',array(
		'label'	=> __('Icon Width','digital-agency-lite'),
		'description'	=> __('Enter a value in pixels Example:20px','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('digital_agency_lite_scroll_to_top_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('digital_agency_lite_scroll_to_top_height',array(
		'label'	=> __('Icon Height','digital-agency-lite'),
		'description'	=> __('Enter a value in pixels. Example:20px','digital-agency-lite'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'digital-agency-lite' ),
        ),
		'section'=> 'digital_agency_lite_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'digital_agency_lite_scroll_to_top_border_radius', array(
		'default'              => '',
		'type'                 => 'theme_mod',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	) );
	$wp_customize->add_control( 'digital_agency_lite_scroll_to_top_border_radius', array(
		'label'       => esc_html__( 'Icon Border Radius','digital-agency-lite' ),
		'section'     => 'digital_agency_lite_footer',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

    $wp_customize->add_setting('digital_agency_lite_scroll_top_alignment',array(
        'default' => __('Right','digital-agency-lite'),
        'sanitize_callback' => 'digital_agency_lite_sanitize_choices'
	));
	$wp_customize->add_control(new Digital_Agency_Lite_Image_Radio_Control($wp_customize, 'digital_agency_lite_scroll_top_alignment', array(
        'type' => 'select',
        'label' => __('Scroll To Top','digital-agency-lite'),
        'section' => 'digital_agency_lite_footer',
        'settings' => 'digital_agency_lite_scroll_top_alignment',
        'choices' => array(
            'Left' => get_template_directory_uri().'/assets/images/layout1.png',
            'Center' => get_template_directory_uri().'/assets/images/layout2.png',
            'Right' => get_template_directory_uri().'/assets/images/layout3.png'
    ))));

    // Has to be at the top
	$wp_customize->register_panel_type( 'Digital_Agency_Lite_WP_Customize_Panel' );
	$wp_customize->register_section_type( 'Digital_Agency_Lite_WP_Customize_Section' );
}

add_action( 'customize_register', 'digital_agency_lite_customize_register' );

load_template( trailingslashit( get_template_directory() ) . '/inc/logo/logo-resizer.php' );

if ( class_exists( 'WP_Customize_Panel' ) ) {
  	class Digital_Agency_Lite_WP_Customize_Panel extends WP_Customize_Panel {
	    public $panel;
	    public $type = 'digital_agency_lite_panel';
	    public function json() {

	      $array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type', 'panel', ) );
	      $array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
	      $array['content'] = $this->get_content();
	      $array['active'] = $this->active();
	      $array['instanceNumber'] = $this->instance_number;
	      return $array;
    	}
  	}
}

if ( class_exists( 'WP_Customize_Section' ) ) {
  	class Digital_Agency_Lite_WP_Customize_Section extends WP_Customize_Section {
	    public $section;
	    public $type = 'digital_agency_lite_section';
	    public function json() {

	      $array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'panel', 'type', 'description_hidden', 'section', ) );
	      $array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
	      $array['content'] = $this->get_content();
	      $array['active'] = $this->active();
	      $array['instanceNumber'] = $this->instance_number;

	      if ( $this->panel ) {
	        $array['customizeAction'] = sprintf( 'Customizing &#9656; %s', esc_html( $this->manager->get_panel( $this->panel )->title ) );
	      } else {
	        $array['customizeAction'] = 'Customizing';
	      }
	      return $array;
    	}
  	}
}

// Enqueue our scripts and styles
function digital_agency_lite_customize_controls_scripts() {
  wp_enqueue_script( 'customizer-controls', get_theme_file_uri( '/assets/js/customizer-controls.js' ), array(), '1.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'digital_agency_lite_customize_controls_scripts' );

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class digital_agency_lite_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	*/
	public function sections( $manager ) {

		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/inc/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'Digital_Agency_Lite_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section( new Digital_Agency_Lite_Customize_Section_Pro( $manager,'example_1', array(
			'priority'   => 1,
			'title'    => esc_html__( 'Digital Agency Pro', 'digital-agency-lite' ),
			'pro_text' => esc_html__( 'UPGRADE PRO', 'digital-agency-lite' ),
			'pro_url'  => esc_url('https://www.vwthemes.com/themes/digital-marketing-wordpress-theme/'),
		) )	);

		// Register sections.
		$manager->add_section(new Digital_Agency_Lite_Customize_Section_Pro($manager,'example_2',array(
			'priority'   => 1,
			'title'    => esc_html__( 'Documentation', 'digital-agency-lite' ),
			'pro_text' => esc_html__( 'DOCS', 'digital-agency-lite' ),
			'pro_url'  => admin_url('themes.php?page=digital_agency_lite_guide'),
		)));
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'digital-agency-lite-customize-controls', trailingslashit( get_template_directory_uri() ) . '/assets/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'digital-agency-lite-customize-controls', trailingslashit( get_template_directory_uri() ) . '/assets/css/customize-controls.css' );
	}
}

// Doing this customizer thang!
digital_agency_lite_Customize::get_instance();