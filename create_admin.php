<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = new \Botble\ACL\Models\User();
$user->first_name = 'Super';
$user->last_name = 'Admin';
$user->email = 'admin@admin.com';
$user->username = 'admin';
$user->password = bcrypt('12345678');
$user->super_user = 1;
$user->manage_supers = 1;
$user->save();

app(\Botble\ACL\Services\ActivateUserService::class)->activate($user);

echo "Super admin created successfully!\n";
echo "Email: admin@admin.com\n";
echo "Password: 12345678\n";
