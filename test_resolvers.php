<?php
$resolvers = \Illuminate\Database\Eloquent\Model::$relationResolvers;
$has = isset($resolvers[App\Models\Sanctum\PersonalAccessToken::class]['_id']);
var_dump($has);
if (!$has) {
    echo "Resolvers:\n";
    print_r(array_keys($resolvers));
}
