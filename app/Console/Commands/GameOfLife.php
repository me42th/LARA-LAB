<?php
namespace App\Console\Commands;
define('DEAD','□');
define('ALIVE','▣');
define('D','□');
define('A','▣');

use Illuminate\Console\Command;

class GameOfLife extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kata:gameoflife';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'https://codingdojo.org/kata/GameOfLife/';

    protected $petri = [
        [D,D,D,D,D,D,D,D,D,D,D,D],
        [D,D,D,D,D,D,D,D,D,D,D,D],
        [D,D,D,D,D,D,D,D,D,D,D,D],
        [D,D,D,D,A,A,A,A,A,D,D,D],
        [D,D,D,D,D,D,D,D,D,D,D,D],
        [D,D,D,D,D,D,D,D,D,D,D,D],
        [D,D,D,D,D,D,D,D,D,D,D,D],
        [D,D,D,D,D,D,D,D,D,D,D,D]
    ];
    protected $buffer = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    // As regras são simples e elegantes:


    public function handle()
    {
        while(true){
            system("clear");
            $this->printPetri();
            $this->processPetri();
            sleep(1);
        }
    }

    private function printPetri(){
        foreach($this->petri as $line){
            $this->info(implode('|',$line));
        }
    }

    private function processPetri(){
        $this->buffer = $this->petri;
        for($i = 0;$i < count($this->petri);$i++){
            for($j = 0;$j < count($this->petri[$i]);$j++){
                $this->nextCellState($i,$j);
            }
        }
        $this->petri = $this->buffer;
    }

    private function nextCellState($i,$j){
        $cell = $this->petri[$i][$j];
        if($cell === DEAD){
            $cell = $this->nextDeadCellState($i,$j);
        } else {
            $cell = $this->nextAliveCellState($i,$j);
        }
        $this->buffer[$i][$j] = $cell;
    }

    private function nextDeadCellState($i,$j){
        // 3- Qualquer célula morta com exatamente três vizinhos vivos se torna uma célula viva.
        $neighborhood_state = $this->getNeighborhoodState($i,$j);
        return $neighborhood_state->alive == 3?ALIVE:DEAD;
    }

    private function nextAliveCellState($i,$j){
        // 1- Qualquer célula viva com menos de dois vizinhos vivos morre de solidão.
        // 2- Qualquer célula viva com mais de três vizinhos vivos morre de superpopulação.
        // 4- Qualquer célula viva com dois ou três vizinhos vivos continua no mesmo estado para a próxima geração.
        $neighborhood_state = $this->getNeighborhoodState($i,$j);
        if($neighborhood_state->alive < 2 || $neighborhood_state->alive > 3){
            return DEAD;
        } else {
            return ALIVE;
        }
    }

    private function getNeighborhoodState($i,$j){
        $line_limit = count($this->petri);
        $column_limit = count($this->petri[$i]);
        $previous_line = $i-1;
        $next_line = $i+1;
        $previous_column = $j-1;
        $next_column = $j+1;
        $neighborhood = [];
        $neighborhood[] = $this->safeNeighborState($i,$previous_column);
        $neighborhood[] = $this->safeNeighborState($i,$next_column);
        $neighborhood[] = $this->safeNeighborState($previous_line,$previous_column);
        $neighborhood[] = $this->safeNeighborState($previous_line,$j);
        $neighborhood[] = $this->safeNeighborState($previous_line,$next_column);
        $neighborhood[] = $this->safeNeighborState($next_line,$previous_column);
        $neighborhood[] = $this->safeNeighborState($next_line,$j);
        $neighborhood[] = $this->safeNeighborState($next_line,$next_column);
        $neighborhood_state = new \stdClass;
        $neighborhood = collect($neighborhood);
        $neighborhood_state->dead = $neighborhood->filter(function($item){
            return $item === DEAD;
        })->count();
        $neighborhood_state->alive = $neighborhood->filter(function($item){
            return $item === ALIVE;
        })->count();
        \Log::info($i." ".$j);
        \Log::info($previous_line." ".$next_line." ".$previous_column." ".$next_column);
        \Log::info(print_r($neighborhood,true));
        \Log::info(print_r($neighborhood_state,true));
        return $neighborhood_state;
    }

    private function safeNeighborState($i,$j){
        try{
            $return = $this->getNeighborState($i,$j);
        } catch (\Exception $ex){
            $return = null;
        }
        return $return;
    }
    private function getNeighborState($i,$j){
        return $this->petri[$i][$j];
    }
}
