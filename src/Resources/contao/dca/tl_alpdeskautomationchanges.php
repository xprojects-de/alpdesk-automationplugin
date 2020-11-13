<?php

$GLOBALS['TL_DCA']['tl_alpdeskautomationchanges'] = array(
    'config' => array(
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'sql' => array
            (
            'keys' => array
                (
                'id' => 'primary'
            )
        )
    ),
    'list' => array(
        'sorting' => array(
            'mode' => 1,
            'fields' => array('mandant'),
            'flag' => 1,
            'panelLayout' => 'limit',
        ),
        'label' => array(
            'fields' => array('mandant', 'devicehandle'),
            'showColumns' => true,
            'label_callback' => array('Alpdesk\\AlpdeskAutomationPlugin\\Backend\\AlpdeskAutomationDcaUtils', 'showLabelChanges')
        ),
        'global_operations' => array(
            'all' => array(
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"'
            )
        ),
        'operations' => array(
            'edit' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_alpdeskautomationchanges']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif'
            ),
            'delete' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_alpdeskautomationchanges']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            )
        )
    ),
    'palettes' => array(
        '__selector__' => array(''),
        'default' => 'mandant,devicehandle,devicevalue',
    ),
    'subpalettes' => array(
        '' => ''
    ),
    'fields' => array
        (
        'id' => array
            (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
            (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array
            (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'mandant' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_alpdeskautomationchanges']['mandant'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'select',
            'foreignKey' => 'tl_alpdeskcore_mandant.mandant',
            'eval' => array('mandatory' => true, 'tl_class' => 'w50'),
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'devicehandle' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_alpdeskautomationchanges']['devicehandle'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'devicevalue' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_alpdeskautomationchanges']['devicevalue'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'textarea',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "blob NULL"
        ),
    )
);
