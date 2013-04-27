<?php

namespace Deploylah\Project;

use Deploylah\Model\Project; 
use Doctrine\DBAL\Connection;
 
class ProjectProvider implements AbstractClass 
{
    private $conn;
 
    public function __construct($project, $name)
    {
        $this->conn = new Connection();
    }
 
    public function getProjectByUsername($projectname)
    {
        $stmt = $this->conn->executeQuery('SELECT * FROM user, project WHERE project.user_id == user.id AND user.username = ?', array(strtolower($projectname)));
        if (!$project = $stmt->fetch()) {

            throw new Exception(sprintf('No project associated with "%s".', $projectname));
        }
 
        return new Project($project['id'], $project['name'], $project['user_id'], $project['created_at'], $project['updated_at']);
    }
}

