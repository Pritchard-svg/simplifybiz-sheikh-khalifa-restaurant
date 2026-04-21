<?php

namespace SMPLFY\sheikh_khalifa;

/**
 * Restaurant table inventory by party size and seating type.
 *
 * Adjust these numbers as the restaurant's capacity changes.
 */
class TableInventory {

    public static function get_all(): array {
        return [
            2  => [ 'indoor' => 7, 'outdoor' => 3, 'total' => 10 ],
            4  => [ 'indoor' => 5, 'outdoor' => 3, 'total' => 8  ],
            6  => [ 'indoor' => 4, 'outdoor' => 2, 'total' => 6  ],
            8  => [ 'indoor' => 3, 'outdoor' => 1, 'total' => 4  ],
            10 => [ 'indoor' => 1, 'outdoor' => 1, 'total' => 2  ],
        ];
    }

    /**
     * Maps a party size label from the form (e.g. "4 guests") to its integer size.
     */
    public static function map_party_size_label( string $label ): int {
        $map = [
            '2 guests'  => 2,
            '4 guests'  => 4,
            '6 guests'  => 6,
            '8 guests'  => 8,
            '10 guests' => 10,
        ];

        return $map[ $label ] ?? 0;
    }
}
