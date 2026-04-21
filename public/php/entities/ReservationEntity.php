<?php

namespace SMPLFY\sheikh_khalifa;

use SmplfyCore\SMPLFY_BaseEntity;

class ReservationEntity extends SMPLFY_BaseEntity {

    protected function get_property_map(): array {
        return [
            // Personal Details
            'nameFirst'       => '2.3',
            'nameLast'        => '2.6',
            'email'           => '3',
            'phone'           => '4',

            // Reservation Details
            'date'            => '6',
            'time'            => '7',
            'partySize'       => '9',
            'seating'         => '8',

            // Extras
            'specialRequests' => '12',
            'addons'          => '17',
            'agreement'       => '18.1',

            // Manager Only (populated during workflow — empty at submission)
            'tableAssigned'   => '20',
            'waiterAssigned'  => '21',
            'rejectionReason' => '22',
        ];
    }
}
