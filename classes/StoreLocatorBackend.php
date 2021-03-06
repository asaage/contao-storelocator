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
 * Namespace
 */
namespace numero2\StoreLocator;


class StoreLocatorBackend extends \System {


    /**
     * Show a message in backend if google keys are missing
     *
     * @param DataContainer $dc
     *
     * @return none
     */
    public function showGoogleKeysMissingMessage( $dc ) {

        if( TL_MODE != 'BE' )
            return;

        if( \Input::get('table') == "tl_module" && \Input::get('act') == "edit" ) {

            $objModule = \Database::getInstance()->prepare("
                SELECT * FROM tl_module WHERE id = ?
                ")->execute( $dc->id );

            if( !array_key_exists($objModule->type, $GLOBALS['FE_MOD']['storelocator']) ){
                return;
            }
        }

        self::loadLanguageFile('tl_settings');

        if( empty(\Config::get('google_maps_server_key')) ) {
            \Message::addInfo(
                sprintf($GLOBALS['TL_LANG']['tl_settings']['err']['missing_key'],
                    $GLOBALS['TL_LANG']['tl_settings']['google_maps_server_key'][0]
                )
            );
        }

        if( empty(\Config::get('google_maps_browser_key')) ) {
            \Message::addInfo(
                sprintf($GLOBALS['TL_LANG']['tl_settings']['err']['missing_key'],
                    $GLOBALS['TL_LANG']['tl_settings']['google_maps_browser_key'][0]
                )
            );
        }
    }


    /**
	 * Fills coordinates if not already set and saving
	 *
	 * @param $dc  current element ether a DC_Table or a DataContainer
	 *
	 * @return none
	 */
	public function fillCoordinates( $dc ) {

        $aResults = array();

        if( \Input::get('key') == "fillCoordinates" ) {

            ini_set('max_execution_time', 0);

            $results = \Database::getInstance()->prepare("
                SELECT *
                FROM tl_storelocator_stores
                WHERE pid = ?
                    AND (longitude = '' OR latitude = '')
                ")->execute($dc->id);

            $aResults = $results->fetchAllAssoc();

        }

        // creates array with data from activeRecord
		if( $dc->activeRecord ) {

            if( empty($dc->activeRecord->longitude) || empty($dc->activeRecord->latitude) ) {

                $aResults= array(
                    array(
                            "id" => $dc->id
                        ,   "street" => $dc->activeRecord->street
                        ,   "postal" => $dc->activeRecord->postal
                        ,   "city" => $dc->activeRecord->city
                        ,   "country" => $dc->activeRecord->country
                    )
                );
            }
        }

        if( !empty($aResults) ) {

            foreach( $aResults as $key => $value ) {

                $oSL = NULL;
                $oSL = new StoreLocator();

                // find coordinates using google maps api
                $coords = $oSL->getCoordinates(
                    $value['street']
                    ,	$value['postal']
                    ,	$value['city']
                    ,	$value['country']
                );

                if( !empty($coords) ) {
                    \Database::getInstance()->prepare("UPDATE tl_storelocator_stores %s WHERE id=?")->set($coords)->execute($value['id']);
                }
            }
        }

        if( \Input::get('key') == "fillCoordinates" ) {
            $this->redirect($this->getReferer());
        }

		return;
	}


    /**
     * Returns a list of weekdays
     *
     * @return array
     */
    public static function getMapInteractions() {

        return array(
            'nothing'               => $GLOBALS['TL_LANG']['tl_storelocator']['interactions']['nothing']
        ,   'showMarkerInfo'        => $GLOBALS['TL_LANG']['tl_storelocator']['interactions']['showMarkerInfo']
        ,   'scrollToListElement'   => $GLOBALS['TL_LANG']['tl_storelocator']['interactions']['scrollToListElement']
        );
    }


    /**
     * Returns a list of weekdays
     *
     * @return array
     */
    public static function getListInteractions() {

        return array(
            'nothing'                       => $GLOBALS['TL_LANG']['tl_storelocator']['interactions']['nothing']
        ,   'scrollToMapAndCenterMarker'    => $GLOBALS['TL_LANG']['tl_storelocator']['interactions']['scrollToMapAndCenterMarker']
        );
    }
}
