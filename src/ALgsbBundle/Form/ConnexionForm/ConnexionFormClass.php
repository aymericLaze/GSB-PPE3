<?php

namespace ALgsbBundle\Form\ConnexionForm;

/**
 * Description of ConnexionFormClass
 *
 * @author laze
 */
class ConnexionFormClass {
    
    // ATTRIBUTS
    private $login;
    private $passwd;
    private $role;
    
    // METHODES
    
    // GETTERS
    public function getLogin()
    {
        return $this->login;
    }

    public function getPasswd()
    {
        return $this->passwd;
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    // SETTERS
    public function setLogin($login)
    {
        $this->login = $login;
    }
    
    public function setPasswd($passwd)
    {
        $this->passwd = $passwd;
    }
    
    public function setRole($role)
    {
        $this->role = $role;
    }
}
