<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateSanctumToken extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:generate {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Sanctum token for a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $user = User::find($this->argument('user_id'));

        if (!$user) {
            $this->error('User not found.');
            return;
        }
        $token = $user->createToken('external-app')->plainTextToken;
        $this->info('Token generated successfully:');
        $this->line($token);
    }
}
