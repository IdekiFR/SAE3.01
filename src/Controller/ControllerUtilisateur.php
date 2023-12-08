<?php

namespace App\Naturotheque\Controller;

use App\Naturotheque\Lib\ConnexionUtilisateur;
use App\Naturotheque\Lib\MessageFlash;
use App\Naturotheque\Lib\MotDePasse;
use App\Naturotheque\Model\DataObject\Utilisateur;
use App\Naturotheque\Model\Repository\DatabaseConnection;
use App\Naturotheque\Model\Repository\UtilisateurRepository;
use TypeError;
use PDOException;


class ControllerUtilisateur{

    // Méthode qui redirige vers le formulaire de connexion
    public static function connection() : void {
        ControllerUtilisateur::afficheVue("view.php" , [ "pagetitle" => "Formulaire de connexion",
                                                                    "style" => "Utilisateur",
                                                                    "cheminVueBody" => "utilisateur/formulaire_connexion.php"]);
    }

    public static function connecter() : void{
        if (isset($_POST["email"]) && isset($_POST["password"])){
            $utilisateur = (new UtilisateurRepository())->select($_POST["email"]);

            if (isset($utilisateur)){
                if (MotDePasse::verifier($_POST["password"],$utilisateur->get("password"))) {
                    ConnexionUtilisateur::connecter($utilisateur->get("email"));
                    MessageFlash::ajouter("success" , "Vous vous êtes connecté .");    // marche pas
                    header("Location: frontcontroller.php");
                }else{
                    MessageFlash::ajouter("warning" , "Mdp incorrect");
                    self::connection();
                }
            }else{
                MessageFlash::ajouter("warning" , "Login incorrect");
                self::connection();
            }
        }else{
            MessageFlash::ajouter("danger" , "Login and/or mdp non define !");
            self::connection();
        }
    }

    // Méthode qui redirige vers le formulaire d'inscription
    public static function register() : void {
        ControllerUtilisateur::afficheVue("view.php" , [ "pagetitle" => "Formulaire d'inscription",
                                                                    "style" => "Utilisateur",
                                                                    "cheminVueBody" => "utilisateur/formulaire_inscription.php"]);
    }

    public static function registered(array $utilisateurFormatTableau):void{
        try{
            if (!($_GET["password"] == $_GET["password2"])){
                MessageFlash::ajouter("warning" , "Mot de passe incompatible !");
                ControllerUtilisateur::register();
            }else {
                $utilisateur = Utilisateur::construireDepuisFormulaire($utilisateurFormatTableau);
            }

            if (UtilisateurRepository::missing_value($utilisateur)){
                throw new TypeError();
            }

            (new UtilisateurRepository())->sauvegarder($utilisateur);
            MessageFlash::ajouter("success" , "La utilisateur a bien été crée ! Connectez vous");
            ControllerUtilisateur::connection();
        }catch (PDOException $e){
            echo $e;
            MessageFlash::ajouter("warning" , "Login déjà existante !");
            ControllerUtilisateur::register();
        }catch (TypeError $e){
            MessageFlash::ajouter("danger" , "Nom, prénom, email ou mdp manquant!");
            ControllerUtilisateur::register();
        }
    }


    public static function getUtilisateurConnecte(): void{
        $users = UtilisateurRepository::getUtilisateurConnecte();
        ControllerUtilisateur::afficheVue("view.php" , [ "pagetitle" => "Formulaire d'inscription",
                                                                    "pagetitle" => "Page Naturotheque",
                                                                    "style" => "Naturotheque",
                                                                    "cheminVueBody" => "naturotheque/naturotheque.php",
                                                                    "users" => $users,]);

    }


    // Méthode qui permet d'afficher la vue avec son chemin et ses parametres
    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        if(!is_null(MessageFlash::lireTousMessages()) ){
            if (sizeof(MessageFlash::lireTousMessages()) > 0) {
                $message = MessageFlash::lireMessages(array_key_first(MessageFlash::lireTousMessages()));
            }
        }
        require __DIR__ . "/../view/$cheminVue";
    }

    public static function error(string $errorMessage = ""){
        self::afficheVue("view.php" , [ "pagetitle" => "Action incorrect",
            "cheminVueBody" => "error.php",
            "errorMessage" => $errorMessage]);
    }

}

?>