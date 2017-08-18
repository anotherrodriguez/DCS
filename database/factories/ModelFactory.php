<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Customer::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->company
    ];
});

$factory->define(App\Process::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->word
    ];
});

$factory->define(App\Type::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->word
    ];
});


$factory->define(App\Part::class, function (Faker\Generator $faker) {

    return [
        'part_number' => $faker->numerify('###-###-###-#'),
        'customer_id' => function () {
            return factory(App\Customer::class)->create()->id;
        }
    ];
});

$factory->define(App\Document::class, function (Faker\Generator $faker) {

    return [
        'part_id' => function () {
            return factory(App\Part::class)->create()->id;
        },
        'type_id' => function () {
            return factory(App\Type::class)->create()->id;
        },
        'process_id' => function () {
            return factory(App\Process::class)->create()->id;
        },
        'document_number' => $faker->regexify('[A-Z]+[A-Z]-[0-9][0-9][0-9][0-9]+[A-Z]')
    ];
});

$factory->define(App\Revision::class, function (Faker\Generator $faker) {

    return [
        'document_id' => function () {
            return factory(App\Document::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'description' => $faker->text($maxNbChars = 200), 
        'revision_date' => $faker->date,
        'revision' => $faker->randomLetter, 
        'change_description' => $faker->text($maxNbChars = 200) 
    ];
});


