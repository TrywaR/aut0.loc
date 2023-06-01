<?php
class user extends model
{
    public $table_name = 'users';

    public $id = '';
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = '';

    function check_email ( $sEmail = '' ){
        $oUser = new user();
        $oUser->query .= ' AND `email` = "' . $sEmail . '"';
        if ( count($oUser->get()) ) return true;
        else return false;
    }
    
    function __construct(){
    }
}
