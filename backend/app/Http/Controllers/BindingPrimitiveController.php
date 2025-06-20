<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BindingPrimitiveController extends Controller
{
    public function __construct(protected $variable){}

    public function run(){
        echo $this->variable;
    }
}
