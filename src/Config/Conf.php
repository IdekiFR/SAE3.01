<?php
namespace App\Naturotheque\Config;

class Conf {

    static private array $databases = array(
        // Le nom d'hote est localhost sur votre machine
        'hostname' => 'localhost',
        // Sur votre machine, vous devrez creer une BDD
        'database' => 'sae301',
        // Sur votre machine, vous avez surement un compte 'root'
        'login' => 'root',
        // Sur votre machine, vous avez créé ou non ce mdp a l'installation
        'password' => 'root'
    );

    static private float $dureeExpiration = 1800;

    static public function getLogin() : string {
        // L'attribut statique $databases s'obtient
        // avec la syntaxe static::$databases
        // au lieu de $this->databases pour un attribut non statique
        return static::$databases['login'];
    }

    static public function getHostname() : string {
        return static::$databases["hostname"];
    }

    static public function getDatabase() : string {
        return static::$databases["database"];
    }

    static public function getPassword() : string {
        return static::$databases["password"];
    }

    static public function getDureeExpiration() : float{
        return static::$dureeExpiration;
    }

}
?>
