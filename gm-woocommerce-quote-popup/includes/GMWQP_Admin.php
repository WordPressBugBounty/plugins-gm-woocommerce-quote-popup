<?php

/**
 * This class is loaded on the back-end since its main job is 
 * to display the Admin to box.
 */

class GMWQP_Admin {

	public function __construct () {

		
		add_action( 'admin_menu', array( $this, 'GMWQP_admin_menu' ) );
		add_action('admin_enqueue_scripts', array( $this, 'GMWQP_admin_script' ));
		add_action( 'init', array( $this, 'GMWQP_init' ) );
		if ( is_admin() ) {
			return;
		}
		
	}

	public function GMWQP_admin_script ($hook) {
		if($hook=='toplevel_page_GMWQP'){
			wp_enqueue_style('wp-components');
			wp_register_script(
	            'gmwqp-react-admin',
	            GMWQP_PLUGIN_URL.'/build/admin/admin.js', // Adjust the path if necessary
		        ['wp-element','wp-dom-ready','wp-components'], // Ensure this depends on WordPress's React
		        '1.0',
		        true

		        );

			global $gmwqp_translation;
	        wp_localize_script('gmwqp-react-admin', 'gmwqp_wp_ajax', [
	            'nonce' => wp_create_nonce('wp_rest'), 
	            'getsettings' => add_query_arg('rand', rand(),rest_url('gmwqp/v1/get-settings')),
	            'getenquirys' => add_query_arg('rand', rand(),rest_url('gmwqp/v1/get-enquiries')),
	            'savedata' => rest_url('gmwqp/v1/save-settings'),
	            'deleteallenquirys' => rest_url('gmwqp/v1/deleteallenquirys'),
	            'savecustomfield' => rest_url('gmwqp/v1/save-customfield'),
	            'deletecustomfield' => rest_url('gmwqp/v1/delete-customfield'),
	            'site_url' => get_site_url(),
	            'gmwqpcategory' => get_terms( 'product_cat', array(
                        'hide_empty' => false,
                    ) ),
	            'gmwqppages' => get_posts( array(
                              'posts_per_page' => -1,
                              'post_type'  => 'page',
                          ) ),
	            'gmwqp_translation' => $gmwqp_translation,
	        ]);

			wp_enqueue_script('gmwqp-react-admin');

		    wp_enqueue_style(
		            'gmwqp-react-admin-style',
		            GMWQP_PLUGIN_URL.'/build/admin/admin.css',
		            array(),
		            1,
		        );
			
		}
	}

	public function GMWQP_init () {
		$args = array(
				'label'               => __( 'gmwqp_enquiry', 'gmwqp' ),
				'show_ui'             => false,
				'show_in_menu'        => false,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
				'menu_position'       => 5,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				);
	
		// Registering your Custom Post Type
		register_post_type( 'gmwqp_enquiry', $args );
	}

	public function GMWQP_admin_menu () {

		add_menu_page('Woo Quote Popup', 'Woo Quote Popup', 'manage_options', 'GMWQP', array( $this, 'GMWQP_page' ));
	}

	public function GMWQP_page() {

		
	?>
	<div>
	  
	   <h2><?php _e('Woo Quote Popup', 'gmwqp'); ?></h2>
	    <div class="about-text">
	        <p>
				Thank you for using our plugin! If you are satisfied, please reward it a full five-star <span style="color:#ffb900">★★★★★</span> rating.                        <br>
	            <a href="https://wordpress.org/support/plugin/gm-woocommerce-quote-popup/reviews/?filter=5" target="_blank">Reviews</a>
	            
	            | <a href="https://www.codesmade.com/product-enquiry-for-woocommerce-documentation/" target="_blank">Documentation</a>
	            | <a href="https://www.codesmade.com/contact-us/" target="_blank">Support 24x7</a> <span style="font-weight: bold;">We will respond to your support request within 24 hours.</span>
	        </p>
	    </div>
	   <?php
	   echo '<div id="GMWQP-admin-root"></div>';
		?>
	</div>
		<?php
	}

	
}
