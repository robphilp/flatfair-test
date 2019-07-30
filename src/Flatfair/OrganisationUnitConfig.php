<?php

namespace Flatfair;

class OrganisationUnitConfig
{
    /** @var bool */
    private $hasFixedMembershipFee;

    /** @var int|null */
    private $fixedMemberShipFeeAmount = null;

    /**
     * OrganisationUnitConfig constructor.
     *
     * @param bool $hasFixedMembershipFee
     * @param int|null $fixedMemberShipFeeAmount
     */
    public function __construct(bool $hasFixedMembershipFee, ?int $fixedMemberShipFeeAmount = null)
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