<?php

namespace App\Support;

final class BrazilianDocuments
{
    public static function onlyDigits(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return preg_replace('/\D/', '', $value) ?? '';
    }

    public static function isValidCpf(string $digits): bool
    {
        if (strlen($digits) !== 11) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $digits) === 1) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += (int) $digits[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ((int) $digits[$t] !== $d) {
                return false;
            }
        }

        return true;
    }

    public static function isValidCnpj(string $digits): bool
    {
        if (strlen($digits) !== 14) {
            return false;
        }

        if (preg_match('/^(\d)\1{13}$/', $digits) === 1) {
            return false;
        }

        $weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $digits[$i] * $weights1[$i];
        }
        $r = $sum % 11;
        $d1 = $r < 2 ? 0 : 11 - $r;
        if ((int) $digits[12] !== $d1) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $digits[$i] * $weights2[$i];
        }
        $r = $sum % 11;
        $d2 = $r < 2 ? 0 : 11 - $r;

        return (int) $digits[13] === $d2;
    }

    public static function formatCpfForDisplay(?string $stored): string
    {
        if ($stored === null || $stored === '') {
            return '';
        }
        $d = self::onlyDigits($stored);
        if (strlen($d) !== 11) {
            return $stored;
        }

        return substr($d, 0, 3).'.'.substr($d, 3, 3).'.'.substr($d, 6, 3).'-'.substr($d, 9, 2);
    }

    public static function formatCnpjForDisplay(?string $stored): string
    {
        if ($stored === null || $stored === '') {
            return '';
        }
        $d = self::onlyDigits($stored);
        if (strlen($d) !== 14) {
            return $stored;
        }

        return substr($d, 0, 2).'.'.substr($d, 2, 3).'.'.substr($d, 5, 3).'/'.substr($d, 8, 4).'-'.substr($d, 12, 2);
    }

    public static function formatHourlyReaisFromCents(int $hourlyRateCents): string
    {
        return number_format($hourlyRateCents / 100, 2, ',', '.');
    }

    /**
     * Gera um CPF válido e único por índice (apenas para seed / testes).
     */
    public static function demoCpf(int $index): string
    {
        $base = 100000000 + ($index * 7919 % 899999999);
        $nine = str_pad((string) $base, 9, '0', STR_PAD_LEFT);

        return self::appendCpfCheckDigits($nine);
    }

    private static function appendCpfCheckDigits(string $nine): string
    {
        $d1 = self::cpfVerifierDigit($nine, 10);
        $d2 = self::cpfVerifierDigit($nine.$d1, 11);

        return $nine.$d1.$d2;
    }

    private static function cpfVerifierDigit(string $partial, int $factorStart): int
    {
        $sum = 0;
        $len = strlen($partial);
        for ($i = 0; $i < $len; $i++) {
            $sum += (int) $partial[$i] * ($factorStart - $i);
        }
        $r = ($sum * 10) % 11;

        return $r === 10 ? 0 : $r;
    }
}
