<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
class PermissionsDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        // app()[PermissionsDemoSeeder::class]->forgetCachedPermissions();
        // php artisan migrate:fresh --seed --seeder=PermissionsDemoSeeder
        // create permissions
        Permission::create(['name' => 'donasi produk']);
        Permission::create(['name' => 'perkembangan santri']);
        Permission::create(['name' => 'ikut bimbingan']);
        Permission::create(['name' => 'uji materi']);
        Permission::create(['name' => 'dampingi materi']);
        Permission::create(['name' => 'kirim referral']);
        Permission::create(['name' => 'lihat laporan']);
        Permission::create(['name' => 'daftar donasi']);
        Permission::create(['name' => 'otorisasi santri']);
        Permission::create(['name' => 'otorisasi pendamping']);
        Permission::create(['name' => 'buat berita']);
        Permission::create(['name' => 'buat pesan']);
        Permission::create(['name' => 'kirim pesan']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'donatur']);
        $role1->givePermissionTo('donasi produk');
        $role1->givePermissionTo('perkembangan santri');
        $role1->givePermissionTo('kirim referral');

        $role2 = Role::create(['name' => 'santri']);
        $role2->givePermissionTo('ikut bimbingan');
        $role2->givePermissionTo('uji materi');
        $role2->givePermissionTo('kirim referral');

        $role3 = Role::create(['name' => 'pendamping']);
        $role3->givePermissionTo('dampingi materi');
        $role3->givePermissionTo('kirim referral');

        $role4 = Role::create(['name' => 'manajer']);
        $role4->givePermissionTo('lihat laporan');
        $role4->givePermissionTo('kirim pesan');
        $role4->givePermissionTo('daftar donasi');
        $role4->givePermissionTo('otorisasi santri');
        $role4->givePermissionTo('otorisasi pendamping');

        $role5 = Role::create(['name' => 'helpdesk']);
        $role5->givePermissionTo('buat berita');
        $role5->givePermissionTo('buat pesan');
        $role5->givePermissionTo('otorisasi santri');
        $role5->givePermissionTo('otorisasi pendamping');
        $role5->givePermissionTo('kirim pesan');


        $role9 = Role::create(['name' => 'super-admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        // $user = \App\Models\User::factory()->create([
        //     'name' => 'Example User',
        //     'email' => 'test@example.com',
        // ]);
        // $user->assignRole($role1);

        // $user = \App\Models\User::factory()->create([
        //     'name' => 'Example Admin User',
        //     'email' => 'admin@example.com',
        // ]);
        // $user->assignRole($role2);

        $user = \App\Models\User::create([
            'name' => 'Wawan Hartawan',
            'email' => 'harta@gimanamas.com',
            'password' => Hash::make('admin127'),
            'approve' => '1',
            'email_verified_at' =>date("Y-m-d H:i:s"),
        ]);
       
        $user->assignRole($role9); 
    }
}
