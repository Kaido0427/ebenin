<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdatePassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-password {email} {password}';
    protected $description = 'Mettre à jour le mot de passe d\'un utilisateur par email';



    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Récupérer les arguments
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Récupérer l'utilisateur par email
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('Utilisateur non trouvé.');
            return;
        }

        // Mettre à jour le mot de passe
        $user->password = Hash::make($password);
        $user->save();

        $this->info('Mot de passe mis à jour avec succès pour : ' . $email);
    }
}
