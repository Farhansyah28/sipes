<?php
$user = App\Models\User::firstOrNew(['username' => 'demo']);
$user->name = 'Akun Demo';
$user->email = 'demo@pesantren.com';
$user->password = bcrypt('demo');
$user->tenant_id = 1;
$user->save();
$user->assignRole('Super Admin');
echo 'Demo user created';
