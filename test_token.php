<?php
$user = App\Models\User::first();
$tokenResult = $user->createToken('test_final_token');
echo "NewAccessToken Class: " . get_class($tokenResult) . "\n";
echo "PersonalAccessToken Class inside: " . get_class($tokenResult->accessToken) . "\n";
echo "Plain text token: " . $tokenResult->plainTextToken . "\n";
