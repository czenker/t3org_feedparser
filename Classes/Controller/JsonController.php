<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2011 Christian Zenker <christian.zenker@599media.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * The main controller to show items from a json stream
 *
 * @author Christian Zenker <christian.zenker@599media.de>
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_T3orgFeedparser_Controller_JsonController extends Tx_Extbase_MVC_Controller_ActionController {

    /**
     * Show teasers from a json feed
     *
     * assigned to template:
     * =====================
     *  * feed - Tx_T3orgT3blogrefviewer_Domain_Model_LazyJson - a representation of the feed
     *  * feedUrl - the feedUrl that should be fetched
     *  * error - contains an error message if something went wrong when trying to create the feed object
     *  
     * @return string
     */
    public function teaserAction() {
    	try {
	    	if(!$this->settings['feedUrl']) {
	    		throw new InvalidArgumentException('feedUrl is not configured.');
	    	}
	    	
	    	$feedUrl = $this->settings['feedUrl'];
	    	
	    	$cacheTime = intval($this->settings['cacheTime']);
	    	
	    	if(!empty($this->settings['templatePathAndName'])) {
	    		$this->view->setTemplatePathAndFilename(t3lib_div::getFileAbsFileName($this->settings['templatePathAndName']));
	    	}
    		
	    	/**
	    	 * some lazy fetching feed
	    	 * 
	    	 * it just does its time-consuming work when it is actually needed
	    	 * 
	    	 * @var Tx_T3orgFeedparser_Domain_Model_LazyJson
	    	 */
	    	$feed = new Tx_T3orgFeedparser_Domain_Model_LazyJson();
	    	$feed->setFeedUrl($feedUrl);
	    	$feed->setCacheTime($cacheTime);
	    	
    		$this->view->assign('feed', $feed);
	    	$this->view->assign('feedUrl', $feedUrl);
	    	$this->view->assign('cacheTime', $cacheTime);
	    	
	    	// call explicitly to fetch exceptions thrown by Domain_Model_LazyJson
	    	return $this->view->render();
    		
    	} catch (Exception $e) {
    		t3lib_div::sysLog($e->getMessage(), 't3org_feedparser', LOG_ERR);
    		$this->view->assign('error', $e->getMessage());
    	}
    }
	
}

?>