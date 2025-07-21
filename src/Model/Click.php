<?php

namespace UrlShortener\Model;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    protected $table = 'clicks';
    public $timestamps = false;
    protected $fillable = ['url_id', 'referrer', 'ip', 'country', 'created_at'];
} 