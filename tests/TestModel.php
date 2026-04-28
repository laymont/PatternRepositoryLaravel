<?php

namespace Laymont\PatternRepository\Tests;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $table = 'test_models';

    protected $fillable = ['name', 'email', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public $timestamps = true;

    protected $keyType = 'int';

    public $incrementing = true;
}