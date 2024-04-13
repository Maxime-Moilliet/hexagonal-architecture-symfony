<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Repository;

use App\Core\Domain\Model\ValueObject\Email;
use App\Core\Domain\Model\ValueObject\Identifier;
use App\Core\Infrastructure\Doctrine\Entity\User as DoctrineUser;
use App\Security\Domain\Model\Entity\User;
use App\Security\Domain\Model\Factory\CreateUserFactory;
use App\Security\Domain\Model\ValueObject\Password;
use App\Security\Domain\Port\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
final class UserDoctrineRepository extends ServiceEntityRepository implements UserRepository
{
    public function __construct(
        ManagerRegistry                    $registry,
        private readonly CreateUserFactory $createUserFactory
    )
    {
        parent::__construct($registry, DoctrineUser::class);
    }

    public function register(User $user): void
    {
        $this->getEntityManager()->persist(DoctrineUser::fromSecurityUser($user));
        $this->getEntityManager()->flush();
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

        return null === $doctrineUser ? null : $this->createUserFactory
            ->withId(Identifier::fromUlid($doctrineUser->id))
            ->withEmail(Email::create($doctrineUser->email))
            ->withPassword(Password::create($doctrineUser->password))
            ->build();
    }
}
