<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Author;
use Faker\Generator as Faker;

$factory->define(Author::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->afterCreating('App\Author', function ($author, $faker) {
   $author->profile()->save(factory('App\Profile')->make()); 
});

$factory->afterMaking('App\Author', function ($author, $faker) {
   $author->profile()->save(factory('App\Profile')->make()); 
});