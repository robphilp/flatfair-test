<?php

namespace Flatfair\Tests;

use Flatfair\FeeCalculator;
use Flatfair\OrganisationUnit;
use Flatfair\OrganisationUnitConfig;

class FeeCalculatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     *
     * @throws \Exception
     */
    public function throwsExceptionWithBadInput()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Rent period must be week or month');

        $config = new OrganisationUnitConfig(true, 500);
        $organisation = new OrganisationUnit('Independent Organisation');
        $organisation->setConfig($config);

        $config = $organisation->getConfig();
        $this->assertInstanceOf(OrganisationUnitConfig::class, $config);

        $feeCalculator = new FeeCalculator();
        $feeCalculator->calculate(15000, "Annual", $organisation);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function canUseConfigFixedFeeForWeeklyRent()
    {
        $config = new OrganisationUnitConfig(true, 500);
        $organisation = new OrganisationUnit('Independent Organisation');
        $organisation->setConfig($config);

        $config = $organisation->getConfig();
        $this->assertInstanceOf(OrganisationUnitConfig::class, $config);

        // Calculate
        $feeCalculator = new FeeCalculator();
        $fee = $feeCalculator->calculate(15000, FeeCalculator::PERIOD_WEEK, $organisation);

        // Use fixed fee set in config, as above
        $this->assertEquals(500, $fee);
    }

    public function weeklyRentTestDataProvider()
    {
        return [
            [10000, 14400],
            [12500, 15000],
            [20000, 24000],
            [25000, 30000],
        ];
    }

    /**
     * @test
     * @dataProvider weeklyRentTestDataProvider
     *
     * @param int $weeklyRentAmount
     * @param int $expectedFeeAmount
     *
     * @throws \Exception
     */
    public function canUseConfigVariableFeeForWeeklyRent(int $weeklyRentAmount, int $expectedFeeAmount)
    {
        $config = new OrganisationUnitConfig(false);
        $organisation = new OrganisationUnit('Independent Organisation');
        $organisation->setConfig($config);

        $config = $organisation->getConfig();
        $this->assertInstanceOf(OrganisationUnitConfig::class, $config);

        // Calculate
        $feeCalculator = new FeeCalculator();
        $fee = $feeCalculator->calculate($weeklyRentAmount, FeeCalculator::PERIOD_WEEK, $organisation);

        // Assert expected fee
        $this->assertEquals($expectedFeeAmount, $fee);
    }

    public function monthlyRentTestDataProvider()
    {
        return [
            [35000, 14400],
            [87500, 24230],
            [100000, 27692],
            [175000, 48461],
        ];
    }

    /**
     * @test
     * @dataProvider monthlyRentTestDataProvider
     *
     * @param int $monthlyRentAmount
     * @param $expectedFeeAmount
     *
     * @throws \Exception
     */
    public function canUseConfigVariableFeeForMonthlyRent(int $monthlyRentAmount, $expectedFeeAmount)
    {
        $config = new OrganisationUnitConfig(false);
        $organisation = new OrganisationUnit('Independent Organisation');
        $organisation->setConfig($config);

        $config = $organisation->getConfig();
        $this->assertInstanceOf(OrganisationUnitConfig::class, $config);

        // Calculate
        $feeCalculator = new FeeCalculator();
        $fee = $feeCalculator->calculate($monthlyRentAmount, FeeCalculator::PERIOD_MONTH, $organisation);

        // Assert expected fee
        $this->assertEquals($expectedFeeAmount, $fee);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function useMinimumFeeWhenRentIsTooLow()
    {
        // No fixed fee
        $config = new OrganisationUnitConfig(false);
        $organisation = new OrganisationUnit('Independent Organisation');
        $organisation->setConfig($config);

        $config = $organisation->getConfig();
        $this->assertInstanceOf(OrganisationUnitConfig::class, $config);

        // Calculate
        $feeCalculator = new FeeCalculator();
        $fee = $feeCalculator->calculate(2500, FeeCalculator::PERIOD_WEEK, $organisation);

        // Should use minimum fee level based on low rent (£25 per week - a bargain!!)
        $this->assertEquals(14400, $fee);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function canUseParentConfig()
    {
        // Parent organisation has config with fixed fee
        $parentConfig = new OrganisationUnitConfig(true, 500);
        $parentOrganisation = new OrganisationUnit('Top Organisation');
        $parentOrganisation->setConfig($parentConfig);

        // Child organisation has no config, so we SHOULD walk up the tree to the parent
        $childOrganisation = new OrganisationUnit('Child Organisation');
        $parentOrganisation->addChild('Child A', $childOrganisation);

        // Calculate
        $feeCalculator = new FeeCalculator();
        $fee = $feeCalculator->calculate(123456, FeeCalculator::PERIOD_WEEK, $childOrganisation);

        // Use fixed fee from parent
        $this->assertEquals(500, $fee);
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function canUseNestedParentConfig()
    {
        // Parent organisation has config with fixed fee
        $parentConfig = new OrganisationUnitConfig(true, 750);
        $parentOrganisation = new OrganisationUnit('Top Organisation');
        $parentOrganisation->setConfig($parentConfig);

        // Child organisation has no config, so we SHOULD walk up the tree to the parent
        $childOrganisation = new OrganisationUnit('Child Organisation');
        $parentOrganisation->addChild('Child', $childOrganisation);

        // Sub Child organisation has no config, so we SHOULD walk up the tree to the parent
        $subChildOrganisation = new OrganisationUnit('Sub Child Organisation');
        $childOrganisation->addChild('Sub Child', $subChildOrganisation);

        // Calculate
        $feeCalculator = new FeeCalculator();
        $fee = $feeCalculator->calculate(123456, FeeCalculator::PERIOD_WEEK, $childOrganisation);

        // Use fixed fee from parent
        $this->assertEquals(750, $fee);
    }
}