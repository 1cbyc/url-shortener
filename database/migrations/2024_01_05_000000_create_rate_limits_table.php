<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('rate_limits', function ($table) {
    $table->increments('id');
    $table->string('ip', 45);
    $table->timestamp('created_at');
}); 