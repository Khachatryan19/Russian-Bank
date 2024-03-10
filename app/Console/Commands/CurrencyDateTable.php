<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Services\FileService\XMLService;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CurrencyDateTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:currency-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command takes data from xml file and creates table by date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $xml = new XMLService(env('CURRENCY_API'));
        $xmlArray = $xml->read();
        $date = $xml->getDate();

        if (Schema::hasTable('currencies_'.$date)) {
            $this->line('<fg=red>Table already exists</>');
            return;
        }

        $this->createTable($date);
        $currency = new Currency();
        $currency->setTable('currencies_'.$date);

        foreach ($xmlArray['Valute'] as $xml) {
            $currency->create([
                'id' => $xml['@attributes']['ID'],
                'NumCode' => $xml['NumCode'],
                'CharCode' => $xml['CharCode'],
                'Nominal' => $xml['Nominal'],
                'Name' => $xml['Name'],
                'Value' => $xml['Value'],
                'VunitRate' => $xml['VunitRate']
            ]);
        }

        $this->line('<fg=green>Table has been created</>');
    }

    private function createTable($tableName): void
    {
        if (!Schema::hasTable($tableName)) {
            Schema::create("currencies_$tableName", function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('NumCode');
                $table->string('CharCode');
                $table->string('Nominal');
                $table->string('Name');
                $table->string('Value');
                $table->string('VunitRate');
                $table->timestamps();
            });
        }
    }
}
