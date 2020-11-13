<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskAutomationPlugin\Elements;

use Alpdesk\AlpdeskCore\Elements\AlpdeskCoreElement;
use Alpdesk\AlpdeskCore\Library\Mandant\AlpdescCoreBaseMandantInfo;
use Alpdesk\AlpdeskAutomationPlugin\Model\AlpdeskautomationhistoryModel;
use Contao\Environment;
use Contao\FrontendTemplate;
use Contao\StringUtil;

class AlpdeskElementAutomationHistory extends AlpdeskCoreElement {

  protected bool $customTemplate = true;
  public static $TYPE_INPUT = 1000;
  public static $TYPE_OUTPUT = 2000;
  public static $TYPE_TEMPERATURE = 3000;
  public static $TYPE_SCENE = 4000;
  public static $TYPE_TIME = 5000;
  public static $TYPE_DIMMERDEVICE = 6000;
  public static $TYPE_SENSOR = 7000;
  public static $TYPE_DHT22 = 8000;
  public static $TYPE_HEATINGPUMP = 9000;
  public static $TYPE_VENTILATION = 10000;
  public static $TYPE_SHADING = 11000;
  public static $TYPE_ANALOGIN = 12000;

  private function history(int $mandantId): string {

    $returnValue = '';

    if ($mandantId <= 0) {
      throw new \Exception('MandantId not found');
    }

    $dbItems = AlpdeskautomationhistoryModel::findBy(array('mandant=?'), array($mandantId), array('order' => 'tstamp ASC'));

    if ($dbItems !== null) {

      $hData = array();

      foreach ($dbItems as $dbItem) {

        $data = json_decode($dbItem->data, true);
        if (count($data) > 0) {

          foreach ($data as $item) {
            if (isset($item['devicevalue']['type'])) {

              $type = intval($item['devicevalue']['type']);
              $handle = intval($item['devicehandle']);
              $tstamp = intval($item['tstamp']);
              $date = $item['date'];

              if ($type == self::$TYPE_SENSOR) {
                if (!array_key_exists($handle, $hData)) {
                  $hData[$handle] = array();
                }
                $value = floatval(intval($item['devicevalue']['properties'][0]['value']) / 10.0);
                array_push($hData[$handle], array(
                    'tstamp' => $tstamp,
                    'date' => StringUtil::convertEncoding(date('Y-m-d H:i:s', $tstamp), 'UTF-8'),
                    'title' => StringUtil::convertEncoding($item['devicevalue']['categorie'] . ' / ' . $item['devicevalue']['name'], 'UTF-8'),
                    'label' => StringUtil::convertEncoding($item['devicevalue']['properties'][0]['displayName'], 'UTF-8'),
                    'value' => StringUtil::convertEncoding($value, 'UTF-8')
                ));
              } else if ($type == self::$TYPE_TEMPERATURE) {

                if (!array_key_exists($handle, $hData)) {
                  $hData[$handle] = array();
                }
                $value = floatval(intval($item['devicevalue']['properties'][1]['value']) / 10.0);
                array_push($hData[$handle], array(
                    'tstamp' => $tstamp,
                    'date' => StringUtil::convertEncoding(date('Y-m-d H:i:s', $tstamp), 'UTF-8'),
                    'title' => StringUtil::convertEncoding($item['devicevalue']['categorie'] . ' / ' . $item['devicevalue']['name'], 'UTF-8'),
                    'label' => StringUtil::convertEncoding($item['devicevalue']['properties'][1]['displayName'], 'UTF-8'),
                    'value' => StringUtil::convertEncoding($value, 'UTF-8')
                ));
              } else if ($type == self::$TYPE_ANALOGIN) {

                if (!array_key_exists($handle, $hData)) {
                  $hData[$handle] = array();
                }
                $value = floatval($item['devicevalue']['properties'][1]['value']);
                array_push($hData[$handle], array(
                    'tstamp' => $tstamp,
                    'date' => StringUtil::convertEncoding(date('Y-m-d H:i:s', $tstamp), 'UTF-8'),
                    'title' => StringUtil::convertEncoding($item['devicevalue']['categorie'] . ' / ' . $item['devicevalue']['name'], 'UTF-8'),
                    'label' => StringUtil::convertEncoding($item['devicevalue']['properties'][0]['value'], 'UTF-8'),
                    'value' => StringUtil::convertEncoding($value, 'UTF-8')
                ));
              }
            }
          }
        }
      }

      $template = new FrontendTemplate('alpdeskautomationplugin_historychart');
      $template->hData = $hData;
      $returnValue = $template->parse();
    } else {
      $returnValue = 'no data avalible';
    }

    return $returnValue;
  }

  public function execute(AlpdescCoreBaseMandantInfo $mandantInfo, array $data): array {
    $response = array(
        'ngContent' => 'error loading data',
        'ngStylesheetUrl' => array(
            0 => Environment::get('base') . 'bundles/alpdeskautomationplugin/automationhistory.css'
        ),
        'ngScriptUrl' => array(
            0 => Environment::get('base') . 'assets/jquery/js/jquery.js',
            1 => Environment::get('base') . 'bundles/alpdeskautomationplugin/plotly.min.js',
            2 => Environment::get('base') . 'bundles/alpdeskautomationplugin/automationhistory.js',
        )
    );
    if (\is_array($data)) {
      try {
        $response['ngContent'] = $this->history($mandantInfo->getId());
      } catch (\Exception $ex) {
        $response['ngContent'] = $ex->getMessage();
      }
    }
    return $response;
  }

}
