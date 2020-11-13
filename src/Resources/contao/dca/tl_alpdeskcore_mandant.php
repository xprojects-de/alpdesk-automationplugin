<?php

$GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['palettes']['default'] = $GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['palettes']['default'] . ';automationhistorycroninterval,automationhistorylimit';
$GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['fields']['automationhistorycroninterval'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_alpdeskcore_mandant']['automationhistorycroninterval'],
    'exclude' => true,
    'search' => true,
    'default' => '15',
    'inputType' => 'text',
    'eval' => array('mandatory' => true, 'maxlength' => 5, 'tl_class' => 'w50', 'rgxp' => 'digit'),
    'sql' => "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['fields']['automationhistorylimit'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_alpdeskcore_mandant']['automationhistorylimit'],
    'exclude' => true,
    'search' => true,
    'default' => '30',
    'inputType' => 'text',
    'eval' => array('mandatory' => true, 'maxlength' => 5, 'tl_class' => 'w50', 'rgxp' => 'digit'),
    'sql' => "int(10) unsigned NOT NULL default '0'"
);


