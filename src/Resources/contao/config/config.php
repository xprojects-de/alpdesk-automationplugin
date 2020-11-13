<?php

use Alpdesk\AlpdeskAutomationPlugin\Model\AlpdeskautomationitemsModel;
use Alpdesk\AlpdeskAutomationPlugin\Model\AlpdeskautomationchangesModel;
use Alpdesk\AlpdeskAutomationPlugin\Model\AlpdeskautomationhistoryModel;

$GLOBALS['TL_MODELS']['tl_alpdeskautomationitems'] = AlpdeskautomationitemsModel::class;
$GLOBALS['TL_MODELS']['tl_alpdeskautomationchanges'] = AlpdeskautomationchangesModel::class;
$GLOBALS['TL_MODELS']['tl_alpdeskautomationhistory'] = AlpdeskautomationhistoryModel::class;

$GLOBALS['TL_ADME']['automation'] = 'Alpdesk\\AlpdeskAutomationPlugin\\Elements\\AlpdeskElementAutomation';
$GLOBALS['TL_ADME']['automationhistory'] = 'Alpdesk\\AlpdeskAutomationPlugin\\Elements\\AlpdeskElementAutomationHistory';

$GLOBALS['BE_MOD']['alpdeskautomation']['alpdeskautomationitems'] = array
    (
    'tables' => array('tl_alpdeskautomationitems')
);
$GLOBALS['BE_MOD']['alpdeskautomation']['alpdeskautomationchanges'] = array
    (
    'tables' => array('tl_alpdeskautomationchanges')
);
$GLOBALS['BE_MOD']['alpdeskautomation']['alpdeskautomationhistory'] = array
    (
    'tables' => array('tl_alpdeskautomationhistory')
);

