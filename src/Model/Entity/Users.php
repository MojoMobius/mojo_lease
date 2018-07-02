<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Model\Entity;

use Cake\Collection\Collection;
use Cake\ORM\Entity;

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class User extends Entity {
    public $useDbConfig = 'd2k';
    public $useTable = 'Employee';
    
    function Get_PasswordSalt($user_name) {
    
        $password_salt = $this->find('first', array('conditions' => array('Users.Username' => $user_name, 'Users.Active' => 1),array('nolock')));
        $PasswordSalt = $password_salt['Users']['PasswordSalt'];
        return $PasswordSalt;
    }
    
    function Check_user_login($user_name,$password) {
        
        $login_check = $this->find('first', array('conditions' => array('Users.Username' => $user_name, 'Users.Password' => $password, 'Users.Active' => 1),'order' => array('Id' => 'desc')));
        return $login_check;
    }
}
