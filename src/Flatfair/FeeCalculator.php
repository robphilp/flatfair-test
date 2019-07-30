<?php

namespace Flatfair;

class FeeCalculator
{
    const PERIOD_WEEK = 'week';
    const PERIOD_MONTH = 'month';

    /** @var float */
    private $vatLevel;

    /**
     * FeeCalculator constructor.
     *
     * @param float $vatLevel
     */
    public function __construct(float $vatLevel = 0.2)
    {
        $this->vatLevel = $vatLevel;
    }

    /**
     * @param int $rentAmount
     * @param string $rentPeriod
     * @param OrganisationUnit $organisationUnit
     *
     * @return int
     *
     * @throws \Exception
     */
    public function calculate(int $rentAmount, string $rentPeriod, OrganisationUnit $organisationUnit)
    {
        // validate period inout
        if (!in_array($rentPeriod, [self::PERIOD_WEEK, self::PERIOD_MONTH])) {
            throw new \InvalidArgumentException('Rent period must be week or month');
        }

        // Validate rent input
        switch ($rentPeriod) {
            case self::PERIOD_WEEK:
                if ($rentAmount < 2500 || $rentAmount > 200000) {
                    throw new \InvalidArgumentException("Weekly rent must be between 2500 and 200000 pence");
                }
                break;
            case self::PERIOD_MONTH:
                if ($rentAmount < 11000 || $rentAmount > 866000) {
                    throw new \InvalidArgumentException("Monthly rent must be between 110000 and 866000 pence");
                }
                break;
        }

        // Get the config object, recursively moving up the tree if necessary
        $config = $this->getOrganisationConfigRecursive($organisationUnit);

        // Throw exception if no config available
        if (is_null($config)) {
            throw new \Exception('No config available in organisation structure');
        }

        // Return fixed fee if relevant, otherwise calculate
        if ($config->hasFixedMembershipFee()) {
            $fee = $config->getFixedMemberShipFeeAmount();
        } else {
            // Calculate weekly rent if monthly rent provided
            if ($rentPeriod == self::PERIOD_MONTH) {
                $rentAmount = ($rentAmount * 12) / 52;
            }

            // Enforce minimum rent for fee
            if ($rentAmount < 12000) {
                $rentAmount = 12000;
            }

            // Add VAT
            $fee = $rentAmount + ($rentAmount * $this->vatLevel);
        }

        return (int) $fee;
    }

    /**
     * @param OrganisationUnit $organisationUnit
     * @return OrganisationUnitConfig|null
     */
    private function getOrganisationConfigRecursive(OrganisationUnit $organisationUnit)
    {
        $config = $organisationUnit->getConfig();

        // Recursively walk up the tree until we get a valid config or we reach the root
        if ($config == null) {
            $parent = $organisationUnit->getParent();
            if ($parent == null) return null;

            return $this->getOrganisationConfigRecursive($parent);
        } else {
            return $config;
        }
    }
}