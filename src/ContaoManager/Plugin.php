<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskAutomationPlugin\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Config\ConfigInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Alpdesk\AlpdeskCore\AlpdeskCoreBundle;
use Alpdesk\AlpdeskAutomationPlugin\AlpdeskAutomationPluginBundle;

class Plugin implements BundlePluginInterface, RoutingPluginInterface {

  public function getBundles(ParserInterface $parser) {
    return [
                BundleConfig::create(AlpdeskAutomationPluginBundle::class)
                ->setLoadAfter([
                    ContaoCoreBundle::class,
                    AlpdeskCoreBundle::class
                ]),
    ];
  }

  public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel) {
    $file = __DIR__ . '/../Resources/config/routes.yml';
    return $resolver->resolve($file)->load($file);
  }

}
