<?php

/**
 * Plugin Name:       RGPD for ACFE
 * Plugin URI:        https://aqazi.net/plugins/rgpd-for-acfe
 * Description:       Un plugin spécifique RGPD pour l'ACFE pour la fonctionnalité de conformité RGPD des établissements françaises.
 * Version:           1.0.0
 * Author:            aqazi Studio
 * Author URI:        https://aqazi.net/
 * Developer:         Ahmed Ben Ali
 * Developer URI:     https://aqazi.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       acfe
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'RGPD_FOR_ACFE_VERSION', '1.0.0' );
define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-aba-rgpd-for-acfe-activator.php
 *
 * @version		1.0.0
 * @author 		Ahmed Ben Ali, aqazi Studio
 */
function activate_aba_rgpd_for_acfe() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aba-rgpd-for-acfe-activator.php';
	ABA_Rgpd_For_Acfe_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-aba-rgpd-for-acfe-deactivator.php
 *
 * @version		1.0.0
 * @author 		Ahmed Ben Ali, aqazi Studio
 */
function deactivate_aba_rgpd_for_acfe() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aba-rgpd-for-acfe-deactivator.php';
	ABA_Rgpd_For_Acfe_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_aba_rgpd_for_acfe' );
register_deactivation_hook( __FILE__, 'deactivate_aba_rgpd_for_acfe' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 *
 * @version		1.0.0
 * @author 		Ahmed Ben Ali, aqazi Studio
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-aba-rgpd-for-acfe.php';

/**
 * Begins execution of the plugin.
 *
 * @version		1.0.0
 * @author 		Ahmed Ben Ali, aqazi Studio
 */
function run_aba_rgpd_for_acfe() {

	$plugin = new ABA_Rgpd_For_Acfe();
	$plugin->run();

}
run_aba_rgpd_for_acfe();
