<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public/partials
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


function siret_search_form(){
    $action = get_option( 'validation' );
    $result = '<form action="'.$action[0].'">
	  <input type="text" name="codesiret" pattern="[0-9]{14}" title="14 chiffres"><br>
	  <input id="searchhome" type="submit" value="Recherche">
	</form>';
    return $result;
}

function acfe_cpt_pdf_process(){
	if (isset($_POST['buttonverifier'])){
            //create on-hold siret post
            $post_id = wp_insert_post(array (
            'post_type' => 'siret',
            'post_title' => $_POST['input-siret'],
            'post_status' => 'draft',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            )
        );

            if ($post_id) {
                add_post_meta($post_id, 'dirigeant', $_POST['input-dirigeant'], true);
                add_post_meta($post_id, 'role', $_POST['radios-role'], true);
                add_post_meta($post_id, 'courriel', $_POST['input-email'], true);
                add_post_meta($post_id, 'telefone', $_POST['input-tel'], true);
                add_post_meta($post_id, 'uniteLegale', $_POST['input-unite'], true);
                add_post_meta($post_id, 'adresse', $_POST['input-adresse'], true);
                add_post_meta($post_id, 'codePostal', $_POST['input-codepostal'], true);
                add_post_meta($post_id, 'dpo', $_POST['input-dpo'], true);
                add_post_meta($post_id, 'ville', $_POST['input-ville'], true);
				add_post_meta($post_id, 'ip', $_POST['input-ip'], true);
				add_post_meta($post_id, 'date', $_POST['input-date'], true);
				add_post_meta($post_id, 'heure', $_POST['input-heure'], true);
				add_post_meta($post_id, 'enregistrement', $_POST['input-enregistrement'], true);
                
                //Auto submitting form
                
				$redirectionURL = get_option( 'validation' );
               
				?><form id="formpdf" method="post" action="<?php echo plugin_dir_url( __FILE__ ).'download.php'; ?>">
					   <?php foreach ($_POST as $aname => $avalue){?>
                         <input type="hidden" name="<?php echo $aname; ?>" value="<?php echo $avalue; ?>">
                      <?php } ?>
                      <input type="hidden" name="redirection" value="<?php echo home_url( '/' ).$redirectionURL[0]; ?>">

                <form>
                <script>
                document.getElementById("formpdf").submit();
                </script>';
					<?php
            } else {
                return "<h4>Erreur: Nous n'avons pas pu créer votre demande. Veuillez essayer à nouveau</h4>";
            }
            // end create siret post
        }
}
function siret_search_results(){
    //wp_reset_postdata();
    if (isset($_GET['codesiret'])){
        global $wpdb;
        $codesiret = $_GET['codesiret'];
        $querystr = "
    SELECT $wpdb->posts.* 
    FROM $wpdb->posts, $wpdb->postmeta
    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
    AND $wpdb->posts.post_title = $codesiret 
    AND $wpdb->posts.post_type = 'siret'
 ";

 $pageposts = $wpdb->get_results($querystr, OBJECT);
       $siretfound=0;
        if ( $pageposts ) {
            global $post; 
$post= $pageposts[0];
siret_search_result_display($post->ID);
            $siretfound=1;
                }
        else{
        include_once( PLUGIN_PATH. 'includes/class-aba-rgpd-for-acfe-api-handler.php' );
        new ABA_Rgpd_For_Acfe_Api_Handler($codesiret);
            $siretfound=1;
        }
        if ($siretfound==0){echo '<div class="colonne2"><h3 class="text-center text-fail">Aucune Siret trouvée</h3></div>';}
    }
    else{
		if(isset($_POST['buttonverifier'])){
                acfe_cpt_pdf_process();
        }elseif (isset($_GET['sent'])){
			if ($_GET['sent'] == 1){
				echo '<div class="row">
		<div class="colonne">
			<h4 class="text-center text-success">L\'attestation signée a été enregistrée avec succès.</h4>
		</div>
			</div>';} else {
				echo '<div class="row">
		<div class="colonne2">
			<h4 class="text-center text-fail">Une erreur a été produite lors de la génération de l\'attestation signée.</h4>
		</div>
	</div>';
		}}else{
        echo '<form style="margin-top: 30px;">
  <input id="inputvalidee" type="text" name="codesiret" pattern="[0-9]{14}" title="14 chiffres"><br>
  <input id="searchvalidee" type="submit" value="Recherche">
</form>';
    }
	}
}

function siret_search_result_display($siret_id){
    $siret_post = get_post($siret_id);
    $role= get_post_meta( $siret_post->ID, 'role', true );
    $rolenicename = 'Responsable des traitements';
    if ($role='chef') { $rolenicename = 'Chef de l\'établissement';}
    $result= '
    <div class="container-fluid">
	<div class="row">
		<div class="colonnesiret">
			<h4 class="champssiret">Siret:</h4>
			<p class="contenuchampssiret">'.$siret_post->post_title.'</p>
		</div>
		<div class="colonnedemi">
			<h4 class="titrechamps">Nom de l\'unité légale:</h4>
			<p class="contenuchamps">'.get_post_meta( $siret_post->ID, 'uniteLegale', true ).'</p>
			<h4 class="titrechamps">Adresse:</h4>
			<p class="contenuchamps">'.get_post_meta( $siret_post->ID, 'adresse', true ).'</p>
			<h4 class="titrechamps">Ville ou Code Postal:</h4>
			<p class="contenuchamps">'.get_post_meta( $siret_post->ID, 'ville', true ).'</p>
			<div id="clearaba"></div>
			<h4 class="titrechamps">Dirigeant/Responsable:</h4>
			<p class="contenuchamps">'.get_post_meta( $siret_post->ID, 'dirigeant', true ).'</p>
			<h4 class="titrechamps">Rôle:</h4>
			<p class="contenuchamps">'.$rolenicename.'</p>
		</div>
	</div>';
    
    if($siret_post->post_status=='publish'){
        $result .= '<div class="row">
		<div class="colonne">
			<h4 class="text-center text-success">L\'unité légale avec le Siret '.$siret_post->post_title.' est conforme RGPD.</h4>
		</div>
	</div>';
    } 
    if ($siret_post->post_status=='draft'){
        $soumtarget=get_option('submit_ettestation');
        $result .= '<div class="row">
		<div class="colonneentier">
			<h4 class="text-center text-warning">
				L\'unité légale avec le Siret '.$siret_post->post_title.' est en attente de validation.
			</h4>
			<p class="text-center">
				Vous êtes le dirigeant, le responsable ou le DPO? <a href="'.$soumtarget[0].'" class="bouton" type="button">Contactez nous</a> pour savoir plus sur l\'état de votre attestation.</p>
		</div>
	</div>';
    }
    $result .='</div>';
    echo $result;
}