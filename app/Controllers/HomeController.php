<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        return view('home');
    }

    public function regimesPreview()
    {
        $regimes = [
            [
                'name' => 'Perte de poids 30j',
                'objectif' => 'Perte de poids',
                'duree' => '30 jours',
                'prix' => '3000 Ar',
            ],
            [
                'name' => 'IMC ideal 21j',
                'objectif' => 'IMC ideal',
                'duree' => '21 jours',
                'prix' => '2500 Ar',
            ],
            [
                'name' => 'Prise de masse 45j',
                'objectif' => 'Prise de masse',
                'duree' => '45 jours',
                'prix' => '4500 Ar',
            ],
        ];

        return view('regimes_preview', ['regimes' => $regimes]);
    }

    public function sportsPreview()
    {
        $sports = [
            [
                'name' => 'Marche rapide',
                'niveau' => 'FAIBLE',
                'calories' => '220 kcal/h',
            ],
            [
                'name' => 'Natation',
                'niveau' => 'MOYEN',
                'calories' => '420 kcal/h',
            ],
            [
                'name' => 'HIIT',
                'niveau' => 'ELEVE',
                'calories' => '650 kcal/h',
            ],
        ];

        return view('sports_preview', ['sports' => $sports]);
    }
}
