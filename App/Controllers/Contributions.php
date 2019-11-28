<?php 

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Contribution;
use \App\Models\Term;

class Contributions extends Authenticated
{

    public function indexAction()
    {
        $terms = new Term();

        $terms_list = $terms->view();
        /*
        echo "<pre>";
        print_r($terms_list);
        echo "</pre>";
        */
        View::renderTemplate('/contribution/index.html',[
            'terms' => $terms_list
        ]);

    }

    public function viewAction()
    {
        
        echo $this->route_params['id'];
        View::renderTemplate('/contribution/view.html');
    }

    public function month()
    {
        if (isset($_GET['date'])) {
            # code...

            $month          = Contribution::getMonthContriRecords($_GET['date']);

            echo json_encode($month);
        }
    }

    public function termMonths()
    {
        if (isset($_GET['term'])) {
            # code...

            $term_months          = Term::months($_GET['term']);

            echo json_encode($term_months);
        }
    }
    public function termContribution()
    {
        if (isset($_GET['term'])) {
            # code...

            $term_contri          = Contribution::viewTerm($_GET['term']);

            echo json_encode($term_contri);
        }
    }
    
}



?>