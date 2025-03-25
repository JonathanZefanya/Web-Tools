<?php


namespace Altum\Controllers;

use Altum\Title;

class ApiDocumentation extends Controller {

    public function index() {

        if(!settings()->main->api_is_enabled) {
            redirect('not-found');
        }

        $endpoint = isset($this->params[0]) ? query_clean(str_replace('-', '_', $this->params[0])) : null;

        if($endpoint) {
            if(!file_exists(THEME_PATH . 'views/api-documentation/' . $endpoint . '.php')) {
                redirect('not-found');
            }

            Title::set(sprintf(l('api_documentation.title_dynamic'), l('api_documentation.' . $endpoint)));

            /* Prepare the view */
            $view = new \Altum\View('api-documentation/' . $endpoint, (array) $this);
        } else {
            /* Prepare the view */
            $view = new \Altum\View('api-documentation/index', (array) $this);
        }

        /* Meta */
        \Altum\Meta::set_canonical_url();

        $this->add_view_content('content', $view->run());

    }
}


