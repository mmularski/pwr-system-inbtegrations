<?php

namespace App\Product\Model\Service;

use App\Product\Api\TestInterface;

/**
 * Class TestService
 */
class TestService implements TestInterface
{
    /**
     * @api
     *
     * @return string[]
     */
    public function get()
    {
        return json_encode(
            [
                'a' => 123,
                'sada' => 'saaaaaa',
            ]
        );
    }
}
