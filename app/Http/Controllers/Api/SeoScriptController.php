<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SeoScript;
use Illuminate\Http\Request;

class SeoScriptController extends Controller
{
    public function index()
    {
        $scripts = SeoScript::where('status', true)
                  ->select('id', 'script_content')
                  ->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $scripts
        ]);
    }

    public function show($id)
    {
        $script = SeoScript::where('id', $id)
                  ->where('status', true)
                  ->select('id', 'script_content')
                  ->first();

        if (!$script) {
            return response()->json([
                'status' => 'error',
                'message' => 'Script bulunamadı'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $script
        ]);
    }
}
