<?php

namespace AppBundle\Twig;

use AppBundle\Service\ToolsService;

class CountryExtension extends \Twig_Extension
{

    /**
     * @var ToolsService
     */
    private $toolsService;

    public function __construct(ToolsService $toolsService)
    {
        $this->toolsService = $toolsService;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('country', array($this, 'countryFilter')),
        ];
    }

    public function countryFilter($countryCode)
    {
        return $this->toolsService->getCountryNameByCode($countryCode);
    }

    public function getName()
    {
        return 'country_extension';
    }
}