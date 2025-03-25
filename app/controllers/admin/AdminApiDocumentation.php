<?php


namespace Altum\Controllers;

class AdminApiDocumentation extends Controller {

    public function index() {

        /* Prepare the view */
        $view = new \Altum\View('admin/api-documentation/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

}


