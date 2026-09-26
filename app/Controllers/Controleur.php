<?php
//acces au controller parent pour l heritage
namespace App\Controllers;
use CodeIgniter\Controller;

//=========================================================================================
//définition d'une classe Controleur (meme nom que votre fichier Controleur.php) 
//héritée de Controller et permettant d'utiliser les raccoucis et fonctions de CodeIgniter
//  Attention vos Fichiers et Classes Controleur et Modele doit commencer par une Majuscule 
//  et suivre par des minuscules
//=========================================================================================

class Controleur extends BaseController {

//=====================================================================
//Fonction index correspondant au Controleur frontal (ou index.php) en MVC libre
//=====================================================================
public function index(){
if (isset($_POST["action"]) && $_POST["action"] === "add_hors_forfait") {
    $this->majFraisHorsForfait(
        $_POST["date"],
        $_POST["libelle"],
        $_POST["montant"]
    );
}

if (isset($_POST["KM"])){
	$this->majFraisForfait($_POST["ETP"],$_POST["KM"],$_POST["NUI"],$_POST["REP"]);
}

elseif (isset($_GET["action"]) && $_GET["action"] == "logout") {
    $this->logout();
	}

elseif (isset($_GET["action"]) && $_GET["action"] == "expence") {
    $this->expence();
}


elseif (isset($_POST["username"])){
	$this->connectionlogin($_POST["username"],$_POST["password"]);
}

elseif (isset($_GET["action"])&& $_GET["action"]=="connection" ){
		$this->connection();
		}
elseif (isset($_GET["action"])&& $_GET["action"]=="apropos" ){
		$this->apropos();
		}
elseif (isset($_GET["action"])&& $_GET["action"]=="contact" ){
		$this->contact();
		}
elseif (isset($_GET["action"])&& $_GET["action"]=="historique" ){
		$this->historique();
		}
elseif (isset($_GET["action"])&& $_GET["action"]=="legalnotice" ){
		$this->legalnotice();
		}
elseif (isset($_GET["action"])&& $_GET["action"]=="search" ){
		$this->search();
		}
		else{
			$this->accueil();
		}
		
		//=========================
		//fin du controleur frontal
		//=========================
}


//======================================================
// Code du controleur simple (ex fichier Controleur.php)
//======================================================

// Action 1 : Affiche la page Index
public function accueil() {
		echo view('vueIndex');
}

// Action 2 : Affiche la page Connection
public function connection() {
		echo view('vueConnection');
}

// Action 3 : Affiche la page A Propos
public function apropos() {
		echo view('vueAPropos');
}

// Action 4 : Affiche la page Contact
public function contact() {
		echo view('vueContact');
}

// Action 5 : Affiche la page Historique
public function historique() {
		echo view('vueHistorique');
}

// Action 6 : Affiche la page Legal Notice
public function legalnotice() {
		echo view('vueLegalNotice');
}

// Action 7 : Affiche la page Search
public function search() {
		echo view('vueSearch');
}

public function expence() {
		echo view('vueExpence');
}


// Action 8 : Accès à la page login et permet de renter les identifiant et mots de passe en vérifiant $login,$password
public function connectionlogin($login,$password) {
		//================
		//acces au modele
		//================
		$Modele = new \App\Models\Modele();
		
		//===============================
		//Appel d'une fonction du Modele
		//===============================	
		$donnees = $Modele->getVisiteur($login,$password);
		if (isset($donnees[0]->id)){
			if (!session_id()){session_start();}
			$_SESSION["user1"]=($donnees[0]->id);
			$_SESSION["name"]=($donnees[0]->nom);
			$_SESSION["surname"]=($donnees[0]->prenom);
			//vérif fiche frais
			$donnees2 = $Modele->getFrais($_SESSION["user1"],date('Ym'));
			if (!isset($donnees2[0]->idVisiteur)){
				$Modele->insertFicheFrai($_SESSION["user1"],date('Ym'),date('Y-m-d'));
				$Modele->insertFraisForfait($_SESSION["user1"],date('Ym'),"ETP",0,0);
				$Modele->insertFraisForfait($_SESSION["user1"],date('Ym'),"KM",0,0);
				$Modele->insertFraisForfait($_SESSION["user1"],date('Ym'),"NUI",0,0);
				$Modele->insertFraisForfait($_SESSION["user1"],date('Ym'),"REP",0,0);
			}
			$data['ETP'] = $Modele->getFraisForfat($_SESSION["user1"],date('Ym'),"ETP");
			$data['KM'] = $Modele->getFraisForfat($_SESSION["user1"],date('Ym'),"KM");
			$data['NUI'] = $Modele->getFraisForfat($_SESSION["user1"],date('Ym'),"NUI");
			$data['REP'] = $Modele->getFraisForfat($_SESSION["user1"],date('Ym'),"REP");
			$data['activeTab']='new-expense';
			echo view('vueExpence',$data);
		}
		else{
			echo view('vueConnection');
		}
  
}

public function majFraisForfait($ETP,$KM,$NUI,$REP){

	$Modele = new \App\Models\Modele();
	if (!session_id()){session_start();}

	$Modele->insertFraisForfait($_SESSION["user1"],date('Ym'),"ETP",$ETP,$ETP);
	$Modele->insertFraisForfait($_SESSION["user1"],date('Ym'),"KM",$KM,$KM);
	$Modele->insertFraisForfait($_SESSION["user1"],date('Ym'),"NUI",$NUI,$NUI);
	$Modele->insertFraisForfait($_SESSION["user1"],date('Ym'),"REP",$REP,$REP);

	$data['ETP'] = $Modele->getFraisForfat($_SESSION["user1"],date('Ym'),"ETP");
	$data['KM'] = $Modele->getFraisForfat($_SESSION["user1"],date('Ym'),"KM");
	$data['NUI'] = $Modele->getFraisForfat($_SESSION["user1"],date('Ym'),"NUI");
	$data['REP'] = $Modele->getFraisForfat($_SESSION["user1"],date('Ym'),"REP");

	$data['activeTab']='new-expense';
	echo view('vueExpence',$data);

}

public function logout()
{
    if (!session_id()) {
        session_start();
    }

    // Détruire toutes les données de session
    $_SESSION = [];

    // Détruire la session
    session_destroy();

    // Afficher la page de connexion
    echo view('vueConnection');
}



// public function majFraisHorsForfait($date, $libelle, $montant){

// 	$Modele = new \App\Models\Modele();
// 	if (!session_id()){session_start();}

// 	$Modele->insertFraisHorsForfait($_SESSION["user1"],date('Ym'),$date, $libelle, $montant)

// 	$data['activeTab']='new-expense';
// 	echo view('vueExpence',$data);
// }

// Affiche une erreur
public function erreur($msgErreur) {
  echo view('vueErreur.php', $data);
}

//==========================
//Fin du code du controleur simple
//===========================

//fin de la classe
}



?>