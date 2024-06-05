<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Validator;

use App\Web\Service\Api\GetCatchStatesService;
use App\Web\Service\ApiService;
use App\Web\Validator\CatchStates;
use App\Web\Validator\CatchStatesValidator;
use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<CatchStatesValidator>
 */
class CatchStatesValidatorTest extends ConstraintValidatorTestCase
{
    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new CatchStates());

        $this->assertNoViolation();
    }

    #[DataProvider('providerInvalidConstraints')]
    public function testTrueIsInvalid(CatchStates $constraint): void
    {
        $this->validator->validate('douze', $constraint);

        $this->buildViolation('"{{ string }}" is not a valid catch state')
            ->setParameter('{{ string }}', 'douze')
            ->assertRaised();
    }

    /**
     * @return \ArrayIterator<int, CatchStates[]>
     */
    public static function providerInvalidConstraints(): iterable
    {
        yield [new CatchStates()];
    }

    #[DataProvider('providerValidConstraints')]
    public function testTrueIsValid(CatchStates $constraint): void
    {
        $this->validator->validate('maybenot', $constraint);

        $this->assertNoViolation();
    }

    /**
     * @return \ArrayIterator<int, CatchStates[]>
     */
    public static function providerValidConstraints(): iterable
    {
        yield [new CatchStates()];
    }

    public function testUnexpectedType(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $this->validator->validate('maybenot', new NotNull());
    }

    public function testUnexpectedValue(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $this->validator->validate(new DateTime(), new CatchStates());
    }

    protected function createValidator(): CatchStatesValidator
    {
        $getService = $this->createMock(GetCatchStatesService::class);

        $getService
            ->method('get')
            ->willReturn([
                [
                  'name' => 'No',
                  'frenchName' => 'Non',
                  'slug' => 'no',
                  'color' => '#e57373',
                ],
                [
                  'name' => 'Maybe',
                  'frenchName' => 'Peut être',
                  'slug' => 'maybe',
                  'color' => '#9575cd',
                ],
                [
                  'name' => 'Maybe not',
                  'frenchName' => 'Peut être pas',
                  'slug' => 'maybenot',
                  'color' => '#9575cd',
                ],
                [
                  'name' => 'Yes',
                  'frenchName' => 'Oui',
                  'slug' => 'yes',
                  'color' => '#66bb6a',
                ],
              ])
        ;

        return new CatchStatesValidator($getService);
    }
}
