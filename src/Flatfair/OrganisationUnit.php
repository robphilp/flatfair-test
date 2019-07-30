<?php

namespace Flatfair;

class OrganisationUnit
{
    /** @var string */
    private $name;

    /** @var OrganisationUnitConfig|null */
    private $config = null;

    /** @var OrganisationUnit|null */
    private $parent = null;

    /** @var OrganisationUnit[] */
    private $children;

    /**
     * OrganisationUnit constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name   = $name;
    }

    /**
     * @param OrganisationUnit|null $parent
     */
    public function setParent(?OrganisationUnit $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return OrganisationUnit|null
     */
    public function getParent(): ?OrganisationUnit
    {
        return $this->parent;
    }

    /**
     * @return OrganisationUnitConfig|null
     */
    public function getConfig(): ?OrganisationUnitConfig
    {
        return $this->config;
    }

    /**
     * @param OrganisationUnitConfig|null $config
     */
    public function setConfig(?OrganisationUnitConfig $config = null)
    {
        $this->config = $config;
    }

    /**
     * @param string $name
     * @param OrganisationUnit $organisationUnit
     */
    public function addChild(string $name, OrganisationUnit $organisationUnit)
    {
        $organisationUnit->setParent($this);
        $this->children[$name] = $organisationUnit;
    }
}