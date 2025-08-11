<?php

/**
 * The Custom Post Handler of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! class_exists( 'ABA_Rgpd_For_Acfe_Admin' ) ) {
class ABA_Rgpd_For_Acfe_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rgpd-for-acfe-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rgpd-for-acfe-admin.js', array( 'jquery' ), $this->version, false );

	}
    
    public function create_plugin_settings_page() {

    $page_title = 'Paramètres RGPD pour CCFE';
    $menu_title = 'Confirmation RGPD';
    $capability = 'manage_options';
    $slug = 'rgpd_acfe';
    $callback = array( $this, 'plugin_settings_page_content' );
    $icon = 'dashicons-admin-plugins';
    $position = 100;

    add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
    }
    
    public function plugin_settings_page_content() {?>
    <div class="wrap">
        <h2>Paramètres RGPD pour CCFE</h2><?php
            if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ){
                  $this->admin_notice();
            } ?>
        <form method="post" action="options.php">
            <?php
                settings_fields( 'rgpd_acfe' );
                do_settings_sections( 'rgpd_acfe' );
                submit_button();
            ?>
        </form>
    </div> <?php
    }
    
    public function setup_sections() {
        add_settings_section( 'api_settings', 'Paramètres API', array( $this, 'section_callback' ), 'rgpd_acfe' );
        add_settings_section( 'search_settings', 'Paramètres Formulaires & pages', array( $this, 'section_callback' ), 'rgpd_acfe' );
    }
        public function section_callback( $arguments ) {
    	switch( $arguments['id'] ){
    		case 'Paramètres API':
    			echo 'Entrer les paramètres secrètes de l\'API';
    			break;
    		case 'Paramètres Formulaires & pages':
    			echo 'Entrer les pages du système';
    			break;
    	}
    }
    public function setup_fields() {
    $pages = get_pages();

      foreach ( $pages as $page ) {
        $options [$page->post_name] = $page->post_title;
      }
    $fields = array(
        array(
            'uid' => 'consumer_key',
            'label' => 'Consumer Key',
            'section' => 'api_settings',
            'type' => 'text',
            'options' => false,
            'helper' => 'Propriété Consumer Key de l\'API INSEE',
        ),
        array(
            'uid' => 'consumer_secret',
            'label' => 'Consumer Secret',
            'section' => 'api_settings',
            'type' => 'text',
            'options' => false,
            'helper' => 'Propriétés Consumer Secret de l\'API INSEE',
        ),
        array(
            'uid' => 'validation',
            'label' => 'Résultat de recherche',
            'section' => 'search_settings',
            'type' => 'select',
            'options' => $options,
            'helper' => 'La page contenant le shortcode [siret-results]',
        ),
        // array(
            // 'uid' => 'siret_post',
            // 'label' => 'Page d\'insertion du SIRET dans le système',
            // 'section' => 'search_settings',
            // 'type' => 'select',
            // 'options' => $options,
            // 'helper' => 'La page contenant le shortcode [siret-create-post]',
            // 'default' => array(),
        // ),
        array(
            'uid' => 'submit_ettestation',
            'label' => 'Page de contact',
            'section' => 'search_settings',
            'type' => 'select',
            'options' => $options,
            'helper' => 'La page contenant le formulaire de contact',
            'default' => array(),
        )
    );
    foreach( $fields as $field ){
        add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'rgpd_acfe', $field['section'], $field );
        register_setting( 'rgpd_acfe', $field['uid'] );
        }
    }
    
        public function field_callback( $arguments ) {
        $value = get_option( $arguments['uid'] );
        if( ! $value ) {
            $value = $arguments['default'];
        }
        switch( $arguments['type'] ){
            case 'text':
                printf( '<input name="%1$s" id="%1$s" type="%2$s" value="%3$s" />', $arguments['uid'], $arguments['type'], $value );
                break;
            case 'select':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $attributes = '';
                    $options_markup = '';
                    foreach( $arguments['options'] as $key => $label ){
                        $options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value[ array_search( $key, array('value'=>$value), true ) ], $key, false ), $label );
                    }
                    printf( '<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup );
                }
                break;
        }
        if( $helper = $arguments['helper'] ){
            printf( '<span class="helper"> %s</span>', $helper );
        }
    }
    
        public function admin_notice() { ?>
        <div class="notice notice-success is-dismissible">
            <p>Les paramètres ont été enregistrés!</p>
        </div><?php
    }
}
}