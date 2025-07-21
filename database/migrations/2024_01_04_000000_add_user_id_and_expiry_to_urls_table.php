<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->table('urls', function ($table) {
    $table->unsignedInteger('user_id')->nullable();
    $table->timestamp('expires_at')->nullable();
}); 