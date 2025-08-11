<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! class_exists( 'ABA_Rgpd_For_Acfe_Siret_Handler' ) ) {
class ABA_Rgpd_For_Acfe_Siret_Handler {
    
    /**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function aba_rgpd_for_acfe_cpt_init() {
           $labels = array(
        'name'                => _x( 'Sirets', 'Post Type General Name', 'acfe' ),
        'singular_name'       => _x( 'Siret', 'Post Type Singular Name', 'acfe' ),
        'menu_name'           => __( 'Sirets', 'acfe' ),
        'all_items'           => __( 'Toutes les Sirets', 'acfe' ),
        'view_item'           => __( 'Afficher Siret', 'acfe' ),
        'add_new_item'        => __( 'Ajouter Nouvelle Siret', 'acfe' ),
        'add_new'             => __( 'Ajouter Nouveau', 'acfe' ),
        'edit_item'           => __( 'Editer Siret', 'acfe' ),
        'update_item'         => __( 'Mettre à jour siret', 'acfe' ),
        'search_items'        => __( 'Rechercher Siret', 'acfe' ),
        'not_found'           => __( 'Aucune siret', 'acfe' ),
        'not_found_in_trash'  => __( 'Aucune Siret dans la Corbeille', 'acfe' ),
    );
          
    $args = array(
        'label'               => __( 'siret', 'acfe' ),
        'description'         => __( 'Les siret conformes RGPD', 'acfe' ),
        'labels'              => $labels,
        'supports'            => array( 'title' ),
        'hierarchical'        => false,
        'public'              => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'siret' ),
        'capability_type'     => 'page',
        'exclude_from_search' => true,
    );
     
    register_post_type( 'siret', $args );

    }
    
    /**
 * Adds a meta box to the post editing screen
 */
function acfe_custom_meta() {
	add_meta_box( 'acfe_meta', __( 'Données SIRET', 'acfe' ), array($this, 'acfe_meta_callback'), 'siret' );
}
//add_action( 'add_meta_boxes', 'prfx_custom_meta' );
/**
 * Outputs the content of the meta box
 */
function acfe_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'acfe_nonce' );
	$acfe_stored_meta = get_post_meta( $post->ID );
	?>

	<p>
        <label for="dirigeant" class="acfe-row-title"><strong><?php _e( 'Nom Dirigeant/Responsable', 'acfe' )?></strong></label><br>
		<input type="text" name="dirigeant" id="dirigeant" value="<?php if ( isset ( $acfe_stored_meta['dirigeant'] ) ) echo $acfe_stored_meta['dirigeant'][0]; ?>" />
        <p><i>le nom du dirigeant ou le responsable des traitements.</i></p>
	</p>

	<p>
        <span class="acfe-row-title"><strong><?php _e( 'Rôle', 'acfe' )?></strong></span>
		<div class="acfe-row-content">
			<label for="role">
				<input type="radio" name="role" id="chef" value="chef" <?php if ( isset ( $acfe_stored_meta['role'] ) ) checked( $acfe_stored_meta['role'][0], 'chef' ); ?>/>
				<?php _e( 'Chef de l\'établissement', 'acfe' )?>
			</label>
			<label for="meta-radio-two">
				<input type="radio" name="role" id="responsable" value="responsable" <?php if ( isset ( $acfe_stored_meta['role'] ) ) checked( $acfe_stored_meta['role'][0], 'responsable' ); ?>/>
				<?php _e( 'Responsable des traitements', 'acfe' )?>
			</label>
               <p><i>Rôle du dirigeant/responsable.</i></p>
		</div>
    </p>

    <p>
		<label for="courriel" class="acfe-row-title"><strong><?php _e( 'E-mail', 'acfe' )?></strong></label><br>
		<input type="email" name="courriel" id="courriel" value="<?php if ( isset ( $acfe_stored_meta['courriel'] ) ) echo $acfe_stored_meta['courriel'][0]; ?>" />
        <p><i>l'e-mail de contact.</i></p>
	</p>

    <p>
		<label for="telefone" class="acfe-row-title"><strong><?php _e( 'Téléphone', 'acfe' )?></strong></label><br>
		<input type="text" name="telefone" id="telefone" value="<?php if ( isset ( $acfe_stored_meta['telefone'] ) ) echo $acfe_stored_meta['telefone'][0]; ?>" />
        <p><i>le téléphone de contact.</i></p>
	</p>

    <p>
		<label for="uniteLegale" class="acfe-row-title"><strong><?php _e( 'Nom de l\'établissement', 'acfe' )?></strong></label><br>
		<input type="text" name="uniteLegale" id="uniteLegale" value="<?php if ( isset ( $acfe_stored_meta['uniteLegale'] ) ) echo $acfe_stored_meta['uniteLegale'][0]; ?>" />
        <p><i>le nom du dirigeant ou le responsable des traitements.</i></p>
	</p>

    <p>
        <label for="adresse" class="acfe-row-title"><strong><?php _e( 'Adresse', 'acfe' )?></strong></label><br>
		<input type="text" name="adresse" id="adresse" value="<?php if ( isset ( $acfe_stored_meta['adresse'] ) ) echo $acfe_stored_meta['adresse'][0]; ?>" />
        <p><i>L'adresse du siège de l'établissement</i></p>
	</p>

    <p>
        <label for="codePostal" class="acfe-row-title"><strong><?php _e( 'Code Postal', 'acfe' )?></strong></label><br>
		<input type="text" name="codePostal" id="codePostal" value="<?php if ( isset ( $acfe_stored_meta['codePostal'] ) ) echo $acfe_stored_meta['codePostal'][0]; ?>" />
        <p><i>le code postal enregistré</i></p>
	</p>

    <p>
        <label for="ville" class="acfe-row-title"><strong><?php _e( 'Ville', 'acfe' )?></strong></label><br>
		<input type="text" name="ville" id="ville" value="<?php if ( isset ( $acfe_stored_meta['ville'] ) ) echo $acfe_stored_meta['ville'][0]; ?>" />
        <p><i>la ville générée par code postal.</i></p>
	</p>
		<p>        <label for="enregistrement" class="acfe-row-title"><strong><?php _e( 'Numéro d\'enregistrement', 'acfe' )?></strong></label><br>		<input type="text" name="enregistrement" id="enregistrement" value="<?php if ( isset ( $acfe_stored_meta['enregistrement'] ) ) echo $acfe_stored_meta['enregistrement'][0]; ?>" />        <p><i>Le numéro d'enregistrement.</i></p>	</p>
    <p>
        <label for="dpo" class="acfe-row-title"><strong><?php _e( 'DPO', 'acfe' )?></strong></label><br>
		<input type="text" name="dpo" id="dpo" value="<?php if ( isset ( $acfe_stored_meta['dpo'] ) ) echo $acfe_stored_meta['dpo'][0]; ?>" />
        <p><i>Le nom de l'officier de protection des données.</i></p>

	</p>		<p>        <label for="ip" class="acfe-row-title"><strong><?php _e( 'Adresse IP Signature', 'acfe' )?></strong></label><br>		<input type="text" name="ip" id="ip" value="<?php if ( isset ( $acfe_stored_meta['ip'] ) ) echo $acfe_stored_meta['ip'][0]; ?>" />        <p><i>L'adresse ip de la signature électronique.</i></p>	</p>		<p>        <label for="date" class="acfe-row-title"><strong><?php _e( 'Data Signature', 'acfe' )?></strong></label><br>		<input type="text" name="date" id="date" value="<?php if ( isset ( $acfe_stored_meta['date'] ) ) echo $acfe_stored_meta['date'][0]; ?>" />        <p><i>La date de la signature électronique.</i></p>	</p>		<p>        <label for="heure" class="acfe-row-title"><strong><?php _e( 'Heure Signature', 'acfe' )?></strong></label><br>		<input type="text" name="heure" id="heure" value="<?php if ( isset ( $acfe_stored_meta['heure'] ) ) echo $acfe_stored_meta['heure'][0]; ?>" />        <p><i>L'heure de la signature électronique.</i></p>	</p>
	<?php
}
/**
 * Saves the custom meta input
 */
function acfe_meta_save( $post_id ) {
 
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'acfe_nonce' ] ) && wp_verify_nonce( $_POST[ 'acfe_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}
    if ( !current_user_can( 'edit_page', $post_id ) ){
                print 'Sorry, can\'t edit.';
    }
	if( isset( $_POST[ 'dirigeant' ] ) ) {
		update_post_meta( $post_id, 'dirigeant', sanitize_text_field( $_POST[ 'dirigeant' ] ) );
	}
	if( isset( $_POST[ 'role' ] ) ) {
		update_post_meta( $post_id, 'role', $_POST[ 'role' ] );
	}
    	if( isset( $_POST[ 'courriel' ] ) ) {
		update_post_meta( $post_id, 'courriel', sanitize_text_field( $_POST[ 'courriel' ] ) );
	}
    	if( isset( $_POST[ 'telefone' ] ) ) {
		update_post_meta( $post_id, 'telefone', sanitize_text_field( $_POST[ 'telefone' ] ) );
	}
	if( isset( $_POST[ 'uniteLegale' ] ) ) {
		update_post_meta( $post_id, 'uniteLegale', $_POST[ 'uniteLegale' ] );
	}
	if( isset( $_POST[ 'adresse' ] ) ) {
		update_post_meta( $post_id, 'adresse', $_POST[ 'adresse' ] );
	}
	if( isset( $_POST[ 'codePostal' ] ) ) {
		update_post_meta( $post_id, 'codePostal', $_POST[ 'codePostal' ] );
	}
    	if( isset( $_POST[ 'ville' ] ) ) {
		update_post_meta( $post_id, 'ville', $_POST[ 'ville' ] );
	}
	if( isset( $_POST[ 'enregistrement' ] ) ) {
		update_post_meta( $post_id, 'enregistrement', $_POST[ 'enregistrement' ] );
	}
	if( isset( $_POST[ 'dpo' ] ) ) {
		update_post_meta( $post_id, 'dpo', $_POST[ 'dpo' ] );
	}		if( isset( $_POST[ 'ip' ] ) ) {		update_post_meta( $post_id, 'ip', $_POST[ 'ip' ] );	}		if( isset( $_POST[ 'date' ] ) ) {		update_post_meta( $post_id, 'date', $_POST[ 'date' ] );	}		if( isset( $_POST[ 'heure' ] ) ) {		update_post_meta( $post_id, 'heure', $_POST[ 'heure' ] );	}
}

}
}