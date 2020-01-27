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

        $contri_info = $contribution->checkContriDate();

        if ($contri_info == 1) {
            # code...
            
            echo "<pre>";
            print_r($contri_info);
            echo "</pre>";

            $contributed_dates = $contribution->getContributedDate();

            $date_position = self::Search($_POST['month'], $contributed_dates);

            $date_count = count($contributed_dates);

            echo "<pre>";
            print_r($contributed_dates);
            echo "</pre>";

            echo $date_position."<br>";

            for ($i=$date_position; $i < $date_count; $i++) {
                # code...

                if ($i >= $date_position) {
                    # code...
                    $_POST['month'] = $contributed_dates[$i];
                    $contribution = new Contribution($_POST);

                    $contri_info = $contribution->getContriDate();

                    echo "<pre>";
                    print_r($contri_info);
                    echo "</pre>";

                    if ($i == $date_position) {
                        # code...
                        $_POST['total_contri_wout_int'] = $contri_info['total_contri_wout_int'] + $given_contri;

                        $_POST['contri'] = $contri_info['contri'] + $_POST['contri'];

                        $_POST['total_contri_w_int'] = $_POST['total_contri_wout_int'] + $contri_info['total_int'];

                        $update = new Contribution($_POST);

                        $update->update();
                    } else {
                        # code...
                        $_POST['total_contri_wout_int'] = $contri_info['total_contri_wout_int'] + $given_contri;
                        
                        $_POST['total_contri_w_int'] = $_POST['total_contri_wout_int'] + $contri_info['total_int'];
                        
                        $update = new Contribution($_POST);

                        $update->update_total();
                    }
                }
            }


        
            Flash::addMessage('Contribution is successful added and  updated');

            //$this->redirect('/members/view/'.$_POST['user_id']);
           
        } else {
            # code...

            $last_contri_info = $contribution->getLastContri();

            if ($last_contri_info) {
                # code...
                echo "<pre>";
                print_r($last_contri_info);
                echo "</pre>";
                $_POST['total_contri_wout_int'] = $_POST['contri'] + $last_contri_info['total_contri_wout_int'];

                $_POST['total_int'] = $last_contri_info['total_int'];

                if (empty($last_contri_info['total_int'])) {
                    # code...
                    $_POST['total_contri_w_int'] = 0;
                }else {
                    # code...
                    $_POST['total_contri_w_int'] = $last_contri_info['total_int'] + $_POST['total_contri_wout_int'];
                }
                
            } else {
                # code...
                $_POST['total_contri_wout_int'] = $_POST['contri'];

                $_POST['total_int'] = 0;
                
                $_POST['total_contri_w_int'] = 0;
            }

            $add = new Contribution($_POST);

            $add->add();
            
            Flash::addMessage('Contribution is successful added.');

            //$this->redirect('/members/view/'.$_POST['user_id']);
        }

        echo "<pre>";
        print_r(Contribution::getMonthContriRecords($selected_month));
        echo "</pre>";

        $summary_info = Summary::getMonthSummaryRecords($selected_month);

        if (!empty($summary_info)) {
            # code...
            $_POST['month']     = $selected_month;
            $_POST['contri']    = $summary_info['contri_wout_int'] + $given_contri;

            $summary = new Summary($_POST);

            $summary->updateContri();

            //echo "update";
        } else {
            # code...
            $_POST['month']     = $selected_month;
            $_POST['contri']    = $given_contri;

            $summary = new Summary($_POST);

            $summary->addContri();

            //print_r($summary);

            //echo "Add";
        }

        //self::Update_month_int($selected_month);

        $this->redirect('/members/view/'.$_POST['user_id']);
    }

    /**
     * Functions
     */

    public static function Search($value, $array)
    {
        return(array_search($value, $array));
    }

    public static function Update_month_int($date)
    {

        $summary_info = Summary::getMonthSummaryRecords($date);

        $summary_info['contri_wout_int'];

        $contributor_info = Contribution::getMonthContriRecords($date);
        
        $contributor_count = count($contributor_info);
        
        for ($i=0; $i < $contributor_count; $i++) { 
            # code...
            $_POST['month_int'] = ($contributor_info[$i]['contri'] / $summary_info['contri_wout_int']) * 100;
            $_POST['contribution_id'] = $contributor_info[$i]['contribution_id'];
            $contribution = new Contribution($_POST);

            $contribution->update_month_int();
        }

    }
}
