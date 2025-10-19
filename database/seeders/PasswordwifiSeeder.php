<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use League\Csv\Statement;

class PasswordwifiSeeder  extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try{
            $filepath = database_path('seeders/data/'.config("midtransAPI.csvname"));

            if (!file_exists($filepath)) throw new \RuntimeException("CSV file not found: $filepath");


            $csv = Reader::from($filepath);
            $csv->setHeaderOffset(0);
            $records = (new Statement())->process($csv);

            if (iterator_count($records) === 0) throw new \RuntimeException("CSV file is empty: $filepath");

            foreach ($records as $record) {
                DB::table('passwordwifi')->insert([
                    'nim' => $record['nim'],
                    'birth_date' => $record['birth_date'],
                ]);
            };
        }
        catch(Exception $err){

            DB::table('passwordwifi')->insert([
                'nim' => "23104410000",
                'birth_date' => "20031314"
            ]);
            throw new \RuntimeException($err->getMessage());
        };

    }
}
