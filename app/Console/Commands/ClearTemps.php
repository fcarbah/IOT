<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

class ClearTemps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temps:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove temperature data older than 3 hours';

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
        \Log::info('Clear Temps Running');
        \DB::table('temperatures')
            ->where('created_at','<=',Carbon::now()->subHours(3))
            ->delete();
        \Log::info('Clear Temps Finished');
    }
}
