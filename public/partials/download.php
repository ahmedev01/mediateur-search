<?php
            if(isset($_POST['radios-role'])){
            if($_POST['radios-role'] == 'chef'){$role='Chef de l\'établissement';}else{$role='Responsable des traitements';}
            //begin generate pdf & send mail
            require_once('tcpdf.php');
			//$signaturefont = $pdf->addTTFfont(__DIR__.'/fonts/hellicopter-webfont.ttf', 'TrueType','', 32);
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('CCFE');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            if (@file_exists(dirname(__FILE__).'/lang/fra.php')) {
	        require_once(dirname(__FILE__).'/lang/fra.php');
	        $pdf->setLanguageArray($l);
            }
            $pdf->SetFont('times', '', 11);
            $pdf->AddPage();
            $html='<p style="font-weight: bold; font-size: 13pt; text-align: center">Attestation de Conformité au nouveau Règlement General UE 2016 / 679 du 27 avril<br>2016 sur la Protection des Données RGPD<br>et applicable depuis le 25 mai 2018</p>
            <p style="font-style: italic; text-align: center">(A remplir par le dirigeant et le cas échéant, par le cabinet de conformité en charge de la tenue du Registre des traitements de données)</p>
            <p>Conformément au Règlement de l’Union Européenne N° 2016/679 du Parlement européen et du Conseil du 27 avril 2016 relatif à la protection des personnes physiques à l’égard du traitement des données à caractère personnel et à la libre circulation de ces données abrogeant la directive 95/46/CE et a la Loi n°78-17 du 6 janvier 1978 relative à l’informatique, aux fichiers et aux libertés dans sa dernière version,<br>
            Je soussigne(e) '.$_POST['input-dirigeant'].', agissant en qualité de '.$role.' de l’entreprise «'.$_POST['input-unite'].'», inscrite au Registre du Commerce des Sociétés du Code Postal '.$_POST['input-ville'].'<br>Situé au : '.$_POST['input-adresse'].'<br>Numéro Siret : '.$_POST['input-siret'].'</p>
            <br>
            <p>Atteste sur l’honneur que :</p>
            <ul>
            <li>Les Directives RGPD sont appliquées en intégralité́ sur l’ensemble des données traitées au sein de l’entreprise, sans exception, depuis le 25.05.2018 à 08h00.</li>
            <li>L’entreprise collecte le consentement explicite à recevoir les communications publicitaires par email ou via l’exploitation des cookies, les personnes constituant les bases de données qu’elle collecte et/ou qu’elle administre dans le cadre de son activité.</li>
            <li>Les données collectées sont indispensables à la bonne exécution de l’activité de la société.</li>
            <li>L’entreprise ne collecte et ne stocke aucune donnée sensible (origines raciales ou ethniques, les opinions politiques, philosophiques ou religieuses ou l’appartenance syndicale des personnes, ou qui sont relatives à leur santé ou à leur vie sexuelle et que nous n’établissons aucun traitement automatique sur les données qui permettrait de faire une déduction, une estimation, même fausse, sur ce type d’informations);</li>
            <li>L’entreprise conserve les données personnelles pendant 5 années. À la fin de cette période, les données sont purement et simplement effacées.</li>
            <li>L’entreprise a mis en œuvre une procédure de confirmation de consentement explicite par le biais d’un interstitiel qui s’affiche à toutes les personnes qui cliquent sur un lien de campagne emailing, et ce, jusqu’à l’obtention d’un consentement renouvelé ou annulé par l’utilisateur.</li>
            <li>Les utilisateurs des adresses email exploitées par la société «'.$_POST['input-unite'].'» disposent d’un accès de rectification, de téléchargement, de suppression et de portabilité de l’ensemble des données leur concernant.</li>
            <li>La société «'.$_POST['input-unite'].'» a mis en ligne et à la disposition des autorités habilitées et compétentes, un Registre de l’ensemble des bases de données hébergées sur nos serveurs, ainsi que le type de données collectées, conservées, leur durée de conservation, de mise à jour.</li>
            <li>La société «'.$_POST['input-unite'].'» effectue régulièrement des audits de ses systèmes d’information et de son infrastructure technique.</li>
            <li>La société «'.$_POST['input-unite'].'» applique l’ensemble des accès permettant une consultation des données personnelles, des modifications de mots de passe et de clés d’accès SSH.</li>  
			<li>Le numéro d\'enregistrement (si existant):'.$_POST['input-enregistrement'].'</li>
            <li>Un DPO a été désigné, qui est : '.$_POST['input-dpo'].'</li>
            <li>Une politique de Respect de la Vie Privée est rédigée et soumise à l’approbation des abonnés à l’une des bases de données que la société exploite.</li>
            <li>La société limite l’accès aux données à caractère personnel aux collaborateurs pour lesquels ces accès sont indispensables dans le cadre de l’exécution de leur travail.</li>
            <li>Les données collectées sont traitées uniquement au sein de l’Union Européenne.</li> 
            <li>La société «'.$_POST['input-unite'].'» a transmis à l’ensemble des sous-traitants et prestataires une demande de confirmation de conformité RGPD.</li>
            <li>Les accès physiques aux serveurs au sein desquels sont stockées les données à caractère personnel sont parfaitement sécurisés.</li>
            <li>Les utilisateurs de la plateforme de la société n’ont, en aucune façon, un accès direct aux données qui sont hébergées sur les serveurs de la société.</li>
            <li>La société a mis en place une procédure de signalement à la CNIL et aux partenaires concernés, d’une éventuelle situation de crise, intrusion, menace, ou tout autre événement susceptible de porter atteinte à l’intégrité et à la sécurité des données collectées, traitées et hébergées par les serveurs de l’entreprise.</li> 
            <li>Les détails concernant le processus mis en place sont disponibles dans la page « Conditions Générales d’Utilisation » accessible en ligne depuis le site de la société (Si concernée)</li></ul>
            <br>
            <p>Je soussigne(e) '.$_POST['input-dirigeant'].', <?php echo $role;?> de «'.$_POST['input-unite'].'», atteste par la présente que l’ensemble des dispositions légales décrites dans la présente attestation sont entièrement appliquées, atteste également que cette présente attestation est rédigée de façon claire, explicite et concise.<br>Fait à '.$_POST['input-ville'].' le '.date('d/m/Y').', Pour faire valoir ce que de droit</p>
            <br>
            <center><p>Nom: '.$_POST['input-dirigeant'].'</p>
            <center><p>Document signé électroniquement le <strong>'.$_POST['input-date'].'</strong> à <strong>'.$_POST['input-heure'].'</strong> à partir de l\'adresse IP <strong>'.$_POST['input-ip'].'</strong></p></center><p style="font-family: times; font-size: 9pt"><u>Article 441-1 du code pénal</u><br>Constitue un faux toute altération frauduleuse de la vérité, de nature à causer un préjudice et accomplie par quelque moyen que ce soit, dans un écrit ou tout autre support d’expression de la pensée qui a pour objet ou qui peut avoir pour effet d’établir la preuve d’un droit ou d’un fait ayant des conséquences juridiques.<br>
            Le faux et l’usage de faux sont punis de trois ans d’emprisonnement et de 45000 euros d’amende.</p>
            <p style="font-family: times; font-size: 9pt"><u>Article 441-7 du code pénal</u><br>Est puni d’un an d’emprisonnement et de 15 000 euros d’amende le fait :<br>
            1° D’établir une attestation ou un certificat faisant état de faits matériellement inexacts ;<br>
            2° De falsifier une attestation ou un certificat originairement sincère ;<br>
            3° De faire usage d’une attestation ou d’un certificat inexact ou falsifié.<br>
            Les peines sont portées à trois ans d’emprisonnement et à 45 000 euros d’amende lorsque l’infraction est commise en vue de porter préjudice au Trésor public ou au patrimoine d’autrui.</p>';
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Output(__DIR__.'/siret-files/siret-'.$_POST['input-siret'].'.pdf','F');
            
			echo $_POST['input-siret'];
            // send message
			 
			 
			 function sendmail(){
				 $file = __DIR__.'/siret-files/siret-'.$_POST['input-siret'].'.pdf';
                if (file_exists($file)) {
			//send mail
            $to = $_POST['input-email'].', contact@consommateur.eu';
            //sender
            $from = 'noreply@consommateur.eu';
            $fromName = 'Vérification CCFE';
            //email subject
            $subject = 'COMITE CONSOMMATEUR FRANCE EUROPE'; 
          
            //email body content

            $eol = PHP_EOL;
			
				  $attachment_name = 'siret-'.$_POST['input-siret'].'.pdf'; 
				  $fp = fopen($file, "r");
					$data = fread($fp, filesize($file));
					$data = chunk_split(base64_encode($data));
					fclose($fp);
				//Let's start our headers
				$headers = "From: $fromName<".$from.">".$eol;
				$headers .= "MIME-Version: 1.0".$eol;
				$headers .= "Content-Type: multipart/related; type=\"multipart/alternative\"; boundary=\"----=MIME_BOUNDRY_main_message\"".$eol;
				$headers .= "X-Sender: $fromName<".$from .">".$eol;
				$headers .= "X-Mailer: PHP7".$eol;
				$headers .= "X-Priority: 3".$eol;
				$headers .= "This is a multi-part message in MIME format.".$eol;
				$headers .= "------=MIME_BOUNDRY_main_message".$eol;
				$headers .= "Content-Type: multipart/alternative; boundary=\"----=MIME_BOUNDRY_message_parts\"".$eol;
				$message .= "------=MIME_BOUNDRY_message_parts".$eol;
				$message .= "Content-Type: text/html; charset=\"utf-8\"".$eol;
				$message .= "Content-Transfer-Encoding: quoted-printable".$eol;
				$message .= $eol;
$message .= "<!doctype html>
<html>
<head>
<title>COMITE CONSOMMATEUR FRANCE EUROPE</title>
</head>
<body>
<p>Bonjour,<p>
<p>Suite à votre enregistrement sur notre base de données participative de vérification des entreprises concernant leur conformité RGPD.<br/>
Vous trouverez ci-joint votre attestation.<br/>
Cette dernière sera transmise à notre équipe juridique qui jugera de la conformité ou non, il se pourrait a des fins de validation que des informations supplémentaires vous soient demandées.<br/>
Dès confirmation par nos équipes, votre statut sera changé sur la base de données participatives qui informera l'ensemble des consommateurs sur votre conformité RGPD.</p> 
<p>Nous restons à votre entière disposition, si vous avez besoin d'informations ou d'aides, vous pouvez nous contacter à l'adresse suivante juridique@consommateur.eu en mettant en objet votre numéro de SIRET.</p>
<p>Cordialement,<br/>
consommateur.eu TEAM</p>
<br/>
<p style='font-size:9px'>NB : Article 441-7 du code PENAL :<br/>
est puni d'un an d'emprisonnement et de 15 000€ d'amende le fait :<br/>
1 - d'établir une attestation ou un certificat faisant état de faits matériels inexacts;<br/>
2 - de falsifier une attestation ou un certificat originairement sincère;<br/>
3 - de faire usage d'une attestation inexacte ou falsifiée.<br/>
Les peines peuvent  êtres  portées à 3 ans d'emprisonnement et 45 000€ d'amende en cas de préjudices importants.</p>
<br/>
<img src=\"https://www.consommateur.eu/wp-content/uploads/2019/04/logodark.png\" alt=\"CCFE logo\" title=\"Logo\" style=\"display:block\" width=\"200\" height=\"67\"/><br/>
Courriel: contact@consommateur.eu<br/>
Site Web: www.consommateur.eu
<p style='font-size:9px'>Ce message électronique et toutes les pièces jointes sont strictement confidentiels et destinés uniquement au destinataire. Il peut contenir des informations qui sont couvertes par un privilège légal, professionnel ou d’une autre nature. Si vous n’êtes pas le destinataire désigné de ce document, vous devez immédiatement informer l’expéditeur à l’adresse ci-dessus et supprimer ce message électronique et toutes pièces jointes complètement de votre système informatique. Vous ne devez pas faire de copies ou révéler à quiconque le contenu de ce document, ni prendre de mesures fondées sur lui. Le courrier électronique est une méthode informelle de communication et est susceptible de subir une corruption possible des données. consommateur.eu n’est pas en mesure d’exercer un contrôle sur la teneur des informations contenues dans des transmissions effectuées par le biais de l’Internet. Les opinions, les conclusion s et autres informations exprimées dans ce message sont uniquement celles de l’auteur et ne sont pas données ou endossées par consommateur.eu, sauf indication contraire par un représentant indépendant autorisé de ce message. Il est de la responsabilité du destinataire de s’assurer que ce message et toutes les pièces jointes ne contiennent pas de virus et la responsabilité de consommateur.eu ne peut être en aucun cas engagée en raison de pertes ou dommages pouvant résulter de son utilisation.<br/>
L'équipe CCFE</p>
</body>
</html>";
				$message .= $eol;
				$message .= "------=MIME_BOUNDRY_message_parts--".$eol;
				$message .= $eol;
				$message .= "------=MIME_BOUNDRY_main_message".$eol; 
				$message .= "Content-Type: application/octet-stream;".$eol."\tname=\"" . $attachment_name . "\"".$eol;
				$message .= "Content-Transfer-Encoding: base64".$eol;
				$message .= "Content-Disposition: attachment;".$eol."\tfilename=\"" . $attachment_name . "\"".$eol."; size=".filesize($file).";".$eol.$eol;
				$message .= $data; //The base64 encoded message
				$message .= "------=MIME_BOUNDRY_main_message--".$eol; 
			 
				// send the message
				$mail = mail("$to", $subject, $message, $headers);
					//email sending status
				if($mail){
					header("Location:  ".$_POST['redirection']."?sent=1", true, 301);
					exit();
				}else{
					header("Location:  ".$_POST['redirection']."?sent=0", true, 301);
					exit();
				} }else {
					header("Location:  ".$_POST['redirection']."?sent=2", true, 301);
					exit();
                }
			 }
			 sendmail();
			}
            //end