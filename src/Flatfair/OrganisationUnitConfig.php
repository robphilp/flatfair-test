<?php

namespace Flatfair;

class OrganisationUnitConfig
{
    /** @var bool */
    private $hasFixedMembershipFee;

    /** @var int */
    private $fixedMemberShipFeeAmount;

    /**
     * OrganisationUnitConfig constructor.
     * @param bool $hasFixedMembershipFee
     * @param int $fixedMemberShipFeeAmount
     */
    public function __construct($hasFixedMembershipFee, $fixedMemberShipFeeAmount)
    {
        $this->hasFixedMembershipFee    = $hasFixedMembershipFee;
        $this->fixedMemberShipFeeAmount = $fixedMemberShipFeeAmount;
    }

    /**
     * @return bool
     */
    public function hasFixedMembershipFee()
    {
        return $this->hasFixedMembershipFee;
    }

    /**
     * @return int
     */
    public function getFixedMemberShipFeeAmount()
    {
        return $this->fixedMemberShipFeeAmount;
    }
}