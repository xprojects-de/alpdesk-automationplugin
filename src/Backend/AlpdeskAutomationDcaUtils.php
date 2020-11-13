<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskAutomationPlugin\Backend;

use Contao\Backend;
use Alpdesk\AlpdeskCore\Model\Mandant\AlpdeskcoreMandantModel;

class AlpdeskAutomationDcaUtils extends Backend {

  public function showLabelItems($row, $label, $dc, $args): array {
    $args[0] = date('Y-m-d H:i:s', intval($args[0]));
    try {
      $mandant = AlpdeskcoreMandantModel::findById(intval($args[1]));
      if ($mandant !== null) {
        $args[1] = $mandant->mandant;
      }
    } catch (\Exception $ex) {
      
    }
    return $args;
  }

  public function showLabelChanges($row, $label, $dc, $args): array {
    try {
      $mandant = AlpdeskcoreMandantModel::findById(intval($args[0]));
      if ($mandant !== null) {
        $args[0] = $mandant->mandant;
      }
    } catch (\Exception $ex) {
      
    }
    return $args;
  }

}
