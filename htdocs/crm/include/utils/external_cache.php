<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/

/**
 * Internal -- Has the external cache been checked to determine if it is available and configured.
 */
$external_cache_checked = false;

/**
 * Internal -- Is the external cache available.  This setting is determined by checking for the availability
 * of the external cache functions.  It can be overridden by adding a config variable 
 * (external_cache_disabled=true).
 */
$external_cache_enabled = false;

/**
 * Internal -- This is controlled by a config setting (external_cache_reset) that will update the cache
 * with new values, but not read from the cache.
 */
$external_cache_overwrite = false;

/**
 * Internal -- The data structure for the local cache.
 */
static $cache_local_store = array();

/**
 * The interval in seconds that an external cache entry is valid.
 */
define('EXTERNAL_CACHE_INTERVAL_SECONDS', 3000 );

/**
 * Internal -- Determine if there is an external cache available for use.  
 * Currently only Zend Platform is supported.
 */
function check_cache()
{
    global $external_cache_checked, $external_cache_enabled, $sugar_config;
    if($external_cache_checked == false)
    {
        if(function_exists("output_cache_get"))
        {
            $external_cache_enabled = true;
            if(!empty($sugar_config['external_cache_disabled']) && true == $sugar_config['external_cache_disabled'])
            {
                $external_cache_enabled = false;
            }
            else
            {
                // make sure the cache is not being reset.
                $value = output_cache_get($GLOBALS['sugar_config']['unique_key'].'EXTERNAL_CACHE_RESET', EXTERNAL_CACHE_INTERVAL_SECONDS);
                if(!empty($value))
                {
                    // We are in a cache reset, do not use the cache.
                    $external_cache_enabled = false;        
                }            
            }
        }
    
        $external_cache_checked = true;
    }
}

/**
 * Retrieve a key from cache.  For the Zend Platform, a maximum age of 5 minutes is assumed.
 *
 * @param String $key -- The item to retrieve.
 * @return The item unserialized
 */
function sugar_cache_retrieve($key)
{
    global $external_cache_checked, $external_cache_enabled, $cache_local_store, $sugar_config;

    if(!$external_cache_checked)
    {
        check_cache();
    }

    // If we are currently resetting the cache, do not return any value.  Inserts should still occur.
    if(!empty($sugar_config['external_cache_reset']) && true == $sugar_config['external_cache_reset'])
    {
        // Remove any existing value:
        if($external_cache_enabled)
        {
            sugar_cache_clear($key);
        }
        
        return null;
    }
    
    if(!empty($cache_local_store[$key]))
    {
        return $cache_local_store[$key];
    }
    
    if(!$external_cache_enabled)
    {   
        return null;
    }

	// If it is not in memory, but is in cache, copy it to memory and use it
    $value = output_cache_get($GLOBALS['sugar_config']['unique_key'].$key, EXTERNAL_CACHE_INTERVAL_SECONDS);
    if(!empty($value))
    {
        $cache_local_store[$key] = $value;        
    }            

    return $value;
}

/**
 * Put a value in the cache under a key
 *
 * @param String $key -- Global namespace cache.  Key for the data.
 * @param Serializable $value -- The value to store in the cache.
 */
function sugar_cache_put($key, $value)
{
    global $external_cache_checked, $external_cache_enabled, $cache_local_store;
    if(!$external_cache_checked)
    {
        check_cache();
    }

    $cache_local_store[$key] = $value;
    
    if($external_cache_enabled)
    {   
	   output_cache_put($GLOBALS['sugar_config']['unique_key'].$key, $value);
    }
}

/**
 * Clear a key from the cache.  This is used to invalidate a single key.
 *
 * @param String $key -- Key from global namespace
 */
function sugar_cache_clear($key)
{
    global $external_cache_enabled, $cache_local_store;

    unset($cache_local_store[$key]);

    if($external_cache_enabled)
    {
        output_cache_remove_key($GLOBALS['sugar_config']['unique_key'].$key);
    }
}

/**
 * Turn off external caching for the rest of this round trip and for all round 
 * trips for the next cache timeout.  This function should be called when global arrays
 * are affected (studio, module loader, upgrade wizard, ... ) and it is not ok to 
 * wait for the cache to expire in order to see the change.
 */
function sugar_cache_reset()
{
    global $external_cache_enabled, $cache_local_store;

    // Set a flag to clear the code.
    sugar_cache_put('EXTERNAL_CACHE_RESET', true);
    
    // Clear the local cache
    $cache_local_store = array();
    
    // Disable the external cache for the rest of the round trip
    $external_cache_enabled = false;
}
