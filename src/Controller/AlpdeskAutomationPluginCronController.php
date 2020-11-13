<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskAutomationPlugin\Controller;

use Contao\CoreBundle\Framework\ContaoFramework;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Alpdesk\AlpdeskAutomationPlugin\Model\AlpdeskautomationitemsModel;
use Alpdesk\AlpdeskAutomationPlugin\Model\AlpdeskautomationhistoryModel;
use Alpdesk\AlpdeskCore\Model\Mandant\AlpdeskcoreMandantModel;
use Alpdesk\AlpdeskAutomationPlugin\Elements\AlpdeskElementAutomationHistory;

class AlpdeskAutomationPluginCronController extends AbstractController {

  protected ContaoFramework $framework;

  public function __construct(ContaoFramework $framework) {
    $this->framework = $framework;
    $this->framework->initialize();
  }

  private function deleteOldItems($mandant, $limitDays) {
    if ($limitDays <= 0) {
      $limitDays = 14;
    }
    $constTime = time() - (60 * 60 * 24 * $limitDays);
    $deleteHistoryItems = AlpdeskautomationhistoryModel::findBy(array('mandant=?', 'tstamp<?'), array($mandant, $constTime));
    if ($deleteHistoryItems !== null) {
      foreach ($deleteHistoryItems as $dItem) {
        $dItem->delete();
      }
    }
  }

  public function cron(Request $request): Response {

    $returnvalue = array(
        'error' => true,
        'msg' => ''
    );

    try {

      $dbItems = AlpdeskautomationitemsModel::findAll();
      if ($dbItems !== null) {

        $data = array();
        $mandantCronIntervals = array();

        foreach ($dbItems as $dbItem) {

          if (!array_key_exists($dbItem->mandant, $mandantCronIntervals)) {

            $mandantCronIntervals[$dbItem->mandant] = true;

            $mandantInfo = AlpdeskcoreMandantModel::findById($dbItem->mandant);
            if ($mandantInfo !== null) {
              $this->deleteOldItems($mandantInfo->id, $mandantInfo->automationhistorylimit);
              $mandantHistoryItems = AlpdeskautomationhistoryModel::findBy(array('mandant=?'), array($mandantInfo->id), array('order' => 'tstamp DESC', 'limit' => 1));
              if ($mandantHistoryItems !== null) {
                $secureOffsetSecondsForScriptDuration = 20;
                if (($mandantHistoryItems->tstamp + (60 * intval($mandantInfo->automationhistorycroninterval)) - $secureOffsetSecondsForScriptDuration) > time()) {
                  $mandantCronIntervals[$dbItem->mandant] = false;
                }
              }
            }
          }

          if ($mandantCronIntervals[$dbItem->mandant] == true) {

            if (!array_key_exists($dbItem->mandant, $data)) {
              $data[$dbItem->mandant] = array();
            }

            $deviceValue = json_decode($dbItem->devicevalue, true);

            if (isset($deviceValue['type'])) {

              $type = intval($deviceValue['type']);

              if ($type == AlpdeskElementAutomationHistory::$TYPE_SENSOR || $type == AlpdeskElementAutomationHistory::$TYPE_TEMPERATURE || $type == AlpdeskElementAutomationHistory::$TYPE_ANALOGIN) {

                $date = date('d.m.Y H:i', intval($dbItem->tstamp));
                array_push($data[$dbItem->mandant], array(
                    'tstamp' => $dbItem->tstamp,
                    'date' => $date,
                    'devicehandle' => $dbItem->devicehandle,
                    'devicevalue' => $deviceValue
                ));
              }
            }
          }
        }

        if (count($data) > 0) {
          foreach ($data as $mandant => $data) {
            $historyItem = new AlpdeskautomationhistoryModel();
            $historyItem->tstamp = time();
            $historyItem->mandant = $mandant;
            $historyItem->data = json_encode($data);
            $historyItem->save();
          }
        }

        $returnvalue['error'] = false;
      }
    } catch (\Exception $ex) {
      $returnvalue['error'] = true;
      $returnvalue['msg'] = $ex->getMessage();
    }

    return $this->json($returnvalue);
  }

}
