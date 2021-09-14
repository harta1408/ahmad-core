<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Http\Controllers\Service\BimbinganService;


class BimbinganCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bimbingan:pengingat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'mengirimkan pengingat ke santri';

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
     * @return mixed
     */
    public function handle()
    {
        $bimservice=new BimbinganService;
        $bimservice->pengingatBimbingan();
    }
}
