<?php

namespace Deploylah\Model;

class Deploy extends \ActiveRecord\Model
{
    static $belongs_to = array(
        array('person'),
        array('project')
    );
}
