<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RebMlsListing extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'mls', 'price', 'address', 'location', 'latitude',
        'latitude',
        'longitude',
        'general',
        'aditional',
        'schools',
        'thumbnail',
        'images',
        'overview',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

}
