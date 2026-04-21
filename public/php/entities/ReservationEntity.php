<?php

namespace SMPLFY\sheikh_khalifa;

use SmplfyCore\SMPLFY_BaseEntity;

class ReservationEntity extends SMPLFY_BaseEntity {

    protected function get_property_map(): array {
        return [
            'name'            => '1',   // TODO: verify
            'email'           => '2',   // TODO: verify
            'date'            => '5',
            'time'            => '6',
            'partySize'       => '7',
            'seating'         => '8',   // TODO: verify
            'specialRequests' => '9',   // TODO: verify
            'addons'          => '10',  // TODO: verify
        ];
    }
}
