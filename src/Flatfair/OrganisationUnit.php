<?php

namespace Flatfair;

class OrganisationUnit
{
    /** @var string */
    private $name;

    /** @var OrganisationUnitConfig */
    private $config;

    /** @var OrganisationUnit|null */
    private $parent = null;

    /** @var OrganisationUnit[] */
    private $children;

    /**
     * OrganisationUnit constructor.
     * @param string $name
     * @param OrganisationUnitConfig $config
     * @param OrganisationUnit|null $parent
     */
    public function __construct(string $name, OrganisationUnitConfig $config, ?OrganisationUnit $parent)
    {
        $this->name   = $name;
        $this->config = $config;
        $this->parent = $parent;
    }
}