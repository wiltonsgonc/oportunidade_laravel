<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Exibir a página inicial
     */
    public function index()
    {
        return view('welcome');
    }
}