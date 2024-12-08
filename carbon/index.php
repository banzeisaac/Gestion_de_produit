<?php

require __DIR__.'/../vendor/autoload.php';

use Carbon\Carbon;

$pdo = new PDO('mysql:host=localhost;dbname=prog-sys-2024-2025;user=root');

$pdoStatement = $pdo->prepare('SELECT * FROM produits');

$pdoStatement->execute();

$produits = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

function diffForHumans($datetime)
{
    $date = Carbon::createFromTimeString($datetime);

    $date->locale("fr_FR");

    return $date->diffForHumans();
}

/**
 * badge rouge : produit déjà expiré
 * badge jaune : produit qui expire dans moins de 3 mois
 * badge vert : produit qui expire au délà de 3 mois
 */

function badge($datetime)
{
    $diffForHumans = diffForHumans($datetime);
    
    $date = Carbon::createFromTimeString($datetime);

    if($date->diffInDays(Carbon::now()) >= 0)
    {
        return badgeRouge($diffForHumans);
    }

    if($date->diffInMonths(Carbon::now()) > -3 and 
        $date->diffInMonths(Carbon::now()) < 0)
    {
        return badgeJaune($diffForHumans);
    }
    
    return badgeVert($diffForHumans);
}

function badgeRouge($value)
{
    return '
        <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 
                     rounded dark:bg-red-900 dark:text-red-300">
        '.$value.'
        </span>
    ';
}

function badgeJaune($value)
{
    return '
        <span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 
                     rounded dark:bg-yellow-900 dark:text-yellow-300">
        '.$value.'
        </span>
    ';
}

function badgeVert($value)
{
    return '
        <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 
                     rounded dark:bg-green-900 dark:text-green-300">
        '.$value.'
        </span>
    ';
}

require 'produits-expiration.php';