<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*DB::table('candidates')->insert(
            [
                [
                    'reg'                       => '', 
                    'user_id'                   => '', 
                    'cadre_category'            => '', 
                    'general_merit_position'    => 0, 
                    'technical_merit_position'  => 0, 
                    'technical_passed_cadres'   => '', 
                    'choice_list'               => '', 
                    'has_quota'                 => 0, 
                    'quota_info'                => '',
                    'created_at' => Carbon::now(), 
                    'updated_at' => Carbon::now() 
                ],
        ]);*/

        $candidates = [
            [
                'reg_no' => '20250001',
                'user_id' => 'USR1A2BC34',
                'cadre_category' => 'GG',
                'general_merit_position' => 23,
                'technical_merit_position' => null,
                'choice_list' => 'FORN ADMN PLIC ANSA AUDT',
                'quota' => ['CFF' => true, 'EM' => false, 'PHC' => false]
            ],
            [
                'reg_no' => '20250002',
                'user_id' => 'USR9X8Y7Z6',
                'cadre_category' => 'TT',
                'general_merit_position' => null,
                'global_tech_merit' => 230,
                'technical_merit_position' => ['RLME' => 6, 'ROCE' => 7],
                'choice_list' => 'RLME ROCE AGEX',
                'quota' => ['CFF' => true, 'EM' => false, 'PHC' => false]
            ],
            [
                'reg_no' => '20250003',
                'user_id' => 'USR7K6L5M4',
                'cadre_category' => 'GT',
                'general_merit_position' => 12,
                'global_tech_merit' => 124,
                'technical_merit_position' => ['ROME' => 5],
                'choice_list' => 'ADMN ROME FORN',
                'quota' => ['CFF' => true, 'EM' => false, 'PHC' => false]
            ],
            [
                'reg_no' => '20250004',
                'user_id' => 'USR4P3Q2R1',
                'cadre_category' => 'GG',
                'general_merit_position' => 2,
                'technical_merit_position' => null,
                'choice_list' => 'TAXN ADMN FORN AUDT',
                'quota' => ['CFF' => false, 'EM' => false, 'PHC' => true]
            ],
            [
                'reg_no' => '20250005',
                'user_id' => 'USR5T6U7V8',
                'cadre_category' => 'TT',
                'general_merit_position' => null,
                'global_tech_merit' => 2300,
                'technical_merit_position' => ['ROCE' => 9, 'ROME' => 9],
                'choice_list' => 'RLME ROCE ROME',
                'quota' => ['CFF' => false, 'EM' => true, 'PHC' => false]
            ],
            [
                'reg_no' => '20250006',
                'user_id' => 'USR3A4B5C6',
                'cadre_category' => 'GT',
                'general_merit_position' => 18,
                'global_tech_merit' => 15,
                'technical_merit_position' => ['RLME' => 6, 'ROCE' => 10],
                'choice_list' => 'ROME RLME ADMN ROCE AUDT',
                'quota' => ['CFF' => true, 'EM' => false, 'PHC' => false]
            ],
            [
                'reg_no' => '20250007',
                'user_id' => 'USR1Z2Y3X4',
                'cadre_category' => 'GG',
                'general_merit_position' => 9,
                'technical_merit_position' => null,
                'global_tech_merit' => 38,
                'choice_list' => 'INPO ADMN FORN ANSR',
                'quota' => ['CFF' => true, 'EM' => true, 'PHC' => true]
            ],
            [
                'reg_no' => '20250008',
                'user_id' => 'USR9Q8W7E6',
                'cadre_category' => 'TT',
                'general_merit_position' => null,
                'global_tech_merit' => 2,
                'technical_merit_position' => ['ROCE' => 2],
                'choice_list' => 'ROCE RLME ROME',
                'quota' => ['CFF' => false, 'EM' => false, 'PHC' => true]
            ],
            [
                'reg_no' => '20250009',
                'user_id' => 'USR8L7M6N5',
                'cadre_category' => 'GT',
                'general_merit_position' => 35,
                'global_tech_merit' => 12,
                'technical_merit_position' => ['AGEX' => 8],
                'choice_list' => 'FORN ADMN AGEX',
                'quota' => ['CFF' => true, 'EM' => false, 'PHC' => true]
            ],
            [
                'reg_no' => '20250010',
                'user_id' => 'USR5C4D3E2',
                'cadre_category' => 'GG',
                'general_merit_position' => 1,
                'technical_merit_position' => null,
                'choice_list' => 'FORN ADMN AUDT',
                'quota' => ['CFF' => false, 'EM' => true, 'PHC' => true]
            ],
            [
                'reg_no' => '20250011',
                'user_id' => 'USR1G2H3I4',
                'cadre_category' => 'TT',
                'general_merit_position' => null,
                'global_tech_merit' => 18,
                'technical_merit_position' => ['MEDI' => 5],
                'choice_list' => 'DENT MEDI',
                'quota' => ['CFF' => true, 'EM' => false, 'PHC' => false]
            ],
            [
                'reg_no' => '20250012',
                'user_id' => 'USR9B8C7D6',
                'cadre_category' => 'GT',
                'general_merit_position' => 6,
                'global_tech_merit' => 19,
                'technical_merit_position' => ['ROME' => 1],
                'choice_list' => 'ROME ADMN COPG ANSR',
                'quota' => ['CFF' => false, 'EM' => true, 'PHC' => false]
            ],
            [
                'reg_no' => '20250013',
                'user_id' => 'USR7R6S5T4',
                'cadre_category' => 'GG',
                'general_merit_position' => 10,
                'technical_merit_position' => null,
                'choice_list' => 'ADMN INPO FORN',
                'quota' => ['CFF' => false, 'EM' => false, 'PHC' => true]
            ],
            [
                'reg_no' => '20250014',
                'user_id' => 'USR2M3N4O5',
                'cadre_category' => 'TT',
                'general_merit_position' => null,
                'global_tech_merit' => 125,
                'technical_merit_position' => ['RLME' => 12],
                'choice_list' => 'ROME RLME MEDI',
                'quota' => ['CFF' => false, 'EM' => true, 'PHC' => true]
            ],
            [
                'reg_no' => '20250015',
                'user_id' => 'USR4W3E2R1',
                'cadre_category' => 'GT',
                'general_merit_position' => 22,
                'global_tech_merit' => 7,
                'technical_merit_position' => ['ROME' => 7],
                'choice_list' => 'RLME ROME ADMN COPG',
                'quota' => ['CFF' => false, 'EM' => false, 'PHC' => true]
            ],
            [
                'reg_no' => '20250016',
                'user_id' => 'USR6H5J4K3',
                'cadre_category' => 'GG',
                'general_merit_position' => 4,
                'technical_merit_position' => null,
                'choice_list' => 'PLIC FORN ADMN',
                'quota' => ['CFF' => true, 'EM' => false, 'PHC' => false]
            ],
            [
                'reg_no' => '20250017',
                'user_id' => 'USR8Q7W6E5',
                'cadre_category' => 'TT',
                'general_merit_position' => null,
                'global_tech_merit' => 185,
                'technical_merit_position' => ['ROME' => 11, 'RLME' => 3],
                'choice_list' => 'ROME MEDI RLCE RLME',
                'quota' => ['CFF' => false, 'EM' => true, 'PHC' => false]
            ],
            [
                'reg_no' => '20250018',
                'user_id' => 'USR2V3B4N5',
                'cadre_category' => 'GT',
                'general_merit_position' => 11,
                'global_tech_merit' => 3,
                'technical_merit_position' => ['AGEX' => 30, 'RLCE' => 4],
                'choice_list' => 'AGEX FORN RLCE ROME ADMN',
                'quota' => ['CFF' => false, 'EM' => false, 'PHC' => true]
            ],
            [
                'reg_no' => '20250019',
                'user_id' => 'USR1C2V3B4',
                'cadre_category' => 'GG',
                'general_merit_position' => 49,
                'technical_merit_position' => null,
                'choice_list' => 'ADMN FODG INPO ANSR',
                'quota' => ['CFF' => false, 'EM' => true, 'PHC' => true]
            ],
            [
                'reg_no' => '20250020',
                'user_id' => 'USR9P8O7I6',
                'cadre_category' => 'TT',
                'general_merit_position' => null,
                'global_tech_merit' => 925,
                'technical_merit_position' => ['ROME' => 23, 'PWCE' => 3],
                'choice_list' => 'ROME RLCE PWCE',
                'quota' => ['CFF' => true, 'EM' => false, 'PHC' => true]
            ],
        ];

        foreach ($candidates as $c) {

            DB::table('candidates')->insert([
                'reg'                       => $c['reg_no'],
                'user_id'                   => $c['user_id'],
                'cadre_category'            => $c['cadre_category'],
                'general_merit_position'    => $c['general_merit_position'] ?? 0,

                'technical_merit_position'  => $c['global_tech_merit'] ?? null,

                'technical_passed_cadres'   => is_array($c['technical_merit_position'])
                                                ? json_encode($c['technical_merit_position'])
                                                : null,

                'choice_list'               => $c['choice_list'],

                'has_quota'                 => isset($c['quota']) ? 1 : 0,

                'quota_info'                => json_encode($c['quota']),

                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
            ]);

        } // end foreach
    }
}
