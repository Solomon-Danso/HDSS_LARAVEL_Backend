<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\Authentic;
use Carbon\Carbon;

class UpdateAccountStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-account-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = Authentic::all();

        foreach ($users as $user) {
            if ($user->expirationDate < Carbon::now() && $user->IsLoggedIn == true) {
                $user->Status = 'Offline';
                $user->LastLogout = Carbon::now();
                $user->IsLoggedIn = false;
               
                $user->SessionID = "";
                $timeDifference = $user->LastLogout->diff($user->LastLogin);
                $user->TimeSpent = $timeDifference->format('%d days %h hours %i minutes %s seconds');




                $user->save();
            }
        }

        $this->info('Account statuses updated successfully.');
    }


}
