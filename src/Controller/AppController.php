<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadModel('GetJob');
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    function checkSession($ctrl_act) {
        $localIP = getHostByName(getHostName());
        $session = $this->request->session();
        $session->check('user_id');
        if(empty($session->check('user_id'))) {
            return 1;
        }
        else {
            return 0;
        }
    }
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
        
    
    }
    public function beforeFilter(Event $event)
    {
        $session = $this->request->session();
        $user_id = $session->read("user_id");
        $role_id = $session->read("RoleId");
        $ProjectId = $session->read("ProjectId");
        $ctrl = $this->request->controller;
        $action = $this->request->action;
        
        $controller = $event->subject();
        $request = $controller->request;
        
        $request->params['isAjax'] = $request->is('ajax');
        $ajaxRequest='';
        if ($request->params['isAjax']) {
           $ajaxRequest = 'ajax';
        }
        
        
        if($action == 'index')
            $ctrl_act =  $ctrl;
        else
            $ctrl_act =  $ctrl."/".$action;
        //echo $ctrl_act;
        if($ctrl_act != 'Users' && $ctrl_act != 'Users/logout' && empty($ajaxRequest) && $ctrl_act != 'DeliveryExport/exportdata') {
           
            $temp=$this->checkSession($ctrl_act);
            if($temp==1){
                return $this->redirect('/Users/index');
            }
            else
            {
                //$controller='';
                //echo $ProjectId;
                $JsonArray=$this->GetJob->find('getjob',['ProjectId'=>$ProjectId]);
                //echo $ctrl_act;
                if($ctrl_act == 'ProjectLanding'){
                   $Menu = ''; 
                }else{
                $Menu = $JsonArray['Menu'][$role_id];
                }
                //echo $role_id;
                //pr($Menu);
               // echo $ctrl_act;
                $avail_menus = $this->SearchValue($Menu,  strtolower($ctrl_act));
                //pr($avail_menus); exit;
                $this->set('menus',$Menu);
                if(!empty($avail_menus)){
                $explode = explode(",",$avail_menus);  
                $controller = $explode[0];
                $module = $explode[1]; 
                $this->set('Module',$module);
                $this->set('Action',$controller);
                $moduleId=array_search($module, $JsonArray['Module']);
                $session = $this->request->session();
                $session->write("moduleId", $moduleId);
                }
               //echo $ctrl_act; exit;
            if($ctrl_act != 'Denied' && $ctrl_act!='ModuleConfigs/edit' && $ctrl_act!='Importinitiates/delete' && $ctrl_act!='ProjectLanding' && $ctrl_act!='Getjobcoreview' && $ctrl_act!='Getjobhooview' && $ctrl_act!='Getjobnoncoreview') {
                
                if (empty($controller)) {
                   
                    $this->redirect(array('controller' => 'Denied', 'action' => 'index'));
                    //exit;
                }
            }
            }
        }
    }
    public function SearchValue($a, $value) {
            foreach($a as $key1 => $keyid) {
                foreach($keyid as $key => $keyid2) {
                    if ( strtolower($keyid2) === $value )
                      // return $key;
                       return $key.','.$key1;
                }
            }
        return false;
    }
}
