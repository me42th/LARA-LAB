<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LookAndSay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dojo:lookandsay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'https://dojopuzzles.com/problems/sequencia-look-and-say/';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    *  A seqüência de números inteiros obtido a partir de um dígito (qualquer valor entre 1 e 9)
    *  onde o termo seguinte é obtido pela descrição do termo anterior é definida como uma seqüência look-and-say.
    *  Por exemplo, tendo como dígito inicial 1:
    *  1 é descrito como "um 1" ou 11;
    *  11 é descrito como "dois 1" ou 21;
    *  21 é descrito como "um 2, um 1" ou 1211;
    *  1211 é descrito como "um 1, um 2, dois 1" ou 111221;
    *  111221 é descrito como "três 1, dois 2, um 1" ou 312211.
    *  Para dígitos maiores ou iguais a 2, a seqüência é tem o seguinte formato: d, 1d, 111d, 311d, 13211d, 111312211d (sendo d o dígito inicial).
    *  Dado o dígito inicial da seqüência, determine a soma de todos os dígitos do 50º elemento da seqüência.
   */
    public function handle()
    {
        $this->menu();
        $this->info("Thats all folks");
    }

    private function menu(){
        while(true){
            system("clear");
            $number = $this->ask("Informe um numero ou 's' para sair");
            if($number == 's'){
                break;
            }
            $this->makeLookAndSaySequence($number);

        }
    }

    private function makeLookAndSaySequence($number){
        /*
        *  1 é descrito como "um 1" ou 11;
        *  11 é descrito como "dois 1" ou 21;
        *  21 é descrito como "um 2, um 1" ou 1211;
        *  1211 é descrito como "um 1, um 2, dois 1" ou 111221;
        *  111221 é descrito como "três 1, dois 2, um 1" ou 312211.
        */
        $qtd = 10;
        $bar = $this->output->createProgressBar($qtd);
        system("clear");
        $bar->start();
        $this->info(PHP_EOL.$number);
        for($i = 0; $i < $qtd;$i++){
            sleep(1);
            system("clear");
            $bar->advance();
            $this->getNextNumber($number);
            $this->info(PHP_EOL.$number);
        }
        sleep(1);
        $bar->finish();
    }

    private function getNextNumber(&$number){
        $numbers = str_split($number);
        $this->chunk($numbers);
    }

    private function chunk($numbers){
        $array = [];
        $inner = [];
        $buffer = $numbers[0];
        for($i = 0;$i <= count($numbers);$i++){
            if($number != $buffer){
                $buffer = $number;
                $array[] = $inner;
                $inner = [];
            } else {
                $inner[] = $number;
            }
        }
        if(count($array) == 0){
            $array[] = $inner;
        }
        \Log::info(print_r($array,true));
    }
    //1
    //11
    //21
    //1211
    //111221
    //312211
    //13112221

}
