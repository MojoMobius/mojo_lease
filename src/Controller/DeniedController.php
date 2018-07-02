<?php
/**

 */

namespace App\Controller;

use App\Controller\AppController;

class DeniedController extends AppController {

     //public $uses = array('Users','Role','menu_master','role_module_map');

      public function index()
        {
          $this->set('Module','Denied');
                    $this->set('Action','Denied');
            //$this->set('title_for_layout', 'Role map');
        }
}
