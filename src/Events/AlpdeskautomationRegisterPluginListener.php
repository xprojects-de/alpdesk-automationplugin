<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskAutomationPlugin\Events;

use Alpdesk\AlpdeskCore\Events\Event\AlpdeskCoreRegisterPlugin;

class AlpdeskautomationRegisterPluginListener {

  public function __invoke(AlpdeskCoreRegisterPlugin $event): void {

    $data = $event->getPluginData();
    $info = $event->getPluginInfo();

    $data['automation'] = $GLOBALS['TL_LANG']['ADME']['automationplugin'];
    $info['automation'] = [
        'customTemplate' => false
    ];

    $data['automationhistory'] = $GLOBALS['TL_LANG']['ADME']['automationhistoryplugin'];
    $info['automationhistory'] = [
        'customTemplate' => true
    ];

    $event->setPluginData($data);
    $event->setPluginInfo($info);
  }

}
