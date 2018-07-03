<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;
use Cake\ORM\TableRegistry;

class ProjectmasterController extends AppController{
    
    public function index() {
        if ($this->request->is('post')) {
            $projectmaster = $this->Projectmaster->newEntity($this->request->data());
            $projectmaster = $this->Projectmaster->patchEntity($projectmaster, $this->request->data);
            $projectmaster->CreatedDate = date("Y-m-d H:i:s");
            $projectmaster->RecordStatus = '1';
            $conditions = array(
            'ProjectId' => $this->request->data('ProjectId'),
            'ProjectName' => $this->request->data('ProjectName'),
            'ProjectTypeId' => $this->request->data('ProjectTypeId')
            );
        if ($this->Projectmaster->exists($conditions)){
            $this->Flash->error(__('Project Config already exists.'));
        }
        else{
            if ($this->Projectmaster->save($projectmaster)) {
                $this->Flash->success(__('Your Project Config has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
        }
            //$this->Flash->error(__('Unable to add your post.'));
        }
        $ProjectMasters = $this->Projectmaster->newEntity();
//        $ProjectMaster = TableRegistry::get('Projectmaster');
//
//        $query = $ProjectMaster->find();
//        $this->set(compact('query'));
        $this->set(compact('ProjectMasters'));
        
}

}
?>

