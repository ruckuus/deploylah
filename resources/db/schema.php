<?php

$schema = new \Doctrine\DBAL\Schema\Schema();

$project = $schema->createTable('project');
$project->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
$project->addColumn('name', 'string', array('length' => 32));
$project->addColumn('user_id', 'string', array('length' => 32));
$project->addColumn('created_at', 'string', array('length' => 32));
$project->addColumn('updated_at', 'string', array('length' => 32));
$project->setPrimaryKey(array('id'));

$user = $schema->createTable('user');
$user->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
$user->addColumn('username', 'string', array('length' => 32));
$user->addColumn('password', 'string', array('length' => 32));
$user->addColumn('salt', 'string', array('length' => 64));
$user->addColumn('created_at', 'string', array('length' => 32));
$user->addColumn('updated_at', 'string', array('length' => 32));
$user->addColumn('last_login', 'string', array('length' => 32));
$user->addColumn('user_right', 'string', array('length' => 32));
$user->setPrimaryKey(array('id'));

$repo = $schema->createTable('repo');
$repo->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
$repo->addColumn('name', 'string', array('length' => 32));
$repo->addColumn('repo_type', 'string', array('length' => 32));
$repo->addColumn('repo_url', 'string', array('length' => 32));
$repo->addColumn('repo_path', 'string', array('length' => 32));
$repo->setPrimaryKey(array('id'));

$servers = $schema->createTable('servers');
$servers->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
$servers->addColumn('name', 'string', array('length' => 32));
$servers->addColumn('server_group', 'string', array('length' => 32));
$servers->addColumn('servers_url', 'string', array('length' => 32));
$servers->addColumn('servers_path', 'string', array('length' => 32));
$servers->setPrimaryKey(array('id'));

return $schema;
