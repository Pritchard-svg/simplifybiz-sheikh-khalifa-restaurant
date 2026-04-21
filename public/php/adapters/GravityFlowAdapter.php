<?php

namespace SMPLFY\sheikh_khalifa;

class GravityFlowAdapter {

    private RejectionEmail $rejectionEmail;

    public function __construct( RejectionEmail $rejectionEmail ) {
        $this->rejectionEmail = $rejectionEmail;

        $this->register_hooks();
    }

    private function register_hooks(): void {

        add_action(
            'gravityflow_step_complete',
            [ $this, 'handle_step_complete' ],
            10,
            4
        );
    }

    /**
     * Routes reservation rejections to the RejectionEmail usecase.
     * Ignores all other forms and all non-rejected statuses.
     *
     * @param int    $step_id
     * @param int    $entry_id
     * @param int    $form_id
     * @param string $status
     */
    public function handle_step_complete( $step_id, $entry_id, $form_id, $status ): void {

        if ( (int) $form_id !== FormIds::RESERVATION_FORM_ID ) {
            return;
        }

        if ( (string) $status !== 'rejected' ) {
            return;
        }

        $this->rejectionEmail->handle_rejection( (int) $entry_id );
    }
}
