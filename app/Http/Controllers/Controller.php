<?php

namespace App\Http\Controllers;

use App\Http\Responses\ResultadoResposta;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

abstract class Controller
{
    /**
     * Único ponto que transforma {@see ResultadoResposta} em resposta HTTP.
     */
    protected function responder(ResultadoResposta $resultado): View|RedirectResponse|BinaryFileResponse
    {
        return match ($resultado->tipo) {
            ResultadoResposta::PAGINA => view((string) $resultado->view, $resultado->dados),
            ResultadoResposta::REDIRECIONAR => $this->montarRedirecionamento($resultado),
            ResultadoResposta::ARQUIVO => response()->file((string) $resultado->caminho, $resultado->headers),
            ResultadoResposta::ERRO_HTTP => abort($resultado->statusHttp),
        };
    }

    private function montarRedirecionamento(ResultadoResposta $resultado): RedirectResponse
    {
        $resposta = redirect()->route((string) $resultado->rota, $resultado->parametros);

        if ($resultado->status !== null) {
            $resposta = $resposta->with('status', $resultado->status);
        }

        foreach ($resultado->sessao as $chave => $valor) {
            $resposta = $resposta->with($chave, $valor);
        }

        if ($resultado->erros !== []) {
            $resposta = $resposta->withErrors($resultado->erros);
        }

        if ($resultado->fragmento !== null) {
            $resposta = $resposta->withFragment($resultado->fragmento);
        }

        if ($resultado->input !== null) {
            $resposta = $resposta->withInput($resultado->input);
        }

        return $resposta;
    }
}
