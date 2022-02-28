<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class AssignRoleAndPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:rolesAndPermissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign Roles to first created user.';

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
        // assign admin role
        $user = User::where('email', 'admin@example.org')->first();
        if ($user) {
            $user->assignRole('admin');
        }
        // assign user role
        $user = User::where('email', 'user@example.org')->first();
        if ($user) {
            $user->assignRole('user');
        }

        return 0;
    }
}
