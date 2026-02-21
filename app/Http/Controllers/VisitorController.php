<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    //Methode permet d'afficher la page d'accueil
    public function home(){
        return view('home');
    }

    //Methode permet d'afficher la page a propos
    public function about(){
        return view('about');
    }

    //Methode permet d'afficher la page contact
    public function products(){
        return view('products');
    }
}
