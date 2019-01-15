<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class UpdateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $url = 'https://www.warwicksu.com/membershipapi/listmembers/' . env('WARWICK_SU_API_KEY');
        $client = new \GuzzleHttp\Client();
        $result = $client->request('GET', $url);

        $xml = $result->getBody()->getContents();
	$converted_xml = \Vyuldashev\XmlToArray\XmlToArray::convert($xml);
        $members = $converted_xml['MembershipAPI']['Member'];
        foreach($members as $member) {
            $user = User::where('username', $member['UniqueID'])->first();
            if(is_null($user)) {
                $user = new User;
                $user->username = $member['UniqueID'];
                $user->save();
                echo 'Created account for: ' . $member['UniqueID'];
            }
        }
    }
}
