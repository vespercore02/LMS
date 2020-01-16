<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Contribution;
use \App\Models\Summary;
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
        View::renderTemplate('/contribution/index.html', [
            'terms' => $terms_list
        ]);
    }

    public function termAction()
    {
        $term = $this->route_params['id'];
        $contribution   = Contribution::viewTerm($this->route_params['id']);
        $term_months    = Term::months($this->route_params['id']);
        
        $contri_months = array();

        foreach ($term_months as $month) {
            # code...
            //echo $month['month_start']."<br>";

            $contri_months[]  = Contribution::getMonthContriRecords($month['month_start']);
        }

        /*
        echo "<pre>";
        print_r($contri_months);
        echo "</pre>";
        */
        View::renderTemplate('/contribution/term.html', [
            'term' => $term,
            'term_months' => $term_months,
            'contri_months' => $contri_months
        ]);
    }

    public function monthAction()
    {
        $month = $this->route_params['id'];
        $month_contri   = Contribution::getMonthContriRecords($this->route_params['id']);
        /*
        echo "<pre>";
        print_r($month_contri);
        echo "</pre>";
*/
        View::renderTemplate('/contribution/month.html', [
            'month' => $month,
            'month_contri' => $month_contri
        ]);
    }

    /*
    public function month()
    {
        if (isset($_GET['date'])) {
            # code...

            $month          = Contribution::getMonthContriRecords($_GET['date']);

            echo json_encode($month);
        }
    }
    */

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
    /*
    public function addAction()
    {
        $contribution = new Contribution($_POST);

        echo "<pre>";
        print_r($contribution->checkContri());
        echo "</pre>";

        if (count($contribution->checkContri()) == 0) {
            # code...
            $contribution->add();

            Flash::addMessage('Contribution is successful added.');

            $this->redirect('/members/view/'.$_POST['user_id']);
        } else {
            # code...
            Flash::addMessage('Contribution for this date '.$_POST['month'].' and this person '.$_POST['name'].' is already set', "warning");

            $this->redirect('/members/view/'.$_POST['user_id']);
        }
    }

    */
    public function addAction()
    {

        # BACK TO BASIC CRUD --- ADD CONTRIBUTION FIRST
        ### NOTE 12-12-19
        # CREATE FUNCTION FOR ADD / UPDATE TOTAL CONTRIBUTION WITH OUT INTEREST
        # PROBLEM if first date is added or update contribution of all is not updateing

        $contribution = new Contribution($_POST);

        $selected_month = $_POST['month'];
        $given_contri   = $_POST['contri'];

        $month = $_POST['month'];

        $months = Term::months($_POST['term']);

        $months_count = count($months);

        $contri_info = $contribution->checkContri();

        if ($contri_info) {
            # code...
            
            $_POST['total_contri_wout_int'] = $contri_info[0]['total_contri_wout_int'] + $_POST['contri'];

            if ($contri_info[0]['contri'] != 0) {
                # code...
                Flash::addMessage('Contribution for this date '.$_POST['month'].' and this person '.$_POST['name'].' is already set', "warning");

                $this->redirect('/members/view/'.$_POST['user_id']);

                end;
            }

            for ($i=0; $i < $months_count; $i++) {
                # code...

                if ($months[$i]['month_start'] == $month) {
                    # code...

                    $_POST['month'] = $months[$i]['month_start'];

                    $update = new Contribution($_POST);

                    $update->update();

                } elseif ($months[$i]['month_start'] >= $month) {
                    # code...
                    $_POST['month'] = $months[$i]['month_start'];

                    $update = new Contribution($_POST);

                    $update->update_total();
                }
            }

            /*
            Flash::addMessage('Contribution is successful added and  updated');

            $this->redirect('/members/view/'.$_POST['user_id']);
            */
            
        } else {
            # code...

            $_POST['total_contri_wout_int'] = $_POST['contri'];


            for ($i=0; $i < $months_count; $i++) {
                # code...

                if ($months[$i]['month_start'] == $month) {
                    # code...

                    $_POST['month'] = $months[$i]['month_start'];
                } elseif ($months[$i]['month_start'] >= $month) {
                    # code...

                    $_POST['month'] = $months[$i]['month_start'];

                    $_POST['contri'] = 0;
                }

                $add = new Contribution($_POST);

                $add->add();
            }

            //Flash::addMessage('Contribution is successful added.');

            //$this->redirect('/members/view/'.$_POST['user_id']);
        }

        //echo "<pre>";
        //print_r($_POST);
        //echo "</pre>";

        $summary_info = Summary::getMonthSummaryRecords($selected_month);

        #Summary Check Add or Update
        #If summary info = 1 update if 0 add
        print_r($summary_info);

        
        if (!empty($summary_info)) {
            # code...
            $_POST['month']     = $selected_month;
            $_POST['contri'] = $summary_info[0]['contri_wout_int'] + $given_contri;

            $summary = new Summary($_POST);

            $summary->updateContri();

            //echo "update";

        }else {
            # code...
            $_POST['month']     = $selected_month;
            $_POST['contri']    = $given_contri;

            $summary = new Summary($_POST);

            $summary->addContri();

            //print_r($summary);

            //echo "Add";
        }
        

    }
    
}
