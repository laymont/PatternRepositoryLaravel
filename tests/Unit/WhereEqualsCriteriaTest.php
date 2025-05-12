<?php

declare(strict_types=1);

use Laymont\PatternRepository\Criteria\WhereEqualsCriteria;

test('WhereEqualsCriteria instancia correctamente', function () {
    $criteria = new WhereEqualsCriteria('status', 'active');
    expect($criteria)->toBeInstanceOf(WhereEqualsCriteria::class);
});
