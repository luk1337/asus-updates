<?php

namespace App;

class Helper
{
    /**
     * Swaps data between two models.
     *
     * @param from
     * @param to
     * @param array $fields
     */
    public static function swapData($from, $to, array $fields)
    {
        $cache = [
            "from" => clone $from,
            "to" => clone $to
        ];

        foreach ($fields as $field) {
            $from[$field] = $cache['to'][$field];
            $to[$field] = $cache['from'][$field];
        }

        $to->save();
        $from->save();
    }
}
