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
			// The admin UI is a self-contained React app using the plugin's own component kit,
			// so no WordPress component stylesheet (wp-components) is loaded.
			$js      = GMWQP_PLUGIN_DIR . 'build/admin/admin.js';
			$version = file_exists( $js ) ? filemtime( $js ) : '1';
			wp_register_script( 'gmwqp-react-admin', GMWQP_PLUGIN_URL . 'build/admin/admin.js', array(), $version, true );

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
	            'pro_url' => GMWQP_PRO_URL,
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
		            GMWQP_PLUGIN_URL.'build/admin/admin.css',
		            array(),
		            $version,
		        );
			
		}
	}

	public function GMWQP_init () {
		$args = array(
				'label'               => __( 'gmwqp_enquiry', 'gm-woocommerce-quote-popup' ),
				'show_ui'             => false,
				'show_in_menu'        => false,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
				'menu_position'       => 5,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'public' => false,
				'publicly_queryable'  => false,
				);
	
		// Registering your Custom Post Type
		register_post_type( 'gmwqp_enquiry', $args );
	}

	public function GMWQP_admin_menu () {

		add_menu_page('Woo Quote Popup', 'Woo Quote Popup', 'manage_options', 'GMWQP', array( $this, 'GMWQP_page' ));
	}

	public function GMWQP_page() {
		?>
		<div class="wrap gmwqp-wrap">
			<h1 class="screen-reader-text"><?php esc_html_e( 'Woo Quote Popup', 'gm-woocommerce-quote-popup' ); ?></h1>
			<div id="GMWQP-admin-root"></div>
		</div>
		<?php
	}
}
