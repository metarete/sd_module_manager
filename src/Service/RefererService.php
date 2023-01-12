<?php

namespace App\Service;

use Symfony\Component\Routing\RouterInterface;
use function Symfony\Component\String\u;
use Symfony\Component\HttpFoundation\Request;

class RefererService
{
    private $router;
    private $appName;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getRouteName(Request $request)
    {
        $referer = $request->headers->get('referer');
        $refererStr = u($referer);
        if ($refererStr->isEmpty()) {
            //non va bene
        }
        $refererPathInfo = Request::create($referer)->getPathInfo();
        $routeInfos = $this->router->match($refererPathInfo);
        $this->appName = $routeInfos['_route'] ?? '';
        
    }
    public function setAppName(string $appName)
    {
        $this->appName = $appName;
    }
    public function getAppName()
    {
        return $this->appName;
    }
}