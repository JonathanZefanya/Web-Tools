<?php


namespace Altum\Controllers;


class Plan extends Controller {

    public function index() {

        if(!settings()->payment->is_enabled) {
            redirect('not-found');
        }

        $type = isset($this->params[0]) && in_array($this->params[0], ['renew', 'upgrade', 'new']) ? $this->params[0] : 'new';

        /* If the user is not logged in when trying to upgrade or renew, make sure to redirect them */
        if(in_array($type, ['renew', 'upgrade']) && !\Altum\Authentication::check()) {
            redirect('plan/new');
        }

        /* Meta */
        \Altum\Meta::set_canonical_url();

        /* Plans View */
        $data = [];

        $view = new \Altum\View('partials/plans', (array) $this);

        $this->add_view_content('plans', $view->run($data));


        /* Prepare the view */
        $data = [
            'type' => $type
        ];

        $view = new \Altum\View('plan/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
