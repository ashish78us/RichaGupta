<?php

namespace app\Helpers;

class Text
{

    /**
     * @param string $key
     * @param bool $firstcap
     * @param int $qty
     * @param string $plural
     * @return string
     */
    public static function getStringFromKey(string $key, bool $firstcap = true, int $qty = 1, string $plural = ''): string
    {
        //var_dump($key);
        switch ($key) {
            case 'upadte':
                return Text::getString(['update', 'update'], $firstcap, $qty, $plural);
            case 'courses':
                return Text::getString(['courses list', 'liste de cours'], $firstcap, $qty, $plural);
            case 'date_debut' :
                return Text::getString(['date_debut', 'date_debut'], $firstcap, $qty, $plural);
                case 'niveau_etude' :
                    return Text::getString(['niveau_etude', 'niveau_etude'], $firstcap, $qty, $plural);
                case 'date_fin' :
                    return Text::getString(['date_fin', 'date_fin'], $firstcap, $qty, $plural);
            case 'Blog' : 
                return Text::getString(['Blog', 'Blog'], $firstcap, $qty, $plural);
            case 'address':
                return Text::getString(['address', 'Complete address'], $firstcap, $qty, $plural);
            case 'phone':
                return Text::getString(['phone', 'phone number'], $firstcap, $qty, $plural);
            case 'amount':
                return Text::getString(['amount', 'état du compte'], $firstcap, $qty, $plural);
            case 'created':
                return Text::getString(['created', 'date de création'], $firstcap, $qty, $plural);
            case 'home':
                return Text::getString(['home', 'accueil'], $firstcap, $qty, $plural);
            case 'pays':
                return Text::getString(['pays', 'nationalité'], $firstcap, $qty, $plural);
            case 'birthdate':
                    return Text::getString(['birthdate', 'Date de naissance'], $firstcap, $qty, $plural);  
            case 'lastlogin':
                return Text::getString(['lastlogin', 'dernière connexion'], $firstcap, $qty, $plural);
            case 'login':
                return Text::getString(['login', 'se connecter'], $firstcap, $qty, $plural);
            case 'logout':
                return Text::getString(['logout', 'se déconnecter'], $firstcap, $qty, $plural);
            case 'password':
                return Text::getString(['password', 'mot de passe'], $firstcap, $qty, $plural);
            case 'profile':
                return Text::getString(['profile', 'profil'], $firstcap, $qty, $plural);
            case 'submit':
                return Text::getString(['submit', 'valider'], $firstcap, $qty, $plural);
            case 'update':
                return Text::getString(['update', 'mise à jour'], $firstcap, $qty, $plural);
            case 'username':
                return Text::getString(['username', 'identifiant'], $firstcap, $qty, $plural);
            default:
                return Text::getString([$key, $key], $firstcap, $qty, $plural);
        }
    }

    /**
     * @param array $text
     * @param bool $firstcap
     * @param string $plural
     * @param int $qty
     * @return string
     */
    public static function getString(array $text, bool $firstcap = true, int $qty = 2, string $plural = ''): string
    {
        //var_dump($text);
        $index = 0;
        if (!empty($_SESSION['pays'])) {
            if ($_SESSION['pays'] == 'belgium') {
                $index = 1;
            }
        }
        if ($firstcap) {
            $return = ucfirst($text[$index]);
        } else {
            $return = $text[$index];
        }
        if ($plural) {
            $return = self::plural($return, $qty, $plural);
        }
        //var_dump($return);
        return $return;
    }

    /**
     * Gestion basique du pluriel
     *
     * @param string $text      Texte
     * @param integer $qty      Quantité (si 2 ou +, le texte sera mis au pluriel)
     * @param string $end       Terminaison (par défaut 's')
     * @return string
     */
    public static function plural(string $text, int $qty = 2, string $end = 's'): string
    {
        if ($qty > 1 && $text[-1] != $end) {
            $text .= $end;
        }
        return $text;
    }
    public static function yesOrNo(mixed $value): string
    {
        if (!$value) {
            return self::getString(['No', 'Non'],);
        } else {
            return self::getString(['Yes', 'Oui']);
        }
    }
}
