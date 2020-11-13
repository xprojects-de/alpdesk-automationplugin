<?php

$GLOBALS['TL_DCA']['tl_alpdeskautomationhistory'] = array
    (
    'config' => array
        (
        'dataContainer' => 'Table',
        'enableVersioning' => false,
        'sql' => array
            (
            'keys' => array
                (
                'id' => 'primary'
            )
        )
    ),
    'list' => array
        (
        'sorting' => array
            (
            'mode' => 2,
            'fields' => array('tstamp DESC'),
            'flag' => 1,
            'panelLayout' => 'filter;sort,search,limit'
        ),
        'label' => array
            (
            'fields' => array('tstamp', 'mandant'),
            'label_callback' => array('tl_alpdeskautomationhistory', 'showLabels'),
            'showColumns' => true
        ),
        'global_operations' => array
            (
            'all' => array
                (
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
            (
            'edit' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_alpdeskautomationhistory']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif'
            ),
            'delete' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_alpdeskautomationhistory']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            )
        )
    ),
    'palettes' => array
        (
        'default' => 'mandant;data'
    ),
    'fields' => array
        (
        'id' => array
            (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
            (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'mandant' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_alpdeskautomationhistory']['mandant'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'select',
            'foreignKey' => 'tl_alpdeskcore_mandant.mandant',
            'eval' => array('mandatory' => true, 'tl_class' => 'w50'),
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'data' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_alpdeskautomationhistory']['data'],
            'exclude' => true,
            'search' => false,
            'inputType' => 'textarea',
            'eval' => array('mandatory' => false, 'tl_class' => 'clr'),
            'sql' => "mediumtext NULL"
        )
    )
);

class tl_alpdeskautomationhistory extends Backend {

  public function showLabels($row, $label, DataContainer $dc, $args) {
    $args[0] = date('d.m.Y H:i:s', $args[0]);
    return $args;
  }

}
