<?php

namespace App\Validations;

trait SaIdNumberVerification
{
    public function validateSouthAfricanId(string $idNumber): bool
    {
        // Check for valid length (13 digits) and numeric characters
        if (strlen($idNumber) !== 13 || !is_numeric($idNumber)) {
            return false;
        }

        // Extract components 930208 1372 0 8 9 // YYMMDD G SSSS C 8 Z
        $year = substr($idNumber, 0, 2);
        $prefix = $this->determineCentury($year);
        $birthYear = (int)$prefix . $year;
        $BirthMonth = (int)substr($idNumber, 2, 2);
        $BirthDay = (int)substr($idNumber, 4, 2);
        $gender = substr($idNumber, 6, 4);
        $citizenship = (int)substr($idNumber, 10, 1);
        $checksum = (int)substr($idNumber, 12, 1);

        // Validate birthdate (basic check)
        if (!checkdate($BirthMonth, $BirthDay, $birthYear)) {
            return false;
        }

        // Validate gender (0-4 female, 5-9 male)
        // unnecessary check
        if ($gender < '0000' || $gender > '9999') {
            return false;
        }

        // Validate citizenship (0 - South African Citizen, 1 - Permanent Resident)
        if ($citizenship !== 0 && $citizenship !== 1) {
            return false;
        }

        // Calculate checksum digit using Luhn Algorithm
        $digits = str_split(substr($idNumber, 0, 12));
        $sum = array_reduce($digits, function ($carry, $digit) use (&$index) {
            $digit = (($index++ % 2 === 0) ? $digit : $digit * 2);
            return $carry + ($digit > 9 ? $digit - 9 : $digit);
        }, 0);

        $checkDigit = (10 - ($sum % 10)) % 10;

        // Validate checksum
        return $checksum === $checkDigit;
    }

    private function determineCentury($YY): string
    {
        return ($YY < date('y')) ? '20' : '19';
    }
}
