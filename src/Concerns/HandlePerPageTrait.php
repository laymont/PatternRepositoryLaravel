<?php

namespace Laymont\PatternRepository\Concerns;

use Illuminate\Http\Request;

trait HandlePerPageTrait
{
    /**
     * Get the per page value from the request.
     *
     * @param Request $request
     * @param int $default
     * @return int
     */
    protected function getPerPage(Request $request, int $default = 15): int
    {
        return $request->filled('per_page') ? (int) $request->get('per_page') : $default;
    }
}
