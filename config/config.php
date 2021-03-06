<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2019 Leo Feyer
 *
 * @package   StoreLocator
 * @author    Benny Born <benny.born@numero2.de>
 * @author    Michael Bösherz <michael.boesherz@numero2.de>
 * @license   LGPL
 * @copyright 2019 numero2 - Agentur für digitales Marketing
 */


/**
 * MODELS
 */
$GLOBALS['TL_MODELS'][\numero2\StoreLocator\StoresModel::getTable()] = 'numero2\StoreLocator\StoresModel';
$GLOBALS['TL_MODELS'][\numero2\StoreLocator\CategoriesModel::getTable()] = 'numero2\StoreLocator\CategoriesModel';


/**
 * BACK END MODULES
 */
$GLOBALS['BE_MOD']['content']['storelocator'] = array(
    'tables'            => array('tl_storelocator_categories', 'tl_storelocator_stores')
,   'stylesheet'        => 'system/modules/storelocator/assets/backend.css'
,   'importStores'      => array( '\numero2\StoreLocator\ModuleStoreLocatorImporter', 'showImport' )
,   'fillCoordinates'   => array( '\numero2\StoreLocator\StoreLocatorBackend', 'fillCoordinates' )
);

// Add backend.css to modules
$GLOBALS['BE_MOD']['design']['themes']['stylesheet'] = (array)$GLOBALS['BE_MOD']['design']['themes']['stylesheet'];
$GLOBALS['BE_MOD']['design']['themes']['stylesheet'][] = 'system/modules/storelocator/assets/backend.css';


/**
 * BACK END FORM FIELDS
 */
$GLOBALS['BE_FFL']['openingTimes'] = '\numero2\StoreLocator\OpeningTimes';


/**
 * FRONT END MODULES
 */
$GLOBALS['FE_MOD']['storelocator'] = array(
    'storelocator_search'       => '\numero2\StoreLocator\ModuleStoreLocatorSearch'
,   'storelocator_list'         => '\numero2\StoreLocator\ModuleStoreLocatorList'
,   'storelocator_filter'       => '\numero2\StoreLocator\ModuleStoreLocatorFilter'
,   'storelocator_details'      => '\numero2\StoreLocator\ModuleStoreLocatorDetails'
,   'storelocator_static_map'   => '\numero2\StoreLocator\ModuleStoreLocatorStaticMap'
);


/**
 * REGISTER HOOKS
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('\numero2\StoreLocator\StoreLocator', 'replaceInsertTags');
