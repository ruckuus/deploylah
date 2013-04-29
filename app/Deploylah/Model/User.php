<?php
namespace Deploylah\Model;

class User extends \ActiveRecord\Model
{
    static $has_many = array(
        array('project'),
        array('deploys')
    );

    static $validates_presence_of = array(
        array('name'), array('roles')
    );
}
