<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('clicks', function ($table) {
    $table->increments('id');
    $table->unsignedInteger('url_id');
    $table->text('referrer')->nullable();
    $table->string('ip', 45);
    $table->string('country', 2)->nullable();
    $table->timestamp('created_at');
}); 