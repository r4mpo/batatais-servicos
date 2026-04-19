<?php

namespace App\Support;

/**
 * Utilitários para validar e formatar CPF/CNPJ e valores auxiliares usados nos formulários brasileiros.
 *
 * Não acessa banco de dados; apenas regras puras sobre strings e números.
 */
final class BrazilianDocuments
{
    /**
     * Remove tudo que não for dígito da string informada.
     *
     * @param  string|null  $value  Texto vindo do formulário ou da base.
     * @return string  Somente `0-9` ou string vazia.
     */
    public static function onlyDigits(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return preg_replace('/\D/', '', $value) ?? '';
    }

    /**
     * Valida dígitos verificadores do CPF (11 posições, sem máscara).
     *
     * Passo a passo:
     * 1. Exigir exatamente 11 dígitos.
     * 2. Rejeitar sequências repetidas (111…, 000…).
     * 3. Para cada dígito verificador (posições 9 e 10), recalcular pelo algoritmo oficial e comparar.
     *
     * @param  string  $digits  Exatamente 11 caracteres numéricos.
     */
    public static function isValidCpf(string $digits): bool
    {
        if (strlen($digits) !== 11) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $digits) === 1) {
            return false;
        }

        for ($indiceVerificador = 9; $indiceVerificador < 11; $indiceVerificador++) {
            $somaPonderada = 0;
            for ($coluna = 0; $coluna < $indiceVerificador; $coluna++) {
                $somaPonderada += (int) $digits[$coluna] * (($indiceVerificador + 1) - $coluna);
            }
            $digitoEsperado = ((10 * $somaPonderada) % 11) % 10;
            if ((int) $digits[$indiceVerificador] !== $digitoEsperado) {
                return false;
            }
        }

        return true;
    }

    /**
     * Valida dígitos verificadores do CNPJ (14 posições, sem máscara).
     *
     * Passo a passo:
     * 1. Exigir exatamente 14 dígitos e rejeitar sequências repetidas.
     * 2. Calcular o 13º dígito com pesos fixos nos 12 primeiros.
     * 3. Calcular o 14º dígito com pesos fixos nos 13 primeiros.
     *
     * @param  string  $digits  Exatamente 14 caracteres numéricos.
     */
    public static function isValidCnpj(string $digits): bool
    {
        if (strlen($digits) !== 14) {
            return false;
        }

        if (preg_match('/^(\d)\1{13}$/', $digits) === 1) {
            return false;
        }

        $pesosPrimeiroDigito = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $pesosSegundoDigito = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        $soma = 0;
        for ($i = 0; $i < 12; $i++) {
            $soma += (int) $digits[$i] * $pesosPrimeiroDigito[$i];
        }
        $resto = $soma % 11;
        $primeiroVerificador = $resto < 2 ? 0 : 11 - $resto;
        if ((int) $digits[12] !== $primeiroVerificador) {
            return false;
        }

        $soma = 0;
        for ($i = 0; $i < 13; $i++) {
            $soma += (int) $digits[$i] * $pesosSegundoDigito[$i];
        }
        $resto = $soma % 11;
        $segundoVerificador = $resto < 2 ? 0 : 11 - $resto;

        return (int) $digits[13] === $segundoVerificador;
    }

    /**
     * Formata CPF armazenado (só dígitos) para exibição com máscara `000.000.000-00`.
     *
     * @param  string|null  $stored  Valor salvo na base; se já estiver inválido em tamanho, devolve sem alterar estrutura.
     */
    public static function formatCpfForDisplay(?string $stored): string
    {
        if ($stored === null || $stored === '') {
            return '';
        }
        $digitos = self::onlyDigits($stored);
        if (strlen($digitos) !== 11) {
            return $stored;
        }

        return substr($digitos, 0, 3).'.'.substr($digitos, 3, 3).'.'.substr($digitos, 6, 3).'-'.substr($digitos, 9, 2);
    }

    /**
     * Formata CNPJ armazenado (só dígitos) para exibição com máscara `00.000.000/0000-00`.
     *
     * @param  string|null  $stored  Valor salvo na base.
     */
    public static function formatCnpjForDisplay(?string $stored): string
    {
        if ($stored === null || $stored === '') {
            return '';
        }
        $digitos = self::onlyDigits($stored);
        if (strlen($digitos) !== 14) {
            return $stored;
        }

        return substr($digitos, 0, 2).'.'.substr($digitos, 2, 3).'.'.substr($digitos, 5, 3).'/'.substr($digitos, 8, 4).'-'.substr($digitos, 12, 2);
    }

    /**
     * Converte valor horário em centavos para string em reais com separadores brasileiros (ex.: `45,50`).
     */
    public static function formatHourlyReaisFromCents(int $hourlyRateCents): string
    {
        return number_format($hourlyRateCents / 100, 2, ',', '.');
    }

    /**
     * Gera um CPF válido e distinto por índice (uso restrito a seeders e testes automatizados).
     *
     * @param  int  $index  Sequência para variar os 9 primeiros dígitos antes dos verificadores.
     */
    public static function demoCpf(int $index): string
    {
        $base = 100000000 + ($index * 7919 % 899999999);
        $novePrimeiros = str_pad((string) $base, 9, '0', STR_PAD_LEFT);

        return self::appendCpfCheckDigits($novePrimeiros);
    }

    /**
     * Calcula os dois dígitos verificadores e concatena aos 9 primeiros dígitos do CPF.
     */
    private static function appendCpfCheckDigits(string $nine): string
    {
        $d1 = self::cpfVerifierDigit($nine, 10);
        $d2 = self::cpfVerifierDigit($nine.$d1, 11);

        return $nine.$d1.$d2;
    }

    /**
     * Um dígito verificador do CPF conforme posição (algoritmo oficial).
     *
     * @param  string  $partial  Bloco parcial do CPF (9 ou 10 dígitos).
     * @param  int  $factorStart  Fator inicial da multiplicação (10 ou 11).
     */
    private static function cpfVerifierDigit(string $partial, int $factorStart): int
    {
        $soma = 0;
        $comprimento = strlen($partial);
        for ($i = 0; $i < $comprimento; $i++) {
            $soma += (int) $partial[$i] * ($factorStart - $i);
        }
        $resto = ($soma * 10) % 11;

        return $resto === 10 ? 0 : $resto;
    }
}
