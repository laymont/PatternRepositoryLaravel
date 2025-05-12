<?php

declare(strict_types=1);

use Laymont\PatternRepository\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class DummyModel extends Model {
    protected $table = 'users';
    protected $guarded = [];
}

test('AbstractRepository puede ser instanciado', function () {
    $model = new DummyModel();
    $repo = new class($model) extends AbstractRepository {};
    expect($repo)->toBeInstanceOf(AbstractRepository::class);
});
