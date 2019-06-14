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
    protected $signature = 'audio:updatefile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update file metadata from a date';

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
        $date = $this->ask('Give a date to update files from. In the format YYYY-MM-DD.');
        $timestamp = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->startOfDay()->timestamp;
        $audios = Audio::where('import_date', '>=', $timestamp)->get();
        foreach($audios as $audio) {
            $this->info('Updating the track: ' . $audio->title . ' by ' . $audio->artist->name);
            $audio->updateFileMetadata();
        }
    }
}
