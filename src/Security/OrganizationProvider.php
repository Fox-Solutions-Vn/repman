<?php

declare(strict_types=1);

namespace Buddy\Repman\Security;

use Buddy\Repman\Security\Model\Organization;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class OrganizationProvider implements UserProviderInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function loadUserByUsername(string $username)
    {
        $data = $this->getUserDataByToken($username);

        if ($data === false) {
            throw new BadCredentialsException();
        }

        return $this->hydrateOrganization($data);
    }

    public function refreshUser(UserInterface $user)
    {
        $data = $this->getUserDataByToken($user->getUsername());

        if ($data === false) {
            throw new UsernameNotFoundException();
        }

        return $this->hydrateOrganization($data);
    }

    public function supportsClass(string $class)
    {
        return $class === Organization::class;
    }

    /**
     * @return false|mixed[]
     */
    private function getUserDataByToken(string $token)
    {
        return $this->connection->fetchAssoc('
            SELECT t.value, o.name, o.id FROM organization_token t 
            JOIN organization o ON o.id = t.organization_id 
            WHERE t.value = :token',
            [
                ':token' => $token,
            ]);
    }

    /**
     * @param mixed[] $data
     */
    private function hydrateOrganization(array $data): Organization
    {
        return new Organization(
            $data['id'],
            $data['name'],
            $data['value']
        );
    }
}
