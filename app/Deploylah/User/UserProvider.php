<?php

namespace Deploylah\User;
 
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Deploylah\Model\User as DeployUser;
 
class UserProvider implements UserProviderInterface
{

    public function loadUserByUsername($username)
    {
        $user = DeployUser::find_by_username(strtolower($username)); 

        if ($user->count() < 1) {

            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        if ($user->dirty_attributes()) {
            throw new UnsupportedUserException(sprintf('Bad credentials for "%s"'), $username);
        }
 
        return new User($user->alias_username, $user->alias_password, explode(',', $user->alias_roles), true, true, true, true);
    }
 
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
 
        return $this->loadUserByUsername($user->getUsername());
    }
 
    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}

