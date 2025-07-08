<?php

namespace App\Services;

class LocationService
{
    /**
     * Get all districts (kecamatan) data
     */
    public static function getDistricts()
    {
        return [
            '010' => 'BELAKANG PADANG',
            '020' => 'BULANG',
            '030' => 'GALANG',
            '040' => 'SEI BEDUK',
            '041' => 'SAGULUNG',
            '050' => 'NONGSA',
            '051' => 'BATAM KOTA',
            '060' => 'SEKUPANG',
            '061' => 'BATU AJI',
            '070' => 'LUBUK BAJA',
            '080' => 'BATU AMPAR',
            '081' => 'BENGKONG',
        ];
    }

    /**
     * Get all villages (kelurahan) data organized by district
     */
    public static function getVillages()
    {
        return [
            '010' => [ // BELAKANG PADANG
                '001' => 'PULAU TERONG',
                '002' => 'PECONG',
                '003' => 'KASU',
                '004' => 'PEMPING',
                '006' => 'TANJUNG SARI',
                '007' => 'SEKANAK RAYA',
            ],
            '020' => [ // BULANG
                '001' => 'PANTAI GELAM',
                '002' => 'TEMOYONG',
                '003' => 'PULAU SETOKOK',
                '004' => 'BATU LEGONG',
                '005' => 'BULANG LINTANG',
                '006' => 'PULAU BULUH',
            ],
            '030' => [ // GALANG
                '001' => 'PULAU ABANG',
                '002' => 'KARAS',
                '003' => 'SIJANTUNG',
                '004' => 'SEMBULANG',
                '005' => 'REMPANG CATE',
                '006' => 'SUBANG MAS',
                '007' => 'GALANG BARU',
                '008' => 'AIR RAJA',
            ],
            '040' => [ // SEI BEDUK
                '003' => 'TANJUNG PIAYU',
                '004' => 'MUKA KUNING',
                '005' => 'DURIANGKANG',
                '006' => 'MANGSANG',
            ],
            '041' => [ // SAGULUNG
                '001' => 'TEMBESI',
                '002' => 'SUNGAI BINTI',
                '003' => 'SUNGAI LEKOP',
                '004' => 'SAGULUNG KOTA',
                '005' => 'SUNGAI LANGKAI',
                '006' => 'SUNGAI PELUNGGUT',
            ],
            '050' => [ // NONGSA
                '001' => 'NGENANG',
                '002' => 'KABIL',
                '003' => 'BATU BESAR',
                '008' => 'SAMBAU',
            ],
            '051' => [ // BATAM KOTA
                '001' => 'TAMAN BALOI',
                '002' => 'BALOI PERMAI',
                '003' => 'BELIAN',
                '004' => 'TELUK TERING',
                '005' => 'SUNGAI PANAS',
                '006' => 'SUKAJADI',
            ],
            '060' => [ // SEKUPANG
                '002' => 'TANJUNG RIAU',
                '003' => 'TIBAN BARU',
                '004' => 'TIBAN LAMA',
                '005' => 'TIBAN INDAH',
                '006' => 'PATAM LESTARI',
                '007' => 'SUNGAI HARAPAN',
                '008' => 'TANJUNG PINGGIR',
            ],
            '061' => [ // BATU AJI
                '001' => 'TANJUNG UNCANG',
                '002' => 'BUKIT TEMPAYAN',
                '003' => 'BULIANG',
                '004' => 'KIBING',
            ],
            '070' => [ // LUBUK BAJA
                '001' => 'BALOI INDAH',
                '002' => 'BATU SELICIN',
                '003' => 'KAMPUNG PELITA',
                '004' => 'LUBUK BAJA KOTA',
                '005' => 'TANJUNG UMA',
            ],
            '080' => [ // BATU AMPAR
                '004' => 'KAMPUNG SERAYA',
                '005' => 'SUNGAI JODOH',
                '007' => 'TANJUNG SENGKUANG',
                '008' => 'BATU MERAH',
            ],
            '081' => [ // BENGKONG
                '001' => 'BENGKONG LAUT',
                '002' => 'BENGKONG INDAH',
                '003' => 'SADAI',
                '004' => 'TANJUNG BUNTUNG',
            ],
        ];
    }

    /**
     * Get villages for a specific district
     */
    public static function getVillagesByDistrict($districtCode)
    {
        $villages = self::getVillages();
        return $villages[$districtCode] ?? [];
    }

    /**
     * Get formatted districts for dropdown
     */
    public static function getDistrictsForDropdown()
    {
        $districts = self::getDistricts();
        $formatted = [];
        
        foreach ($districts as $code => $name) {
            $formatted[$code] = $code . ' - ' . $name;
        }
        
        return $formatted;
    }

    /**
     * Get formatted villages for dropdown by district
     */
    public static function getVillagesForDropdown($districtCode)
    {
        $villages = self::getVillagesByDistrict($districtCode);
        $formatted = [];
        
        foreach ($villages as $code => $name) {
            $formatted[$code] = $code . ' - ' . $name;
        }
        
        return $formatted;
    }

    /**
     * Get district name by code
     */
    public static function getDistrictName($code)
    {
        $districts = self::getDistricts();
        return $districts[$code] ?? null;
    }

    /**
     * Get village name by district and village code
     */
    public static function getVillageName($districtCode, $villageCode)
    {
        $villages = self::getVillagesByDistrict($districtCode);
        return $villages[$villageCode] ?? null;
    }
}
