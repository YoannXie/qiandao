<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\QiandaoController;

class IndexController extends Controller
{
    public function index()
    {
    	$qiandao = new QiandaoController();
    	return view('index',['signs' => $qiandao->getSigns()]);
    }
}
