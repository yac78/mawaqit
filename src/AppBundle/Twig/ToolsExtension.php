<?php

namespace AppBundle\Twig;

use AppBundle\Service\ToolsService;
use Symfony\Component\Intl\Intl;

class ToolsExtension extends \Twig_Extension
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
            new \Twig_SimpleFilter('langTrans', array($this, 'langTrans')),
        ];
    }

    public function countryFilter($countryCode, $locale = null)
    {
        return $this->toolsService->getCountryNameByCode($countryCode, $locale);
    }

    public function langTrans($code, $locale)
    {
        return Intl::getLanguageBundle()->getLanguageName($code, null, $locale);
    }

    public function getName()
    {
        return 'tools_extension';
    }
}