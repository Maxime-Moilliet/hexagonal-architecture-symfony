<?php

declare(strict_types=1);

namespace Tests\Unit\Security;

use App\Core\Domain\Model\ValueObject\Email;
use App\Security\Domain\Model\Entity\User;
use App\Security\Domain\Model\Factory\RegisterUserFactory;
use App\Security\Domain\UseCase\SignUp\NewUserCommand;
use App\Security\Domain\UseCase\SignUp\SignUp;
use App\Security\Domain\Validation\Validator\UniqueEmailValidator;
use Tests\FakerTrait;
use Tests\Fixtures\Core\Doctrine\Repository\FakeUserRepository;
use Tests\Fixtures\Security\Hasher\FakePasswordHasher;
use Tests\Unit\UseCaseTestCase;

final class SignUpTest extends UseCaseTestCase
{
    use FakerTrait;

    private FakeUserRepository $fakeUserRepository;

    protected function setUp(): void
    {
        $this->fakeUserRepository = new FakeUserRepository();

        $this->setValidator([
            UniqueEmailValidator::class => new UniqueEmailValidator($this->fakeUserRepository),
        ]);

        $fakePasswordHasher = new FakePasswordHasher();

        $this->setUseCase(
            new SignUp(
                $this->fakeUserRepository,
                $fakePasswordHasher,
            )
        );
    }

    public function testShouldSignUp(): void
    {
        $newUser = new NewUserCommand();
        $newUser->email = 'user@email.com';
        $newUser->password = '4234df00-45dd-49a4-b303-a75dbf8b10d8!';

        $this->handle($newUser);

        $user = $this->fakeUserRepository->findByEmail(Email::create($newUser->email));

        self::assertInstanceOf(User::class, $user);
        self::assertSame('hashed_password', $user->password()->value());
    }

    /**
     * @dataProvider provideInvalidData
     *
     * @param array<array{propertyPath: string, message: string}> $expectedViolations
     */
    public function testShouldRaiseValidationFailedException(array $expectedViolations, NewUserCommand $newUserCommand): void
    {
        $this->expectedViolations($expectedViolations);
        $this->handle($newUserCommand);
    }

    /**
     * @return iterable<array{
     *     expectedViolations: array<array{propertyPath: string, message: string}>,
     *     newUserCommand: NewUserCommand
     * }>
     */
    public static function provideInvalidData(): iterable
    {
        yield 'blank email' => [
            'expectedViolations' => [
                [
                    'propertyPath' => 'email',
                    'message' => 'This value should not be blank.',
                ],
            ],
            'newUserCommand' => self::createNewUser('', self::faker()->password(20)),
        ];

        yield 'invalid email' => [
            'expectedViolations' => [
                [
                    'propertyPath' => 'email',
                    'message' => 'This value is not a valid email address.',
                ],
            ],
            'newUserCommand' => self::createNewUser('fail', self::faker()->password(20)),
        ];

        yield 'blank password' => [
            'expectedViolations' => [
                [
                    'propertyPath' => 'password',
                    'message' => 'The password strength is too low. Please use a stronger password.',
                ],
            ],
            'newUserCommand' => self::createNewUser('user@email.com', ''),
        ];

        yield 'compromised password' => [
            'expectedViolations' => [
                [
                    'propertyPath' => 'password',
                    'message' => 'This password has been leaked in a data breach, it must not be used. Please use another password.',
                ],
            ],
            'newUserCommand' => self::createNewUser('user@email.com', 'Password123!'),
        ];
    }

    private static function createNewUser(string $email, string $password): NewUserCommand
    {
        $newUserCommand = new NewUserCommand();
        $newUserCommand->email = $email;
        $newUserCommand->password = $password;

        return $newUserCommand;
    }
}
