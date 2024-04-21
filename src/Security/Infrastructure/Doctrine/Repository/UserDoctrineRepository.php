<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Doctrine\Repository;

use App\Core\Domain\Model\ValueObject\Email;
use App\Core\Domain\Model\ValueObject\Identifier;
use App\Security\Domain\Model\Entity\User;
use App\Security\Domain\Model\ValueObject\Password;
use App\Security\Domain\Port\Repository\UserRepository;
use App\Security\Infrastructure\Doctrine\Entity\User as DoctrineUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

/**
 * @extends ServiceEntityRepository<User>
 */
final class UserDoctrineRepository extends ServiceEntityRepository implements UserRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, DoctrineUser::class);
    }

    public function register(User $user): void
    {
        $this->getEntityManager()->persist(DoctrineUser::fromSecurityUser($user));
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    public function isAlreadyUsed(Email|string $email): bool
    {
        return $this->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->where('u.email = :email')
                ->setParameter('email', (string) $email)
                ->getQuery()
                ->getSingleScalarResult() > 0;
    }

    public function findByEmail(Email $email): ?User
    {
        /** @var DoctrineUser|null $doctrineUser */
        $doctrineUser = $this->findOneBy(['email' => $email->value()]);

        if (null === $doctrineUser) {
            throw new UserNotFoundException(sprintf('User (email: %s) not found', $email));
        }

        return self::hydrateUserFromDoctrineEntity($doctrineUser);
    }

    private static function hydrateUserFromDoctrineEntity(DoctrineUser $doctrineUser): User
    {
        return new User(
            Identifier::fromUlid($doctrineUser->id),
            Email::create($doctrineUser->email),
            Password::create($doctrineUser->password),
        );
    }
}
