<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return view('back.pages.index'); // back/pages/index.blade.php dosyasını döndür
    }
} 