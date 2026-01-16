<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoDocumento;

class TipoDocumentoController extends Controller
{
    public function index()
    {
        $tipos = TipoDocumento::all();
        return $tipos;
    }
}
