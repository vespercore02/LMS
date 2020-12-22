<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Term;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

class Terms extends Authenticated
{
    public function indexAction()
    {
        $terms = new Term();

        $terms_list = $terms->view();

        View::renderTemplate('/term/index.html',[
            'terms' => $terms_list
        ]);
    }


    # Need check term
    
    public function saveAction()
    {
        if (isset($_POST)) {
            # code...
            $month=1;
            $months = 12;

            $term = $_POST['month_year'];
            $month_start = $_POST['month_year']."-".$_POST['month_start'];

            while ($month <= 12) {
                # code...

                if (isset($month_end)) {
                    # code...

                    $month_start = date("Y-m-d", strtotime($month_start." +1month"));
                
                } 

                Term::save($term, $month_start);

                $month_end =  date("Y-m-t", strtotime($month_start));
                
                Term::save($term, $month_end);

                $month++;
            }

            Flash::addMessage('Term successfully added ');

            $this->redirect('/ControlPanel/Terms');
        }
    }

    public function viewAction()
    {



    }

    public static function generateDate($date_start)
    {


        



    }

    public function months()
    {
        if (isset($_GET['term'])) {
            # code...

            $months_term          = Term::months($_GET['term']);

            echo json_encode($months_term);
        }
    }

}
