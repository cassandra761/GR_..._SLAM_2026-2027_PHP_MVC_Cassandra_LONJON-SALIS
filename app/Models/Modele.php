<?php
//acces au Modele parent pour l heritage
namespace App\Models;
use CodeIgniter\Model;

//=========================================================================================
//définition d'une classe Modele (meme nom que votre fichier Modele.php) 
//héritée de Modele et permettant d'utiliser les raccoucis et fonctions de CodeIgniter
//  Attention vos Fichiers et Classes Controleur et Modele doit commencer par une Majuscule 
//  et suivre par des minuscules
//=========================================================================================
class Modele extends Model {

//==========================
// Code du modele
//==========================

//=========================================================================
// Fonction 1
// récupère les données BDD dans une fonction getVisiteur
//=========================================================================
public function getVisiteur($login,$mdp) { 

//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
	$db = db_connect();
    $db->table('visiteurs');

//=============================
// rédaction de la requete sql
//=============================
    $sql = 'SELECT id, nom, prenom FROM gsbV2.visiteur WHERE login = ? AND mdp = ?';
	
//=============================
// execution de la requete sql
//=============================	
    $resultat = $db->query($sql,[$login,$mdp]);

//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================	
    return $resultat;
   
}


//=========================================================================
// Fonction 2 
// récupère les données BDD dans une fonction getFrais
//=========================================================================
public function getFrais($idVisiteur,$mois) {
	
//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
    $db = db_connect();	
	
//=====================================
// rédaction de la requete sql préparée
//=====================================
	$sql = 'SELECT * FROM FicheFrais WHERE idVisiteur = ? AND mois = ?';
	
//=====================================================
// execution de la requete sql en passant un parametre id
//=====================================================	
    $resultat = $db->query($sql, [$idVisiteur,$mois]);
	
//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================		
    return $resultat;
  
}

//=========================================================================
// Fonction 3 
// récupère les données BDD dans une fonction getQuantites
//=========================================================================
public function getQuantites($idVisiteur,$mois) {
	
//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
    $db = db_connect();	
	
//=====================================
// rédaction de la requete sql préparée
//=====================================
	$sql = 'SELECT SUM(l.quantite * f.montant) as total
                FROM LigneFraisForfait l
                JOIN FraisForfait f ON l.idFraisForfait = f.id
                WHERE l.idVisiteur = ? AND l.mois = ?';
	
//=====================================================
// execution de la requete sql en passant un parametre id
//=====================================================	
    $resultat = $db->query($sql, [$idVisiteur,$mois]);
	
//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================		
    return $resultat;
  
}

//=========================================================================
// Fonction 4 
// récupère les données BDD dans une fonction getMontant
//=========================================================================
public function getMontant($idVisiteur,$mois) {
	
//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
    $db = db_connect();	
	
//=====================================
// rédaction de la requete sql préparée
//=====================================
	$sql = 'SELECT SUM(montant) 
                FROM LigneFraisHorsForfait
                WHERE idVisiteur = ? AND mois = ?';
	
//=====================================================
// execution de la requete sql en passant un parametre id
//=====================================================	
    $resultat = $db->query($sql, [$idVisiteur,$mois]);
	
//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================		
    return $resultat;
  
}

//=========================================================================
// Fonction 4 
// récupère les données BDD dans une fonction insertFicheFrai
//=========================================================================
public function insertFicheFrai($idVisiteur,$mois,$dateModif) {
	
//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
    $db = db_connect();	
	
//=====================================
// rédaction de la requete sql préparée
//=====================================
	$sql = 'INSERT INTO FicheFrais (idVisiteur, mois, nbJustificatif, montantValide, dateModif, idEtat) VALUES (?, ?, 0, 0.00, ?, "CR")';
	
//=====================================================
// execution de la requete sql en passant un parametre id
//=====================================================	
    $resultat = $db->query($sql, [$idVisiteur,$mois,$dateModif]);
	
//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================		
    return $resultat;
  
}

//=========================================================================
// Fonction 5 
// récupère les données BDD dans une fonction getHorsFrais
//=========================================================================
public function getHorsFrais($idVisiteur) {
	
//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
    $db = db_connect();	
	
//=====================================
// rédaction de la requete sql préparée
//=====================================
	$sql = 'SELECT * FROM FicheFrais WHERE idVisiteur = ? ORDER BY mois DESC
                SELECT date, libelle, montant 
                FROM LigneFraisHorsForfait 
                WHERE idVisiteur = ?
                ORDER BY date DESC';
	
//=====================================================
// execution de la requete sql en passant un parametre id
//=====================================================	
    $resultat = $db->query($sql, [$idVisiteur]);
	
//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================		
    return $resultat;
  
}

//=========================================================================
// Fonction 6 
// récupère les données BDD dans une fonction insertFraisForfait
//=========================================================================
public function insertFraisForfait($idVisiteur,$mois,$dateModif,$quantite,$quantites) {
	
//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
    $db = db_connect();	
	
//=====================================
// rédaction de la requete sql préparée
//=====================================
	$sql = 'INSERT INTO LigneFraisForfait (idVisiteur, mois, idFraisForfait, quantite) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE quantite = ?';
	
//=====================================================
// execution de la requete sql en passant un parametre id
//=====================================================	
    $resultat = $db->query($sql, [$idVisiteur,$mois,$dateModif,$quantite,$quantites]);
	
//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================		
    return $resultat;
  
}

//=========================================================================
// Fonction 7 
// récupère les données BDD dans une fonction updateFicheFrais
//=========================================================================
public function updateFicheFrais($dateModif,$idVisiteur,$mois) {
	
//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
    $db = db_connect();	
	
//=====================================
// rédaction de la requete sql préparée
//=====================================
	$sql = 'UPDATE FicheFrais SET dateModif = ?, nbJustificatif = nbJustificatif + 1 WHERE idVisiteur = ? AND mois = ?';
	
//=====================================================
// execution de la requete sql en passant un parametre id
//=====================================================	
    $resultat = $db->query($sql, [$dateModif,$idVisiteur,$mois]);
	
//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================		
    return $resultat;
  
}

//=========================================================================
// Fonction 8 
// récupère les données BDD dans une fonction updateMontant
//=========================================================================
public function updateMontant($montant,$idVisiteur,$mois) {
	
//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
    $db = db_connect();	
	
//=====================================
// rédaction de la requete sql préparée
//=====================================
	$sql = 'UPDATE FicheFrais 
                SET montantValide = ?
                WHERE idVisiteur = ? AND mois = ?';
	
//=====================================================
// execution de la requete sql en passant un parametre id
//=====================================================	
    $resultat = $db->query($sql, [$montant,$idVisiteur,$mois]);
	
//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================		
    return $resultat;
  
}

//=========================================================================
// Fonction 9 
// récupère les données BDD dans une fonction insertFicheHorsFrais
//=========================================================================
public function insertFicheHorsFrais($idVisiteur,$mois,$libelle,$date,$montant) {
	
//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
    $db = db_connect();	
	
//=====================================
// rédaction de la requete sql préparée
//=====================================
	$sql = 'INSERT INTO LigneFraisHorsForfait (idVisiteur, mois, libelle, date, montant) VALUES (?, ?, ?, ?, ?)';
	
//=====================================================
// execution de la requete sql en passant un parametre id
//=====================================================	
    $resultat = $db->query($sql, [$idVisiteur,$mois,$libelle,$date,$montant]);
	
//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================		
    return $resultat;
  
}

//=========================================================================
// Fonction 10 
// récupère les données BDD dans une fonction updateJustificatif
//=========================================================================
public function updateJustificatif($dateModif,$idVisiteur,$mois) {
	
//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
    $db = db_connect();	
	
//=====================================
// rédaction de la requete sql préparée
//=====================================
	$sql = 'UPDATE FicheFrais SET dateModif = ?, nbJustificatif = nbJustificatif + 1 WHERE idVisiteur = ? AND mois = ?';
	
//=====================================================
// execution de la requete sql en passant un parametre id
//=====================================================	
    $resultat = $db->query($sql, [$dateModif,$idVisiteur,$mois]);
	
//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================		
    return $resultat;
  
}

//=========================================================================
// Fonction 11 
// récupère les données BDD dans une fonction updateMontantValide
//=========================================================================
public function updateMontantValide($montant,$idVisiteur,$mois) {
	
//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
    $db = db_connect();	
	
//=====================================
// rédaction de la requete sql préparée
//=====================================
	$sql = 'UPDATE FicheFrais 
                SET montantValide = ?
                WHERE idVisiteur = ? AND mois = ?';
	
//=====================================================
// execution de la requete sql en passant un parametre id
//=====================================================	
    $resultat = $db->query($sql, [$montant,$idVisiteur,$mois]);
	
//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================		
    return $resultat;
  
}

//==========================
// Fin Code du modele
//===========================


//fin de la classe
}


?>