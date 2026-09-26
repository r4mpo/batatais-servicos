<?php

namespace App\Http\Controllers;

use App\Http\Responses\ResultadoResposta;
use Illuminate\View\View;

/**
 * Página pública sobre a SpiderSoft.
 */
class AboutController extends Controller
{
    /**
     * Exibe o texto institucional da SpiderSoft.
     */
    public function index(): View
    {
        return $this->responder(ResultadoResposta::pagina('about.index'));
    }
}
