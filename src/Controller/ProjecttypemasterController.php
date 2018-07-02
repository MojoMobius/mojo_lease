<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;
use Cake\ORM\TableRegistry;
//use Cake\Network\Session;
//use Cake\Datasource\ConnectionManager;

class ProjecttypemasterController extends AppController{
    
    public function index() {
        //$this->session = new Session();
        //pr($this->request->session()->read('user_id'));
        $session = $this->request->session();
        $user_id = $session->read('user_id');
        if ($this->request->is('post')) {
            $projecttypemaster = $this->Projecttypemaster->newEntity($this->request->data());
            $projecttypemaster = $this->Projecttypemaster->patchEntity($projecttypemaster, $this->request->data);
            $projecttypemaster->CreatedDate = date("Y-m-d H:i:s");
            $projecttypemaster->CreatedBy = $user_id;
            $projecttypemaster->RecordStatus = '1';
            $conditions = array(
            'ProjectType' => $this->request->data('ProjectType')
            );
        if ($this->Projecttypemaster->exists($conditions)){
            $this->Flash->error(__('Project Type already exists.'));
        }
        else{
            
//            $connection = ConnectionManager::get('default');
//$connection->insert('Projecttypemaster', [
//    'ProjectType' => $this->request->data('ProjectType'),
//    'CreatedDate' => date("Y-m-d H:i:s")
//]);
//return $this->redirect(['action' => 'index']);
            
            if ($this->Projecttypemaster->save($projecttypemaster)) {
                $this->Flash->success(__('Your Project Type has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
        }
            //$this->Flash->error(__('Unable to add your post.'));
        }
        $ProjectTypeMasters = $this->Projecttypemaster->newEntity();
//
        $Projecttypemaster = TableRegistry::get('Projecttypemaster');
        
        $query = $Projecttypemaster->find();
        $query->where(['RecordStatus'=>1]);
        $this->set(compact('query'));
        $this->set(compact('ProjectTypeMasters'));
        
}
public function edit($id = null)
{
    $Projecttypemaster = $this->Projecttypemaster->get($id);
    if ($this->request->is(['post','put'])) {
        $Projecttypemaster = $this->Projecttypemaster->patchEntity($Projecttypemaster, $this->request->data);
        $Projecttypemaster->ModifiedDate = date("Y-m-d H:i:s");
        $session = $this->request->session();
        $user_id = $session->read('user_id');
        $Projecttypemaster->ModifiedBy= $user_id;
        $conditions = array(
            'ProjectType' => $this->request->data('ProjectType')
            );
        if ($this->Projecttypemaster->exists($conditions)){
            $this->Flash->error(__('Project Type already exists.'));
        } else {
        if ($this->Projecttypemaster->save($Projecttypemaster)) {
            $this->Flash->success(__('Your Project Type has been updated.'));
            return $this->redirect(['action' => 'index']);
        }
        else{
            $this->Flash->error(__('Unable to update your Project Type.'));
        }
        }
        
    }
    $this->set('Projecttypemaster', $Projecttypemaster);
}

}
?>