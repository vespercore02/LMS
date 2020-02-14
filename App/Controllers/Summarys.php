<?php 

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\Group;
use \App\Models\Borrow;
use \App\Models\Loan;
use \App\Models\Announcement;
use \App\Models\Contribution;
use \App\Models\Summary;
use \App\Models\Term;
use \App\Auth;
use \App\Flash;

class Summarys extends Authenticated
{

    public function indexAction()
    {

        $terms = new Term();

        $terms_list = $terms->view();
        
        $today = date('Y-m-d');
        
        $nearest_cutoff = self::getCuffOff($today);

        View::renderTemplate('/summary/index.html',[
            'terms' => $terms_list
        ]);
    }

    public function termAction()
    {
        $term                      = $this->route_params['id'];
        $term_month_summary        = Summary::viewTerm($term);

        View::renderTemplate('/summary/term.html',[
            'term' => $term,
            'term_month_summary' => $term_month_summary
        ]);

    }
    public function termSummary()
    {
        if (isset($_GET['term'])) {
            # code...

            $term_summary          = Summary::viewTerm($_GET['term']);

            echo json_encode($term_summary);
        }
    }

    public function getCuffOff($date)
    {
        
        $month = explode("-", $date);

        $set_month = $month[1]."-".$month[2];

        //print_r($month);
        
        if ($set_month > '10-15' and $set_month < '10-31' or $set_month == '10-15') {
            # code...
            return $month[0]."-10-15";
        }

        if ($set_month > '10-31' and $set_month < '11-15' or $set_month == '10-31') {
            # code...
            return $month[0]."-10-31";
        }

        if ($set_month > '11-15' and $set_month < '11-30' or $set_month == '11-15') {
            # code...
            return $month[0]."-11-15";
        }

        if ($set_month > '11-30' and $set_month < '12-15' or $set_month == '11-30') {
            # code...
            return $month[0]."-11-30";
        }

        if ($set_month > '12-15' and $set_month < '12-31' or $set_month == '12-15') {
            # code...
            return $month[0]."-12-15";
        }

        if ($set_month > '12-31' and $set_month < '01-15' or $set_month == '12-31') {
            # code...
            return $month[0]."-12-31";
        }

        if ($set_month > '01-15' and $set_month < '01-31' or $set_month == '01-15') {
            # code...
            return $month[0]."-01-15";
        }

        if ($set_month > '01-31' and $set_month < '02-15' or $set_month == '01-31') {
            # code...
            return $month[0]."-01-31";
        }

        if ($set_month > '02-15' and $set_month < '02-28' or $set_month == '02-15') {
            # code...
            return $month[0]."-02-15";
        }

        if ($set_month > '02-28' and $set_month < '03-15' or $set_month == '02-28') {
            # code...
            return $month[0]."-02-28";
        }

        if ($set_month > '03-15' and $set_month < '03-31' or $set_month == '03-15') {
            # code...
            return $month[0]."-03-15";
        }

        if ($set_month > '03-31' and $set_month < '04-15' or $set_month == '03-31') {
            # code...
            return $month[0]."-03-31";
        }

        if ($set_month > '04-15' and $set_month < '04-30' or $set_month == '04-15') {
            # code...
            return $month[0]."-04-15";
        }

        if ($set_month > '04-30' and $set_month < '05-15' or $set_month == '04-30') {
            # code...
            return $month[0]."-04-30";
        }

        if ($set_month > '05-15' and $set_month < '05-31' or $set_month == '05-15') {
            # code...
            return $month[0]."-05-15";
        }

        if ($set_month > '05-31' and $set_month < '06-15' or $set_month == '05-31') {
            # code...
            return $month[0]."-05-31";
        }

        if ($set_month > '06-15' and $set_month < '06-30' or $set_month == '06-15') {
            # code...
            return $month[0]."-06-15";
        }

        if ($set_month > '06-30' and $set_month < '07-15' or $set_month == '06-30') {
            # code...
            return $month[0]."-06-30";
        }

        if ($set_month > '07-15' and $set_month < '07-31' or $set_month == '07-15') {
            # code...
            return $month[0]."-07-15";
        }

        if ($set_month > '07-31' and $set_month < '08-15' or $set_month == '07-31') {
            # code...
            return $month[0]."-07-31";
        }

        if ($set_month > '08-15' and $set_month < '08-31' or $set_month == '08-15') {
            # code...
            return $month[0]."-08-15";
        }

        if ($set_month > '08-31' and $set_month < '09-15' or $set_month == '08-31') {
            # code...
            return $month[0]."-08-31";
        }

        if ($set_month > '09-15' and $set_month < '09-30' or $set_month == '09-15') {
            # code...
            return $month[0]."-09-15";
        }

        if ($set_month > '09-30' and $set_month < '10-15' or $set_month == '09-30') {
            # code...
            return $month[0]."-09-30";
        }
        
    }
    
}



?>