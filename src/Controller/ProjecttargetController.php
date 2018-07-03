<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\DateTime;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Utility\Hash;

class ProjecttargetController extends AppController {

    /**
     * to initialize the model/utilities gonna to be used this page
     */
    public $paginate = [
        'limit' => 10,
        'order' => [
            'Id' => 'asc'
        ]
    ];

    public function initialize() {
        parent::initialize();
        $this->loadModel('QCBatchMaster');

        $this->loadModel('Projecttarget');

        $this->loadModel('GetJob');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Paginator');
    }
    public function index() {   
        ini_set('memory_limit', '-1');
        set_time_limit(0);
     
		
		//$Filter="WHERE  ProjectId IN ('$ids') AND CreatedDate >= '".date("Y-m-d")."'";
/////index value form///
       if(!empty($this->request->data('month_from'))){
            $fdate = $this->request->data('month_from');
         } 
        else { 
            $fdate= "";//date("d-m-Y");
        }
        
        if(!empty($this->request->data('month_to'))){
            $tdate = $this->request->data('month_to');
         } 
        else { 
            $tdate= "";
        }
        $this->set('fromdate', $fdate);
        $this->set('todate', $tdate);    
                
////index value form end/////               
            
if (isset($this->request->data['check_submit'])) {
	    $session = $this->request->session();
        $ProjectId = $session->read("ProjectId");
	 $connection = ConnectionManager::get('default');
			 
//seperate count///
$Arrfdate=explode("-",$fdate);
$Arrtdate=explode("-",$tdate);		 
$fdateformat = $Arrfdate[1]."-".$Arrfdate[0]."-01";
$tdateformat = $Arrtdate[1]."-".$Arrtdate[0]."-01";
$ts1 = strtotime($fdateformat);
$ts2 = strtotime($tdateformat);
$year1 = date('Y', $ts1);
$year2 = date('Y', $ts2);
$month1 = date('m', $ts1);
$month2 = date('m', $ts2);
$countmonth = (($year2 - $year1) * 12) + ($month2 - $month1);

if($month1 == $month2 && $year1 == $year2){
    $countmonth = 1;
}
//seperate count end	

$Setmonth=$Arrfdate[0];
$Setyear=$Arrfdate[1];
$Arrcompleted=array();
$Arrtarget=array();
$Arrmonthtitle=array();
 $f_chart='dataPoints: [';
 $f2_chart='dataPoints: [';

				 $target_report = $connection->execute("SELECT MonthlyTarget as mon FROM ProjectMaster  WHERE ProjectId='".$ProjectId."'")->fetchAll('assoc');
				  
			 for($i=0;$i<=$countmonth;$i++){
                             $j=$i+1;
				 if($Setmonth== 13){
					 $Setmonth=1;
					 $Setyear=$Setyear + 1;
				 }
				 /////Query start/////
				 //targete
				  $strdate =$Setyear."-".$Setmonth."-01";
				 $Arrmonthtitle[]=date('F Y', strtotime($strdate));
				 $Arrtarget[]=$target_report[0]['mon'];
                                 
				 //end
				 
				  $Mnth="_".(int)$Setmonth."_".(int)$Setyear;
				  $cnt_report = $connection->execute("SELECT count(1) as cnt FROM ProductionEntityMaster".$Mnth."  WHERE StatusId!='' GROUP BY  InputEntityId")->fetchAll('assoc');
				  $Arrcompleted[]=count($cnt_report);
				  $f_chart.='{label: "'.date('F Y', strtotime($strdate)).'" , x: '.$j.', y: '.$target_report[0]['mon'].' },';
                                  $f2_chart.='{label: "'.date('F Y', strtotime($strdate)).'" , x: '.$j.', y: '.count($cnt_report).' },';
				 /////Query end/////
				 
				 $Setmonth= $Setmonth + 1;
			 }
			 
			 
        $this->set('target', $Arrtarget);
        $this->set('completed', $Arrcompleted); 
        $this->set('monthtitle', $Arrmonthtitle);
        $this->set('totmonth', $countmonth);
        $f_chart.=']';
        //echo $f_chart;exit;
        $f2_chart.=']';
			 
       
          /* $f_charts='dataPoints: [
         
        {label: "Jan" , x: 1, y: 171 },
        {label: "Feb" , x: 2, y: 155 },
        {label: "Mar" , x: 3, y: 150 },
        {label: "Apr" , x: 4, y: 165 },
        {label: "Italy" , x: 5, y: 195 },
        {label: "Italy" , x: 6, y: 168 },
        {label: "Italy" , x: 7, y: 128 },
        {label: "Italy" , x: 8, y: 134 },
        {label: "Italy" , x: 9, y: 114 }
        ]';
        $f2_charts='dataPoints: [
        {label: "Jan" , x: 1, y: 171 },
        {label: "Feb" , x: 2, y: 155 },
        {label: "Mar" , x: 3, y: 150 },
        {label: "Apr" , x: 4, y: 165 },
        {label: "Italy" , x: 5, y: 195 },
        {label: "Italy" , x: 6, y: 168 },
        {label: "Italy" , x: 7, y: 128 },
        {label: "Italy" , x: 8, y: 134 },
        {label: "Italy" , x: 9, y: 114 }
        ]';*/
        
        $this->set('fchart', $f_chart);
        $this->set('f2chart', $f2_chart);
        
        
			 //pr($Arrmonthtitle);
			 //pr($Arrtarget);
			 //exit;
			 
          


          
	    
	    
                   // $this->set('QAreview', $QA_data);
	  } //check_submit close
	
	
	
	
    }

	 function ajaxstatus() {
        echo $status = $this->QAreview->find('status', ['ProjectId' => $_POST['projectId']]);
        exit;
    }
       

}
