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
if ( ! class_exists( 'ABA_Rgpd_For_Acfe_Api_Handler' ) ) {
class ABA_Rgpd_For_Acfe_Api_Handler {
    
    protected $token;
    public $request_result;
    /**
     * Constructor for handler class.
     *
     * @since 1.0.0
     * @access public
     * @param bool $sandbox (yes or no)
     * @param string $token
     * @param string $consumer_key
     * @param string $consumer_secret
     * @param string $siret
	 */
	public function __construct($siret = '') {

        $this->siret = $siret;
        $this->consumer_key = get_option('consumer_key');
        $this->consumer_secret = get_option('consumer_secret');
        //add_action ('init', array($this, 'acfe_api_result_process'));
        $this->request_result = $this->acfe_api_result_process($this->consumer_key, $this->consumer_secret, $this->siret);           
        }
    
    /**
     * Initialise siren request
     *
     * @since 1.0.0
     * @access public
     * @param int $siren
     * @return array
     */
    public function init_siret_request($consumer_key, $consumer_secret, $siret){
        
        //$fp = fopen(PLUGIN_PATH.'/curl1.txt', 'w');
        
        $this->token = $this->init_token_request($consumer_key, $consumer_secret);
        //print_r($this->token);
        $request_url = 'https://api.insee.fr/entreprises/sirene/V3/siret/'.$siret;
            
        $json_headers = array('Accept: application/json', 'Authorization: Bearer '.$this->token['access_token']);
        
        $init_request = curl_init($request_url);
        
        //cURL options
        curl_setopt($init_request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($init_request, CURLOPT_HTTPHEADER, $json_headers);
        curl_setopt($init_request, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($init_request, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($init_request, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($init_request, CURLOPT_VERBOSE, 1);
        // curl_setopt($init_request, CURLOPT_STDERR, $fp);

        $result = json_decode(curl_exec($init_request), true);
        curl_close($init_request);
        
        return $result;
    }
    
    /**
     * Check if php cURL extension is enabled.
     *
     * @since 1.0.0
     * @access public
     * @return bool
     */
    public function is_curl_enabled() {
        return function_exists('curl_version');
    }
    
    /**
     * Request token
     *
     * @since 1.0.0
     * @access public
     * @param string $consumer_key
     * @param string $consumer_secret
     * @return array
     */
    public function init_token_request($consumer_key, $consumer_secret){
        
        $request_url = 'https://api.insee.fr/token';
        //$fp = fopen(PLUGIN_PATH.'/curl2.txt', 'w');
        $authentication = base64_encode($consumer_key.':'.$consumer_secret);
        //echo $authentication;
        $json_headers = array('Authorization: Basic '.$authentication);
        
        $init_request = curl_init($request_url);
        
        //cURL options
        curl_setopt($init_request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($init_request, CURLOPT_HTTPHEADER, $json_headers);
        curl_setopt($init_request, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($init_request, CURLOPT_POST, 1);
        curl_setopt($init_request, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($init_request, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($init_request, CURLOPT_CONNECTTIMEOUT, 15);
        // curl_setopt($init_request, CURLOPT_VERBOSE, 1);
        // curl_setopt($init_request, CURLOPT_STDERR, $fp);

        $result = json_decode(curl_exec($init_request), true);
        curl_close($init_request);
        
        return $result;
    }
    
    /**
     * API result processor
     *
     * @since 1.0.0
     * @access public
     * @param string $consumer_key
     * @param string $consumer_secret
     * @param string $
     * @return array
     */
    public function acfe_api_result_process($consumer_key, $consumer_secret, $siret){
        
        $requestresult = $this->init_siret_request($consumer_key, $consumer_secret, $siret);
        $this->acfe_api_result_form($requestresult);
    }
    
    /**
     * API result form
     *
     * @since 1.0.0
     * @access public
     * @param string $consumer_key
     * @param string $consumer_secret
     * @param string $
     * @return array
     */
    public function acfe_api_result_form($request_result){
               
        if ($request_result['header']['statut']==200){
			// $siret_create=get_option('siret_post');
			$validationURL = get_option( 'validation' );
            $codePostal = $request_result['etablissement']['adresseEtablissement']['codePostalEtablissement'];
            $vicopoUrl = 'https://vicopo.selfbuild.fr/code/' . urlencode($codePostal);
            $json = @json_decode(file_get_contents($vicopoUrl));
            $ville = ! is_object($json) || empty($json->cities) ? $codePostal : $json->cities[0]->city;
            $nomunite = $request_result['etablissement']['uniteLegale']['nomUniteLegale'].' '.$request_result['etablissement']['uniteLegale']['prenom1UniteLegale'];
            $adresseunite = $request_result['etablissement']['adresseEtablissement']['numeroVoieEtablissement'].', '.$request_result['etablissement']['adresseEtablissement']['typeVoieEtablissement'].' '.$request_result['etablissement']['adresseEtablissement']['libelleVoieEtablissement'].' '.$request_result['etablissement']['adresseEtablissement']['libelleCommuneEtablissement'];
			if ($request_result['etablissement']['uniteLegale']['denominationUniteLegale']){
                $nomunite = $request_result['etablissement']['uniteLegale']['denominationUniteLegale'];
            }
            $formresult ='
            <div class="container-fluid">
                <div class="row">
					<div class="colonnesiret">
			<h4 class="champssiret">Siret:</h4>
			<p class="contenuchampssiret">'.$request_result['etablissement']['siret'].'</p>
		</div>
                    <div class="colonnedemi">
					<h4 class="titrechamps">Nom de l\'unité légale:</h4>
			     <p class="contenuchamps">'.$nomunite.'</p>
                 <h4 class="titrechamps">Adresse:</h4>
			<p class="contenuchamps">'.$adresseunite.'</p>
			<h4 class="titrechamps">Code Postal:</h4>
			<p class="contenuchamps">'.$request_result['etablissement']['adresseEtablissement']['codePostalEtablissement'].'</p>
		</div>
	</div>
    <div class="row">
		<div class="colonne2">
			<h4 class="text-center text-fail">L\'unité légale avec le Siret '.$request_result['etablissement']['siret'].' n\'est pas conforme RGPD.</h4>
            <p class="text-center">Vous êtes le dirigeant, le responsable ou le DPO? Procédez à la vérification maintenant!</p>
            <center><button type="button" id="formButton">Procédez à la vérification</button></center>
		</div>
	</div>
    <style>
         .form-horizontal{
         display:none;
         }
         p input {
          border: none;
          display: inline;
          font-family: inherit;
          font-size: inherit;
          padding: none;
          width: auto;
        }
        #wrapper-signature {
          position: relative;
          width: 400px;
          height: 200px;
          -moz-user-select: none;
          -webkit-user-select: none;
          -ms-user-select: none;
          user-select: none;
		  margin: auto;
		  font-family: \'hellicopter\';
		  font-size: 60pt;
        }
 </style>
 <script>
    jQuery(document).ready(function($){
        $("#formButton").click(function(){
            $("#formver").toggle();
        });
    });
 </script>
<form class="form-horizontal" id="formver" method="post" action="/'.$validationURL[0].'">
    <fieldset>
        <p style="font-family: times; font-weight: bold; font-size: 13pt; text-align: center">Attestation de Conformité au nouveau Règlement General UE 2016 / 679 du 27 avril<br>2016 sur la Protection des Données RGPD<br>et applicable depuis le 25 mai 2018</p>
        <p style="font-family: times; font-style: italic; text-align: center">(A remplir par le dirigeant et le cas échéant, par le cabinet de conformité en charge de la tenue du Registre des traitements de données)</p>
        <br>
        <p style="font-family: times; font-size: 11pt">Conformément au Règlement de l’Union Européenne N° 2016/679 du Parlement européen et du Conseil du 27 avril 2016 relatif à la protection des personnes physiques à l’égard du traitement des données à caractère personnel et à la libre circulation de ces données abrogeant la directive 95/46/CE et a la Loi n°78-17 du 6 janvier 1978 relative à l’informatique, aux fichiers et aux libertés dans sa dernière version,<br>
        Je soussigné(e) <input id="input-dirigeant" name="input-dirigeant" type="text" placeholder="Nom Dirigeant" class="form-control" required><input id="input-email" name="input-email" type="email" placeholder="Adresse Email" class="form-control" required><input id="input-tel" name="input-tel" type="text" placeholder="Numéro Téléphone" class="form-control" required>, agissant en qualité de  <select name="radios-role" id="radios-role"><option value="chef">Chef de l\'établissement</option><option value="responsable">Responsable des traitements</option></select> de l’entreprise «<input id="input-unite" name="input-unite" type="text" value="'.$nomunite.'"class="form-control input-md" readonly>», inscrite au Registre du Commerce des Sociétés de: <input id="input-ville" name="input-ville" type="text" value="'.$ville.'" class="form-control input-md" required><br>Situé au : <input id="input-adresse" name="input-adresse" type="text" placeholder="" value="'.$adresseunite.'"class="form-control input-md" readonly><br>Numéro Siret : <input id="input-siret" name="input-siret" type="text" placeholder="" value="'.$request_result['etablissement']['siret'].'"class="form-control input-md" readonly></p>
        <br>
        <p style="font-family: times; font-size: 11pt">Atteste sur l’honneur que :</p>
        <ul style="font-family: times; font-size: 11pt">
        <li>Les Directives RGPD sont appliquées en intégralité́ sur l’ensemble des données traitées au sein de l’entreprise, sans exception, depuis le 25.05.2018 à 08h00.</li>
        <li>L’entreprise collecte le consentement explicite à recevoir les communications publicitaires par email ou via l’exploitation des cookies, les personnes constituant les bases de données qu’elle collecte et/ou qu’elle administre dans le cadre de son activité.</li>
        <li>Les données collectées sont indispensables à la bonne exécution de l’activité de la société.</li>
        <li>L’entreprise ne collecte et ne stocke aucune donnée sensible (origines raciales ou ethniques, les opinions politiques, philosophiques ou religieuses ou l’appartenance syndicale des personnes, ou qui sont relatives à leur santé ou à leur vie sexuelle et que nous n’établissons aucun traitement automatique sur les données qui permettrait de faire une déduction, une estimation, même fausse, sur ce type d’informations);</li>
        <li>L’entreprise conserve les données personnelles pendant 5 années. À la fin de cette période, les données sont purement et simplement effacées.</li>
        <li>L’entreprise a mis en œuvre une procédure de confirmation de consentement explicite par le biais d’un interstitiel qui s’affiche à toutes les personnes qui cliquent sur un lien de campagne emailing, et ce, jusqu’à l’obtention d’un consentement renouvelé ou annulé par l’utilisateur.</li>
        <li>Les utilisateurs des adresses email exploitées par la société «'.$nomunite.'» disposent d’un accès de rectification, de téléchargement, de suppression et de portabilité de l’ensemble des données leur concernant.</li>
        <li>La société «'.$nomunite.'» a mis en ligne et à la disposition des autorités habilitées et compétentes, un Registre de l’ensemble des bases de données hébergées sur nos serveurs, ainsi que le type de données collectées, conservées, leur durée de conservation, de mise à jour.</li>
        <li>La société «'.$nomunite.'» effectue régulièrement des audits de ses systèmes d’information et de son infrastructure technique.</li>
        <li>La société «'.$nomunite.'» applique l’ensemble des accès permettant une consultation des données personnelles, des modifications de mots de passe et de clés d’accès SSH.</li>
		<li><input id="input-enregistrement" name="input-enregistrement" type="text" placeholder="Numéro d\'enregistrement" class="form-control input-md"></li>
        <li>Un DPO a été désigné, qui est : <input id="input-dpo" name="input-dpo" type="text" placeholder="Nom du DPO" class="form-control input-md" required></li>
        <li>Une politique de Respect de la Vie Privée est rédigée et soumise à l’approbation des abonnés à l’une des bases de données que la société exploite.</li>
        <li>La société limite l’accès aux données à caractère personnel aux collaborateurs pour lesquels ces accès sont indispensables dans le cadre de l’exécution de leur travail.</li>
        <li>Les données collectées sont traitées uniquement au sein de l’Union Européenne.</li> 
        <li>La société «'.$nomunite.'» a transmis à l’ensemble des sous-traitants et prestataires une demande de confirmation de conformité RGPD.</li>
        <li>Les accès physiques aux serveurs au sein desquels sont stockées les données à caractère personnel sont parfaitement sécurisés.</li>
        <li>Les utilisateurs de la plateforme de la société n’ont, en aucune façon, un accès direct aux données qui sont hébergées sur les serveurs de la société.</li>
        <li>La société a mis en place une procédure de signalement à la CNIL et aux partenaires concernés, d’une éventuelle situation de crise, intrusion, menace, ou tout autre événement susceptible de porter atteinte à l’intégrité et à la sécurité des données collectées, traitées et hébergées par les serveurs de l’entreprise.</li> 
        <li>Les détails concernant le processus mis en place sont disponibles dans la page « Conditions Générales d’Utilisation » accessible en ligne depuis le site de la société (Si concernée)</li></ul>
        <br>
        <p style="font-family: times; font-size: 11pt">Je soussigne(e) "NOM DU DIRIGEANT/RESPONSABLE", "ROLE" de «'.$nomunite.'», atteste par la présente que l’ensemble des dispositions légales décrites dans la présente attestation sont entièrement appliquées, atteste également que cette présente attestation est rédigée de façon claire, explicite et concise.<br>Fait à "VILLE" le '.date('d/m/Y').', Pour faire valoir ce que de droit</p>
        <br>
        <p style="font-family: times; font-size: 11pt">Nom: NOM DU DIRIGEANT/RESPONSABLE</p>
        <p style="font-family: times; font-size: 11pt">Signature</p>
        <div id="wrapper-signature">
            </div>
            <br>
            <p style="font-family: times; font-size: 9pt"><u>Article 441-1 du code pénal</u><br>Constitue un faux toute altération frauduleuse de la vérité, de nature à causer un préjudice et accomplie par quelque moyen que ce soit, dans un écrit ou tout autre support d’expression de la pensée qui a pour objet ou qui peut avoir pour effet d’établir la preuve d’un droit ou d’un fait ayant des conséquences juridiques.<br>
            Le faux et l’usage de faux sont punis de trois ans d’emprisonnement et de 45000 euros d’amende.</p>
            <p style="font-family: times; font-size: 9pt"><u>Article 441-7 du code pénal</u><br>Est puni d’un an d’emprisonnement et de 15 000 euros d’amende le fait :<br>
            1° D’établir une attestation ou un certificat faisant état de faits matériellement inexacts ;<br>
            2° De falsifier une attestation ou un certificat originairement sincère ;<br>
            3° De faire usage d’une attestation ou d’un certificat inexact ou falsifié.<br>
            Les peines sont portées à trois ans d’emprisonnement et à 45 000 euros d’amende lorsque l’infraction est commise en vue de porter préjudice au Trésor public ou au patrimoine d’autrui.</p>
            <input type="hidden" id="input-ip" name="input-ip" value="'.$_SERVER["REMOTE_ADDR"].'">
			<input type="hidden" id="input-date" name="input-date" value="'.date("d-m-Y").'">
			<input type="hidden" id="input-heure" name="input-heure" value="'.date("H:i:s").'">
            <input type="hidden" id="input-codepostal" name="input-codepostal" value="'.$codePostal.'">
            <div class="form-group">
              <div class="col-md-4">
                <button type ="submit" id="buttonverifier" name="buttonverifier" class="btn btn-primary">Valider l\'attestation</button>
              </div>
            </div>
    </fieldset>
</form>
    <script>
      var inputBox = document.getElementById("input-dirigeant");
	  inputBox.onkeyup = function(){
		   document.getElementById("wrapper-signature").innerHTML = inputBox.value;
	  }
     </script>';
        } else {
     $formresult = '
         <div class="row">
            <div class="colonne2">
                <h4 class="text-center text-fail">Une erreur est survenu lors de la génération des informations requises.</h4>
                <p class="text-center">Le serveur distant peut être inaccessible ou le Siret n\'existe pas</p>
            </div>
         </div>';
        }
        print($formresult);
	}
}
}