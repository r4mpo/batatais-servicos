<?php

namespace App\Http\Responses;

/**
 * Descreve o desfecho de uma action sem montar view, redirect, abort ou arquivo.
 */
final class ResultadoResposta
{
    public const PAGINA = 'pagina';

    public const REDIRECIONAR = 'redirecionar';

    public const ARQUIVO = 'arquivo';

    public const ERRO_HTTP = 'erro_http';

    /**
     * @param  array<string, mixed>  $dados
     * @param  array<string, mixed>  $parametros
     * @param  array<string, string>  $erros
     * @param  array<string, mixed>  $input
     * @param  array<string, mixed>  $sessao
     * @param  array<string, string>  $headers
     */
    private function __construct(
        public readonly string $tipo,
        public readonly ?string $view = null,
        public readonly array $dados = [],
        public readonly ?string $rota = null,
        public readonly array $parametros = [],
        public readonly ?string $status = null,
        public readonly array $erros = [],
        public readonly ?string $fragmento = null,
        public readonly ?array $input = null,
        public readonly array $sessao = [],
        public readonly ?string $caminho = null,
        public readonly array $headers = [],
        public readonly int $statusHttp = 500,
    ) {}

    /**
     * @param  array<string, mixed>  $dados
     */
    public static function pagina(string $view, array $dados = []): self
    {
        return new self(tipo: self::PAGINA, view: $view, dados: $dados);
    }

    /**
     * @param  array<string, mixed>  $parametros
     * @param  array<string, string>  $erros
     * @param  array<string, mixed>|null  $input
     * @param  array<string, mixed>  $sessao
     */
    public static function redirecionar(
        string $rota,
        array $parametros = [],
        ?string $status = null,
        array $erros = [],
        ?string $fragmento = null,
        ?array $input = null,
        array $sessao = [],
    ): self {
        return new self(
            tipo: self::REDIRECIONAR,
            rota: $rota,
            parametros: $parametros,
            status: $status,
            erros: $erros,
            fragmento: $fragmento,
            input: $input,
            sessao: $sessao,
        );
    }

    /**
     * @param  array<string, string>  $headers
     */
    public static function arquivo(string $caminho, array $headers = []): self
    {
        return new self(tipo: self::ARQUIVO, caminho: $caminho, headers: $headers);
    }

    public static function erroHttp(int $status): self
    {
        return new self(tipo: self::ERRO_HTTP, statusHttp: $status);
    }
}
