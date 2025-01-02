<?php

namespace App\Services;

class ScoringService
{
    public static function calculateScore($answer, $options)
    {

        // Ensure the answer is treated as a float for calculations involving numbers
        if (in_array($options, ['%', 'Rupiah']) && is_numeric($answer)) {
            return (float) $answer;
        }

        // Handle percentages: cap the score at 1 if the answer exceeds 1
        if ($options === '%' && $answer !== null) {
            return $answer > 1 ? 1 : $answer; // Cap at 1
        }

        switch ($options) {
            case 'Ya/Tidak':
                return strtolower($answer) === 'ya' ? 1 : 0;

            case 'A/B/C':
                return match (strtoupper($answer)) {
                    'A' => 1,
                    'B' => 0.5,
                    default => 0,
                };

            case 'A/B/C/D':
                return match (strtoupper($answer)) {
                    'A' => 1,
                    'B' => 0.67,
                    'C' => 0.33,
                    default => 0,
                };

            case 'A/B/C/D/E':
                return match (strtoupper($answer)) {
                    'A' => 1,
                    'B' => 0.75,
                    'C' => 0.5,
                    'D' => 0.25,
                    default => 0,
                };

            case 'Jumlah':
                return null;
                

            default:
                return 0; // Default score
        }
    }
}
