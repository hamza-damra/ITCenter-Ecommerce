<?php

/**
 * Palestine Shipping Configuration
 * 
 * Central mapping of cities/governorates to postal code ranges.
 * Used by both frontend (city dropdown + validation hints) and backend (PalestinePostalCode rule).
 * 
 * Postal code format: P + 3 digits (e.g., P600)
 * Source: Palestine Post (postcode.palestine.ps) + custom ranges for الداخل المحتل
 * 
 * Gaza governorates (P800–P999) are explicitly BLOCKED.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Supported Shipping Regions
    |--------------------------------------------------------------------------
    |
    | West Bank governorates use official Palestine Post ranges.
    | الداخل المحتل cities use custom P001–P099 range (not in official system).
    |
    */

    'regions' => [

        /*
        |----------------------------------------------------------------------
        | West Bank Governorates (الضفة الغربية)
        |----------------------------------------------------------------------
        */
        'west_bank' => [
            'label' => [
                'en' => 'West Bank',
                'ar' => 'الضفة الغربية',
                'he' => 'הגדה המערבית',
            ],
            'cities' => [
                'jerusalem' => [
                    'name' => ['en' => 'Jerusalem', 'ar' => 'القدس', 'he' => 'ירושלים'],
                    'governorate' => ['en' => 'Jerusalem', 'ar' => 'القدس', 'he' => 'ירושלים'],
                    'postal_min' => 100,
                    'postal_max' => 148,
                ],
                'bethlehem' => [
                    'name' => ['en' => 'Bethlehem', 'ar' => 'بيت لحم', 'he' => 'בית לחם'],
                    'governorate' => ['en' => 'Bethlehem', 'ar' => 'بيت لحم', 'he' => 'בית לחם'],
                    'postal_min' => 149,
                    'postal_max' => 199,
                ],
                'jenin' => [
                    'name' => ['en' => 'Jenin', 'ar' => 'جنين', 'he' => "ג'נין"],
                    'governorate' => ['en' => 'Jenin', 'ar' => 'جنين', 'he' => "ג'נין"],
                    'postal_min' => 200,
                    'postal_max' => 299,
                ],
                'tulkarm' => [
                    'name' => ['en' => 'Tulkarm', 'ar' => 'طولكرم', 'he' => 'טולכרם'],
                    'governorate' => ['en' => 'Tulkarm', 'ar' => 'طولكرم', 'he' => 'טולכרם'],
                    'postal_min' => 300,
                    'postal_max' => 339,
                ],
                'qalqilya' => [
                    'name' => ['en' => 'Qalqilya', 'ar' => 'قلقيلية', 'he' => 'קלקיליה'],
                    'governorate' => ['en' => 'Qalqilya', 'ar' => 'قلقيلية', 'he' => 'קלקיליה'],
                    'postal_min' => 340,
                    'postal_max' => 377,
                ],
                'salfit' => [
                    'name' => ['en' => 'Salfit', 'ar' => 'سلفيت', 'he' => 'סלפית'],
                    'governorate' => ['en' => 'Salfit', 'ar' => 'سلفيت', 'he' => 'סלפית'],
                    'postal_min' => 380,
                    'postal_max' => 399,
                ],
                'nablus' => [
                    'name' => ['en' => 'Nablus', 'ar' => 'نابلس', 'he' => 'שכם'],
                    'governorate' => ['en' => 'Nablus', 'ar' => 'نابلس', 'he' => 'שכם'],
                    'postal_min' => 400,
                    'postal_max' => 499,
                ],
                'tubas' => [
                    'name' => ['en' => 'Tubas', 'ar' => 'طوباس', 'he' => 'טובאס'],
                    'governorate' => ['en' => 'Tubas', 'ar' => 'طوباس', 'he' => 'טובאס'],
                    'postal_min' => 500,
                    'postal_max' => 540,
                ],
                'jericho' => [
                    'name' => ['en' => 'Jericho', 'ar' => 'أريحا', 'he' => 'יריחו'],
                    'governorate' => ['en' => 'Jericho & Al-Aghwar', 'ar' => 'أريحا والأغوار', 'he' => 'יריחו ובקעת הירדן'],
                    'postal_min' => 550,
                    'postal_max' => 590,
                ],
                'ramallah' => [
                    'name' => ['en' => 'Ramallah & Al-Bireh', 'ar' => 'رام الله والبيرة', 'he' => 'רמאללה ואל-בירה'],
                    'governorate' => ['en' => 'Ramallah & Al-Bireh', 'ar' => 'رام الله والبيرة', 'he' => 'רמאללה ואל-בירה'],
                    'postal_min' => 600,
                    'postal_max' => 699,
                ],
                'hebron' => [
                    'name' => ['en' => 'Hebron', 'ar' => 'الخليل', 'he' => 'חברון'],
                    'governorate' => ['en' => 'Hebron', 'ar' => 'الخليل', 'he' => 'חברון'],
                    'postal_min' => 700,
                    'postal_max' => 797,
                ],
            ],
        ],

        /*
        |----------------------------------------------------------------------
        | الداخل المحتل (1948 Territories)
        |----------------------------------------------------------------------
        | Custom P001–P099 range (not in official Palestine Post system).
        | Sub-ranges assigned per city group.
        */
        'interior_48' => [
            'label' => [
                'en' => '1948 Territories',
                'ar' => 'الداخل المحتل (48)',
                'he' => 'שטחי 48',
            ],
            'cities' => [
                'haifa' => [
                    'name' => ['en' => 'Haifa', 'ar' => 'حيفا', 'he' => 'חיפה'],
                    'governorate' => ['en' => 'Haifa', 'ar' => 'حيفا', 'he' => 'חיפה'],
                    'postal_min' => 1,
                    'postal_max' => 10,
                ],
                'jaffa' => [
                    'name' => ['en' => 'Jaffa', 'ar' => 'يافا', 'he' => 'יפו'],
                    'governorate' => ['en' => 'Jaffa', 'ar' => 'يافا', 'he' => 'יפו'],
                    'postal_min' => 11,
                    'postal_max' => 20,
                ],
                'nazareth' => [
                    'name' => ['en' => 'Nazareth', 'ar' => 'الناصرة', 'he' => 'נצרת'],
                    'governorate' => ['en' => 'Nazareth', 'ar' => 'الناصرة', 'he' => 'נצרת'],
                    'postal_min' => 21,
                    'postal_max' => 30,
                ],
                'acre' => [
                    'name' => ['en' => 'Acre (Akka)', 'ar' => 'عكا', 'he' => 'עכו'],
                    'governorate' => ['en' => 'Acre', 'ar' => 'عكا', 'he' => 'עכו'],
                    'postal_min' => 31,
                    'postal_max' => 37,
                ],
                'umm_al_fahm' => [
                    'name' => ['en' => 'Umm Al-Fahm', 'ar' => 'أم الفحم', 'he' => 'אום אל-פחם'],
                    'governorate' => ['en' => 'Umm Al-Fahm', 'ar' => 'أم الفحم', 'he' => 'אום אל-פחם'],
                    'postal_min' => 38,
                    'postal_max' => 44,
                ],
                'lod' => [
                    'name' => ['en' => 'Lod (Lydda)', 'ar' => 'اللد', 'he' => 'לוד'],
                    'governorate' => ['en' => 'Lod', 'ar' => 'اللد', 'he' => 'לוד'],
                    'postal_min' => 45,
                    'postal_max' => 51,
                ],
                'ramleh' => [
                    'name' => ['en' => 'Ramleh', 'ar' => 'الرملة', 'he' => 'רמלה'],
                    'governorate' => ['en' => 'Ramleh', 'ar' => 'الرملة', 'he' => 'רמלה'],
                    'postal_min' => 52,
                    'postal_max' => 58,
                ],
                'beer_sheva' => [
                    'name' => ['en' => 'Beer Sheva (Negev)', 'ar' => 'بئر السبع', 'he' => 'באר שבע'],
                    'governorate' => ['en' => 'Beer Sheva', 'ar' => 'بئر السبع', 'he' => 'באר שבע'],
                    'postal_min' => 59,
                    'postal_max' => 65,
                ],
                'tiberias' => [
                    'name' => ['en' => 'Tiberias', 'ar' => 'طبريا', 'he' => 'טבריה'],
                    'governorate' => ['en' => 'Tiberias', 'ar' => 'طبريا', 'he' => 'טבריה'],
                    'postal_min' => 66,
                    'postal_max' => 72,
                ],
                'safed' => [
                    'name' => ['en' => 'Safed', 'ar' => 'صفد', 'he' => 'צפת'],
                    'governorate' => ['en' => 'Safed', 'ar' => 'صفد', 'he' => 'צפת'],
                    'postal_min' => 73,
                    'postal_max' => 79,
                ],
                'taybeh' => [
                    'name' => ['en' => 'Taybeh', 'ar' => 'الطيبة', 'he' => 'טייבה'],
                    'governorate' => ['en' => 'Taybeh', 'ar' => 'الطيبة', 'he' => 'טייבה'],
                    'postal_min' => 80,
                    'postal_max' => 85,
                ],
                'baqa_al_gharbiyye' => [
                    'name' => ['en' => 'Baqa Al-Gharbiyye', 'ar' => 'باقة الغربية', 'he' => "בקא אל-ע'רביה"],
                    'governorate' => ['en' => 'Baqa Al-Gharbiyye', 'ar' => 'باقة الغربية', 'he' => "בקא אל-ע'רביה"],
                    'postal_min' => 86,
                    'postal_max' => 91,
                ],
                'shefa_amr' => [
                    'name' => ['en' => 'Shefa-Amr', 'ar' => 'شفا عمرو', 'he' => "שפרעם"],
                    'governorate' => ['en' => 'Shefa-Amr', 'ar' => 'شفا عمرو', 'he' => "שפרעם"],
                    'postal_min' => 92,
                    'postal_max' => 99,
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Blocked Regions (Gaza)
    |--------------------------------------------------------------------------
    | These postal code ranges are explicitly rejected.
    */
    'blocked_ranges' => [
        ['min' => 800, 'max' => 830, 'label' => 'North Gaza'],
        ['min' => 840, 'max' => 890, 'label' => 'Gaza'],
        ['min' => 900, 'max' => 929, 'label' => 'Deir El Balah'],
        ['min' => 930, 'max' => 969, 'label' => 'Khan Yunis'],
        ['min' => 970, 'max' => 999, 'label' => 'Rafah'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fixed Country Value
    |--------------------------------------------------------------------------
    */
    'country' => 'Palestine',

];
