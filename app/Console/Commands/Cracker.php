<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Cracker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kata:cracker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'https://codingdojo.org/kata/CodeCracker/';

    protected $secret,$text;
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
        $this->secret = $this->secret("Informe a chave");
        $this->secret = md5($this->secret);
        $this->secret = substr($this->secret,1,16);
        $this->text = $this->ask("Informe a mensagem");
        $function = $this->choice(
            "Qual a operação",
            ["crypt","decrypt"],
            0
        );
        switch($function){
            case "crypt":
                $result = $this->ssl_crypt();
            break;
            case "decrypt":
                $result = $this->ssl_decrypt();
            break;
        }
        $this->info($result);
    }

    public function ssl_decrypt(){
        $open_ssl = openssl_decrypt(
            base64_decode($this->text),
            'AES-128-CBC',
            $this->secret,
            0,
            $this->secret
        );
        return $open_ssl;
    }

    public function ssl_crypt(){

        $open_ssl = openssl_encrypt(
            json_encode($this->text),
            'AES-128-CBC',
            $this->secret,
            0,
            $this->secret
        );
        return base64_encode($open_ssl);
    }
}
