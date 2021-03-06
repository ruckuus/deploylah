<?php
namespace Deploylah\Model;

class User extends \ActiveRecord\Model
{
    static $connection = 'development';
    
    static $attr_accessible = array('username', 'password', 'roles');

    static $alias_attribute = array(
        'alias_username' => 'username', 
        'alias_password' => 'password',
        'alias_roles' => 'roles',
        'alias_id' => 'id'
    );

    static $has_many = array(
        array('project'),
        array('deploys')
    );

    static $validates_presence_of = array(
        array('name'), array('roles')
    );

    function get_username() {
        return $this->username;
    }
    
    function get_password() {
        return $this->password;
    }
}
