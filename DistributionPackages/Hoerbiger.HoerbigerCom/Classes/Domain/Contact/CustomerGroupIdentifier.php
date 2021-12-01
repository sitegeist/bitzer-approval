<?php declare(strict_types=1);
namespace Hoerbiger\HoerbigerCom\Domain\Contact;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class CustomerGroupIdentifier implements PseudoEnumInterface, \JsonSerializable
{
    const IDENTIFIER_EXISTINGCUSTOMER = 'existingCustomer';
    const IDENTIFIER_NEWCUSTOMER = 'newCustomer';
    const IDENTIFIER_SUPPLIER = 'supplier';
    const IDENTIFIER_JOBCANDIDATE = 'jobCandidate';
    const IDENTIFIER_MEDIAREPRESENTATIVE = 'mediaRepresentative';

    /**
     * @var array<string,self>|self[]
     */
    private static array $instances;

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $string): self
    {
        if (!isset(self::$instances[$string])) {
            if ($string !== self::IDENTIFIER_EXISTINGCUSTOMER
                && $string !== self::IDENTIFIER_NEWCUSTOMER
                && $string !== self::IDENTIFIER_SUPPLIER
                && $string !== self::IDENTIFIER_JOBCANDIDATE
                && $string !== self::IDENTIFIER_MEDIAREPRESENTATIVE) {
                throw CustomerGroupIdentifierIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function existingCustomer(): self
    {
        return self::from(self::IDENTIFIER_EXISTINGCUSTOMER);
    }

    public static function newCustomer(): self
    {
        return self::from(self::IDENTIFIER_NEWCUSTOMER);
    }

    public static function supplier(): self
    {
        return self::from(self::IDENTIFIER_SUPPLIER);
    }

    public static function jobCandidate(): self
    {
        return self::from(self::IDENTIFIER_JOBCANDIDATE);
    }

    public static function mediaRepresentative(): self
    {
        return self::from(self::IDENTIFIER_MEDIAREPRESENTATIVE);
    }

    public function getIsExistingCustomer(): bool
    {
        return $this->value === self::IDENTIFIER_EXISTINGCUSTOMER;
    }

    public function getIsNewCustomer(): bool
    {
        return $this->value === self::IDENTIFIER_NEWCUSTOMER;
    }

    public function getIsSupplier(): bool
    {
        return $this->value === self::IDENTIFIER_SUPPLIER;
    }

    public function getIsJobCandidate(): bool
    {
        return $this->value === self::IDENTIFIER_JOBCANDIDATE;
    }

    public function getIsMediaRepresentative(): bool
    {
        return $this->value === self::IDENTIFIER_MEDIAREPRESENTATIVE;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::IDENTIFIER_EXISTINGCUSTOMER),
            self::from(self::IDENTIFIER_NEWCUSTOMER),
            self::from(self::IDENTIFIER_SUPPLIER),
            self::from(self::IDENTIFIER_JOBCANDIDATE),
            self::from(self::IDENTIFIER_MEDIAREPRESENTATIVE)
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
