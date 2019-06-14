<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Audio;

class UpdateFileMetadata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audio:updatefile {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update file metadata from a date (given as YYYY-MM-DD)';

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
        $date = $this->argument('date');
        $timestamp = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->startOfDay()->timestamp;
        $audios = Audio::where('import_date', '>=', $timestamp)->get();
        foreach($audios as $audio) {

            $audio->updateFileMetadata();
        }
    }
}
