#!/usr/bin/env php
<?php

// longueur à générer
$length = (!empty($argv[1]) && is_numeric($argv[1])) ? $argv[1] : 16;

echo generatePassword($length) . "\n";
exit;

/**
 * Génération d'un mot de passe de n caractères
 * (les caractères iI lL oO 0 1 sont omis pour éviter des confusions)
 *
 * @param string $length
 * @return string
 */
function generatePassword(int $length)
{
    srand((double) microtime() * date('YmdGis'));

    $lettres  = 'abcdefghjkmnpqrstuvwxyz';
    $lettres .= 'ABCDEFGHJKMNPQRSTUVWXYZ';
    $lettres .= '23456789';

    $pwd = '';
    $max = mb_strlen($lettres) - 1;
    for($cpt = 0 ; $cpt < $length ; $cpt++) {
        $pwd .= $lettres[rand(0, $max)];
    }

    return $pwd;
}
