<?php

namespace SMPLFY\sheikh_khalifa;

use SmplfyCore\SMPLFY_BaseEntity;

class ReservationEntity extends SMPLFY_BaseEntity {

    protected function get_property_map(): array {
        return [
            // Personal Details
            'nameFirst'       => '1.3',
            'nameLast'        => '1.6',
            'email'           => '2',
            'phone'           => '3',

            // Reservation Details
            'date'            => '5',
            'time'            => '6',
            'partySize'       => '7',
            'seating'         => '9',

            // Extras
            'specialRequests' => '10',
            'addons'          => '12',
            'agreement'       => '23.1',

            // Manager Only (populated during workflow — empty at submission)
            'tableAssigned'   => '20',
            'waiterAssigned'  => '21',
            'rejectionReason' => '22',
        ];
    }
}
