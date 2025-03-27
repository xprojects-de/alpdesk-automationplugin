<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskAutomationPlugin\Elements;

use Alpdesk\AlpdeskCore\Events\Event\AlpdeskCorePlugincallEvent;
use Alpdesk\AlpdeskAutomationPlugin\Model\AlpdeskautomationitemsModel;
use Alpdesk\AlpdeskAutomationPlugin\Model\AlpdeskautomationchangesModel;

class AlpdeskElementAutomation
{
    /**
     * @param int $mandantId
     * @param array $data
     * @param array $returnValue
     * @return array
     * @throws \Exception
     */
    private function changeItem(int $mandantId, array $data, array $returnValue): array
    {
        if ($mandantId <= 0) {
            throw new \Exception('MandantId not found');
        }

        if (isset($data['devicehandle'], $data['devicevalue'])) {
            if (isset($data['devicevalue']['devicehandle'], $data['devicevalue']['propertiehandle'], $data['devicevalue']['value'])) {
                $dbChange = new AlpdeskautomationchangesModel();
                $dbChange->mandant = $mandantId;
                $dbChange->devicehandle = (int)$data['devicehandle'];
                $dbChange->devicevalue = json_encode($data['devicevalue'], JSON_THROW_ON_ERROR);
                $dbChange->save();
                $returnValue['error'] = false;
            }
        }

        return $returnValue;
    }

    /**
     * @param int $mandantId
     * @param array $returnValue
     * @return array
     * @throws \Exception
     */
    private function listItems(int $mandantId, array $returnValue): array
    {
        if ($mandantId <= 0) {
            throw new \Exception('MandantId not found');
        }

        $returnValue['items'] = array();
        $dbItems = AlpdeskautomationitemsModel::findBy(array('mandant=?'), array($mandantId));
        if ($dbItems !== null) {
            foreach ($dbItems as $dbItem) {
                $date = date('d.m.Y H:i', (int)$dbItem->tstamp);
                $returnValue['items'][] = array(
                    'tstamp' => $dbItem->tstamp,
                    'date' => $date,
                    'devicehandle' => $dbItem->devicehandle,
                    'devicevalue' => json_decode($dbItem->devicevalue, true, 512, JSON_THROW_ON_ERROR)
                );
            }
        }
        $dbChanges = AlpdeskautomationchangesModel::findBy(array('mandant=?'), array($mandantId));
        if ($dbChanges !== null) {
            foreach ($dbChanges as $dbChange) {
                $date = date('d.m.Y H:i', (int)$dbChange->tstamp);
                $returnValue['changes'][] = array(
                    'tstamp' => $dbChange->tstamp,
                    'date' => $date,
                    'devicehandle' => $dbChange->devicehandle,
                    'devicevalue' => json_decode($dbChange->devicevalue, true)
                );
            }
        }
        $returnValue['error'] = false;
        return $returnValue;
    }

    /**
     * @param int $mandantId
     * @param array $data
     * @param array $returnValue
     * @return array
     * @throws \Exception
     */
    private function commitChanges(int $mandantId, array $data, array $returnValue): array
    {
        if ($mandantId <= 0) {
            throw new \Exception('MandantId not found');
        }

        foreach ($data as $deviceHandle => $value) {
            $dbItem = AlpdeskautomationitemsModel::findBy(array('mandant=?', 'devicehandle=?'), array($mandantId, $deviceHandle));
            if ($dbItem !== null) {
                $dbItem->tstamp = time();
                $dbItem->devicevalue = json_encode($value, JSON_THROW_ON_ERROR);
                $dbItem->save();
            } else {
                $dbItem = new AlpdeskautomationitemsModel();
                $dbItem->tstamp = time();
                $dbItem->mandant = $mandantId;
                $dbItem->devicehandle = $deviceHandle;
                $dbItem->devicevalue = json_encode($value, JSON_THROW_ON_ERROR);
                $dbItem->save();
            }
        }

        $dbChanges = AlpdeskautomationchangesModel::findBy(array('mandant=?'), array($mandantId));
        if ($dbChanges !== null) {
            foreach ($dbChanges as $change) {
                $returnValue['changes'][$change->devicehandle] = json_decode($change->devicevalue, true, 512, JSON_THROW_ON_ERROR);
                $change->delete();
            }
        }

        $returnValue['error'] = false;

        return $returnValue;
    }

    public function __invoke(AlpdeskCorePlugincallEvent $event): void
    {
        if ('automation' !== $event->getResultData()->getPlugin()) {
            return;
        }

        $mandantInfo = $event->getResultData()->getMandantInfo();
        $data = $event->getResultData()->getRequestData();

        $response = array(
            'error' => true,
            'changes' => array()
        );

        if (\array_key_exists('method', $data) && \array_key_exists('params', $data)) {

            try {
                switch ($data['method']) {
                    case 'commit':
                        $response = $this->commitChanges($mandantInfo->getId(), $data['params'], $response);
                        break;
                    case 'list':
                        $response = $this->listItems($mandantInfo->getId(), $response);
                        break;
                    case 'change':
                        $response = $this->changeItem($mandantInfo->getId(), $data['params'], $response);
                        break;
                    default:
                        break;
                }
            } catch (\Exception) {
                $response['error'] = true;
            }

        }

        $event->getResultData()->setData($response);
    }

}
