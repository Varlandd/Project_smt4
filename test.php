<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

try {
    $email = 'test_'.rand().'@example.com';
    $user = User::create([
        'name' => 'Test User ' . rand(),
        'email' => $email,
        'password' => Hash::make('password123'),
        'role' => 'user',
    ]);
    echo "User created successfully. ID is a: " . gettype($user->_id) . "\n";
    
    $cleanUser = User::where('email', $email)->first();
    echo "Re-fetched user _id Type: " . gettype($cleanUser->_id) . "\n";
    echo "Re-fetched user _id Value: " . $cleanUser->_id . "\n";
    
    Auth::login($cleanUser);
    echo "Login successful with clean user.\n";
    
    $id = $user->id;
    try {
        serialize($id);
        echo "user->id is serializable.\n";
    } catch (\Exception $e) {
        echo "user->id serialization failed: " . $e->getMessage() . "\n";
    }
    
    try {
        serialize($identifier);
        echo "Cast identifier is serializable.\n";
    } catch (\Exception $e) {
        echo "Serialization still failed: " . $e->getMessage() . "\n";
    }

    echo "getKey Type: " . gettype($user->getKey()) . "\n";
    echo "Attributes: " . json_encode($user->getAttributes()) . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
