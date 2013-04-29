<?php
class Project extends ActiveRecord\Model
{
    static $belongs_to = array(
        array('user')
    );

    static $has_many = array(
        array('deploy'),
        array('user')
    );

}
