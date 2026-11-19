<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {--name= : The administrator name} {--email= : The administrator email}';

    protected $description = 'Create an administrator account';

    public function handle(): int
    {
        $input = [
            'name' => $this->option('name') ?: $this->ask('Administrator name'),
            'email' => $this->option('email') ?: $this->ask('Administrator email'),
            'password' => $this->secret('Password (minimum 8 characters)'),
            'password_confirmation' => $this->secret('Confirm password'),
        ];

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return self::FAILURE;
        }

        User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'is_admin' => true,
        ]);

        $this->info('Administrator account created successfully.');

        return self::SUCCESS;
    }
}
