<?php

namespace CatMS\AuthBundle\Repository;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * UserRepository
 * 
 */
class UserRepository extends EntityRepository implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
        ;

        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf(
                'Unable to find an active admin CatMSAuthBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }
    
    public function findEmail($email) 
    {
        return $this->getManager()
            ->createQuery('SELECT u FROM CatMSAuthBundle:User u
                WHERE u.email = :email')
            ->setParameters(array('email' => $email))
            ->getResult();
    }

    public function findAllAdmins($roles)
    {
        if ($roles == 'all') {
            return $this->createQueryBuilder('u')
                ->where('u.roles LIKE :roleAdmin')
                ->orWhere('u.roles LIKE :roleDeveloper')
                ->setParameters(array(
                    'roleAdmin' => '%ROLE_ADMIN%',
                    'roleDeveloper' => '%ROLE_DEVELOPER%'
                ))
                ->getQuery()
                ->execute();
        }

        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_' . strtoupper($roles) . '%')
            ->getQuery()
            ->execute();
    }
}

?>