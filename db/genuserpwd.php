#!/usr/bin/env php
<?php

$length = 16; // longueur du password

$htpasswd_crypt = [];
$htpasswd_plain = [];

$users = file('users.csv');
foreach ($users as $user) {
  $user = trim($user);
  echo "Génération d'un mot de passe pour $user\n";
  $password = generatePassword($length);
  $htpasswd_crypt[] = $user . ':' . password_hash($password, PASSWORD_BCRYPT); // NGinx pas compatible BCRYPT
  $htpasswd_plain[] = $user . ':' . $password;
}

file_put_contents('.htpasswd', implode("\n", $htpasswd_crypt) . "\n");
file_put_contents('.htpasswd_plain', implode("\n", $htpasswd_plain) . "\n");

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

/**
 * Retourne une version encryptée du mot de passe
 * 
 * @param string $password
 * @param string $algo
 * @return string
 */
function getEncryptedPassword(string $password, string $algo)
{
  return '';
}
