<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AnagramCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kata:anagram';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'https://codingdojo.org/kata/Anagram/';


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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $word = "documenting";
        $letters = str_split($word);
        $this->wordConstructor($letters);
        $this->info(print_r($letters,true));
    }

    private function wordConstructor(&$letters){
        $letters = collect($letters)->unique()
        ->crossJoin($letters)->map(function($item){
            return implode('',$item);
        })->filter(function($item){
            return strlen($item) <= 8;
        })->unique()->values()->toArray();
    }

    //pxjon4:
    //const getAnagram = (word) => {
    //    const letters = word.split("");
    //    const matriz = letters.map((letter1) => letters.map( (letter2) => letter1+letter2 ));
    //}
}
