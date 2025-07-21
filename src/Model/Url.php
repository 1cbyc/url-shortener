<?php

namespace UrlShortener\Model;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $table = 'urls';
    public $timestamps = false;
    protected $fillable = ['code', 'url', 'created_at'];
} 