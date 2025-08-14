<?php

namespace App\Faker;

class AddressElement
{
    public function complexNames(int $count = 10): array
    {
        $starts = [
            'Aloe', 'Acacia', 'Baobab', 'Blue Crane', 'Bushbuck', 'Canyon',
            'Cape', 'Cedar', 'Coral', 'Dune', 'Eagle', 'Eland', 'Emerald',
            'Fynbos', 'Garden', 'Golden', 'Green', 'Harmony', 'Highland', 'Horizon',
            'Impala', 'Ironwood', 'Jacaranda', 'Kalahari', 'Karoo', 'Kudu',
            'Lake', 'Leopard', 'Lion', 'Lush', 'Mahogany', 'Maluti', 'Marula',
            'Morning', 'Mountain', 'Mzansi', 'Nguni', 'Oak', 'Ocean', 'Olive',
            'Palm', 'Panorama', 'Peace', 'Phoenix', 'Pine', 'Protea', 'Rainbow',
            'River', 'Rose', 'Safari', 'Sand', 'Savanna', 'Serene', 'Silver',
            'Sky', 'Springbok', 'Star', 'Stone', 'Sunrise', 'Sunset', 'Thorn',
            'Valley', 'Victoria', 'Village', 'Vineyard', 'Water', 'Whispering', 'Wild Fig',
            'Willow', 'Woodland', 'Zulu', 'Xhosa', 'Sotho', 'Tswana',
        ];

        $middles = [
            'Glen', 'Park', 'Ridge', 'Heights', 'View', 'Grove', 'Manor', 'Gardens',
            'Estate', 'Place', 'Creek', 'Dale', 'Walk', 'Pines', 'Villas', 'Courts',
            'Close', 'Avenue', 'Mews', 'Square', 'Terrace', 'Quarters', 'Side', 'Bay',
            'Meadows', 'Springs', 'Crossings', 'Landing', 'Point', 'Spruit', 'Farms',
            'Port', 'Lodge', 'Rise', 'Knoll', 'Crescent', 'Lane', 'Way',
        ];

        $suffixes = [
            '', // No suffix
            's', 'Heights', 'View', 'Estate', 'Gardens', 'Park', 'Ridge', 'Grove',
            'Place', 'Manor', 'Villas', 'Court', 'Lodge', 'Creek', 'Meadows', 'Springs',
            'Walk', 'Dale', 'Lakes', 'Pointe', 'Oaks', 'Palms', 'Pines', 'Rock',
            'Crossing', 'Terrace', 'Residences', 'Haven', 'Village', 'Lane',
        ];

        $indigenous_elements = [
            'Thaba',   // Mountain (Sotho/Tswana)
            'Lethabo', // Happiness (Sotho)
            'Lerato',  // Love (Sotho/Tswana)
            'Mandla',  // Strength (Zulu/Xhosa)
            'Sipho',   // Gift (Zulu/Xhosa)
            'Naledi',  // Star (Sotho/Tswana)
            'Kagiso',  // Peace (Sotho/Tswana)
            'Lesedi',  // Light (Sotho)
            'Zola',    // Tranquil (Zulu)
            'Khanya',  // Light/Shine (Xhosa)
            'Nomvula', // Mother of rain (Xhosa)
            'Siyanda', // We are growing (Zulu)
            'Ubuntu',  // Humanity/Community
            'Mzansi',  // South Africa (Xhosa/Zulu slang)
            'Kaya',    // Home (Zulu)
            'Akani',   // To build (Tsonga)
        ];

        $names = [];
        for ($i = 0; $i < $count; $i++) {
            $part1_choice = rand(0, 2); // 0 = indigenous, 1 = English/Afrikaans start, 2 = combine
            $part2_choice = rand(0, 1); // 0 = descriptive middle, 1 = combine with start

            $name_parts = [];

            if ($part1_choice === 0 && !empty($indigenous_elements)) {
                $name_parts[] = $indigenous_elements[array_rand($indigenous_elements)];
            } elseif (!empty($starts)) {
                $name_parts[] = $starts[array_rand($starts)];
            }

            if ($part2_choice === 0 && !empty($middles)) {
                $name_parts[] = $middles[array_rand($middles)];
            } elseif ($part1_choice !== 0 && !empty($starts)) { // Combine a start with another start (e.g., "Silver Oak")
                $name_parts[] = $starts[array_rand($starts)];
            }

            // Add a suffix sometimes
            if (rand(0, 1)) { // 50% chance to add a suffix
                $name_parts[] = $suffixes[array_rand($suffixes)];
            }

            // Filter out empty parts and make sure there's at least one main word
            $name_parts = array_filter($name_parts);
            if (count($name_parts) < 1) {
                // Fallback to a simple name if complex generation fails
                $name_parts[] = $starts[array_rand($starts)];
                $name_parts[] = $middles[array_rand($middles)];
            }


            // Capitalize each word and join them
            $final_name = implode(' ', array_map('ucfirst', array_map('strtolower', $name_parts)));

            // Clean up common double words (e.g., "Park Park", "Heights Heights")
            $final_name = preg_replace('/\b(\w+)\s+\1\b/i', '$1', $final_name);
            $final_name = trim($final_name);

            $names[] = $final_name;
        }
        return array_unique($names); // Ensure uniqueness, especially for smaller counts
    }

    private function getSouthAfricanSuburbs(): array
    {
        return [
            'Gauteng' => [
                'Johannesburg' => [
                    'Sandton', 'Rosebank', 'Melville', 'Greenside', 'Bryanston',
                    'Fourways', 'Randburg', 'Soweto', 'Braamfontein', 'Parkhurst',
                    'Killarney', 'Linden', 'Northcliff', 'Rivonia', 'Sunninghill',
                    'Illovo', 'Fairland', 'Bedfordview', 'Edenvale', 'Kempton Park',
                    'Midrand', 'Kyalami', 'Lonehill', 'Germiston', 'Roodepoort',
                    'Florida', 'Ennerdale', 'Orange Farm', 'Alexandra', 'Houghton',
                    'Norwood', 'Linksfield', 'Dunkeld', 'Hyde Park'
                ],
                'Pretoria' => [
                    'Hatfield', 'Arcadia', 'Centurion', 'Brooklyn', 'Menlo Park',
                    'Faerie Glen', 'Silver Lakes', 'Waterkloof', 'Moreleta Park',
                    'Garsfontein', 'Lynnwood', 'Pretoria North', 'Mamelodi',
                    'Soshanguve', 'Atteridgeville', 'Wierdapark', 'Eldoraigne'
                ],
                'Ekangala' => [
                    'Bronkhorstspruit', 'Cullinan', 'Rayton'
                ],
            ],
            'Western Cape' => [
                'Cape Town' => [
                    'Claremont', 'Rondebosch', 'Newlands', 'Sea Point', 'Camps Bay',
                    'Hout Bay', 'Constantia', 'Bishopscourt', 'Pinelands', 'Kenilworth',
                    'Observatory', 'Woodstock', 'Bo-Kaap', 'Gardens', 'Oranjezicht',
                    'Table View', 'Bloubergstrand', 'Plattekloof', 'Durbanville',
                    'Bellville', 'Parow', 'Mitchells Plain', 'Khayelitsha', 'Gugulethu',
                    'Wynberg', 'Diep River', 'Fish Hoek', 'Kommetjie', 'Scarborough',
                    'Llandudno', 'Fresnaye', 'Bantry Bay', 'Mouille Point', 'Green Point'
                ],
                'Stellenbosch' => [
                    'Paradyskloof', 'Dalsig', 'Die Boord', 'Cloetesville'
                ],
                'George' => [
                    'Wilderness', 'Pacaltsdorp', 'Blanco', 'Heatherlands'
                ],
            ],
            'KwaZulu-Natal' => [
                'Durban' => [
                    'Umhlanga Rocks', 'Ballito', 'Durban North', 'Morningside', 'Glenwood',
                    'Berea', 'Musgrave', 'Westville', 'Pinetown', 'Hillcrest',
                    'Amanzimtoti', 'Phoenix', 'Chatsworth', 'Umlazi', 'KwaMashu',
                    'Isipingo', 'La Lucia', 'Cowies Hill', 'Kloof'
                ],
                'Pietermaritzburg' => [
                    'Scottsville', 'Wembley', 'Hayfields', 'Northdale'
                ],
            ],
            'Eastern Cape' => [
                'Gqeberha (Port Elizabeth)' => [
                    'Summerstrand', 'Walmer', 'Mill Park', 'Central', 'Kabega Park',
                    'Motherwell', 'Bethelsdorp', 'New Brighton', 'Lorraine', 'Kamma Park'
                ],
                'East London' => [
                    'Nahoon', 'Vincent', 'Beacon Bay', 'Mdantsane', 'Gompo'
                ],
            ],
            'Free State' => [
                'Bloemfontein' => [
                    'Waverley', 'Langenhoven Park', 'Heuwelsig', 'Willows', 'Mangaung'
                ],
            ],
            'Mpumalanga' => [
                'Mbombela (Nelspruit)' => [
                    'Steiltes', 'West Acres', 'Sonheuwel', 'KaNyamazane'
                ],
                'Emalahleni (Witbank)' => [
                    'Tasbet Park', 'Ext 16', 'Klarinet', 'Reyno Ridge'
                ],
            ],
            'Limpopo' => [
                'Polokwane' => [
                    'Bendor', 'Fauna Park', 'Flora Park', 'Seshego'
                ],
            ],
            'North West' => [
                'Rustenburg' => [
                    'Waterfall Mall', 'Cashan', 'Tlhabane', 'Geelhout Park'
                ],
                'Potchefstroom' => [
                    'Baillie Park', 'Grimbeek Park', 'Die Bult'
                ],
            ],
            'Northern Cape' => [
                'Kimberley' => [
                    'Kimberley Central', 'Hadison Park', 'Galeshewe', 'Roodepan'
                ],
            ],
        ];
    }

    public function addressElement(): object
    {
        $suburbsArray = $this->getSouthAfricanSuburbs();
        $randomProvince = array_rand($suburbsArray);
        $citiesInProvince = $suburbsArray[$randomProvince];

        $randomCity = array_rand($citiesInProvince);
        $suburbsInCity = $citiesInProvince[$randomCity];

        return (object)[
            'province' => $randomProvince,
            'city' => $randomCity,
            'suburb' => $suburbsInCity[array_rand($suburbsInCity)],
        ];
    }
}
