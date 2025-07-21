<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('urls', function ($table) {
    $table->increments('id');
    $table->string('code')->unique();
    $table->text('url');
    $table->timestamp('created_at');
}); 