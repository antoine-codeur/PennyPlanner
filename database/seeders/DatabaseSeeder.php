<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role; // Assurez-vous que vous avez importé le modèle Role
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Assurez-vous que les rôles existent
        Role::firstOrCreate(['id' => 1], ['name' => 'user']);
        Role::firstOrCreate(['id' => 2], ['name' => 'admin']);

        // Créer un utilisateur de test
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => 1, // Assurez-vous que cette valeur correspond à un rôle valide
        ]);
    }
}
