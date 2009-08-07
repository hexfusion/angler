<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetField
 *
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
 */

// $Id: SugarWidgetReportField.php,v 1.25 2006/08/17 22:24:05 wayne Exp $

require_once('include/generic/SugarWidgets/SugarWidgetField.php');

$used_aliases = array();
$alias_map = array();;

class SugarWidgetReportField extends SugarWidgetField
{
	function  getSubClass($layout_def)
	{
		if (! empty($layout_def['type']))
		{
			$layout_def['widget_class'] = 'Field'.$layout_def['type'];
			return $this->layout_manager->getClassFromWidgetDef($layout_def);
		} else {
			return $this;
		}
	}


 function display($layout_def)
 {

        $obj = $this->getSubClass($layout_def);
                                                                                         
        $context = $this->layout_manager->getAttribute('context');//_ppd($context);
        $func_name = 'display'.$context;

                                                                                         
        if ( ! empty($context) && method_exists($obj,$func_name))
        {
                return  $obj->$func_name($layout_def);
        } else
        {
                return 'display not found:'.$func_name;
        }
 }


  
 function _get_column_select_special($layout_def)
 {
 		$alias = '';
		 if ( ! empty($layout_def['table_alias']))
		 {
			$alias = $layout_def['table_alias'];
		 }
		 
		$reporter = $this->layout_manager->getAttribute("reporter");

    if ($layout_def['name'] == 'weighted_sum' ) 
    {






				return "SUM( ".$alias.".probability * ".$alias.".amount_usdollar * 0.01) ";



		}
    if ($layout_def['name'] == 'weighted_amount' ) 
    {






				return "AVG(".$alias.".probability * ".$alias.".amount_usdollar * 0.01) ";



		}
 }

 function _get_column_select($layout_def)
 {
		$alias = '';
		$endalias = '';

	if ( ! empty($layout_def['group_function']) )
	{
    	if ($layout_def['name'] == 'weighted_sum' || $layout_def['name'] == 'weighted_amount') 
    	{
				return $this->_get_column_select_special($layout_def);
    	}
		
			$reporter = $this->layout_manager->getAttribute('reporter');







				$alias .= $layout_def['group_function']."(";
				$endalias = ')';



	}
	if ( ! empty($layout_def['table_alias']))
	{
		$alias .= $layout_def['table_alias'].".".$layout_def['name'];
	
	}else if (! empty($layout_def['name'])) {
		$alias = $layout_def['name'];
	} else {
		$alias .= "*";
	}
	$alias .= $endalias;
	
	return $alias;
 }

 function querySelect(&$layout_def)
 {
			return $this->_get_column_select($layout_def)." ".$this->_get_column_alias($layout_def)."\n";
 }

 function queryGroupBy($layout_def)
 {
	return $this->_get_column_select($layout_def)." \n";
 }


 function queryOrderBy($layout_def)
 {
	$reporter = $this->layout_manager->getAttribute('reporter');
	if(!empty($reporter->all_fields[$layout_def['column_key']])) $field_def = $reporter->all_fields[$layout_def['column_key']];
                                                                                                       
	if ( ! empty( $field_def['sort_on']))
	{
			$order_by = $layout_def['table_alias'].".".$field_def['sort_on'];
            if(!empty($field_def['sort_on2']))
                $order_by .= ', ' . $layout_def['table_alias'].".".$field_def['sort_on2'];
    }
	else {
		$order_by = $this->_get_column_alias($layout_def)." \n";
	}
			
			if ( empty($layout_def['sort_dir']) || $layout_def['sort_dir'] == 'a')
			{
				return $order_by." ASC";
			} else {
				return $order_by." DESC";
			}
 }


 function queryFilter($layout_def)
 {
	$method_name = "queryFilter".$layout_def['qualifier_name'];
	return $this->$method_name($layout_def);
 }

	function & displayHeaderCell(&$layout_def)
	{
	global $start_link_wrapper,$end_link_wrapper;
                require_once("include/ListView/ListView.php");

                // don't show sort links if name isn't defined
                $no_sort = $this->layout_manager->getAttribute('no_sort');
                if(empty($layout_def['name']) || ! empty($no_sort) || ! empty($layout_def['no_sort']))
                {
                        return $layout_def['label'];
                }
                                                                                       
                                                                                       
                                                                                       
                $sort_by ='';
                if ( ! empty($layout_def['table_key']) && ! empty($layout_def['name']) )
                {
                  if (! empty($layout_def['group_function']) && $layout_def['group_function'] == 'count')
                  {
                    $sort_by = 'count'; 
                  } else {
                        	$sort_by = $layout_def['table_key'].":".$layout_def['name'];
                          if ( ! empty($layout_def['column_function']))
                          {
                            $sort_by .= ':'.$layout_def['column_function'];
                          } else if ( ! empty($layout_def['group_function']) )
                        	{
                             $sort_by .= ':'.$layout_def['group_function'];
                        	} 
                  }
                }
                else
                {
                        return $this->displayHeaderCellPlain($layout_def);
                }
                                                                                       
                $start = $start_link_wrapper;
                $end = $end_link_wrapper;
                                                                                       
                $start = empty($start) ? '': $start;
                $end = empty($end) ? '': $end;
                if($layout_def['name'] != 'description') {
                    $header_cell = "<a class=\"listViewThLinkS1\" href=\"".$start.$sort_by.$end."\">";
                    $header_cell .= $this->displayHeaderCellPlain($layout_def);
    
                    $arrow_start = ListView::getArrowStart($this->layout_manager->getAttribute('image_path'));
                    $arrow_end = ListView::getArrowEnd($this->layout_manager->getAttribute('image_path'));
                                                                                           
                                                                                           
                    $imgArrow = '';
                                                                                           
                    if (isset($layout_def['sort']))
                    {
                            $imgArrow = $layout_def['sort'];
                    }
                    $header_cell .= ' ' . $arrow_start.$imgArrow.$arrow_end."</a>";
                }
                else {
                    $header_cell = $this->displayHeaderCellPlain($layout_def);
                }
                                                                                       
                return $header_cell;
        }
                                                                                         
	function query($layout_def)
 	{
       		 $obj = $this->getSubClass($layout_def);
       	                                                                                  
        	$context = $this->layout_manager->getAttribute('context');
       	 	$func_name = 'query'.$context;
                                                                                         
        	if ( ! empty($context) && method_exists($obj,$func_name))
       		 {
               		 return  $obj->$func_name($layout_def);
        	} else
        	{
                	return '';
		}
 	}
                                                                                         
 function _get_column_alias($layout_def)
 {
        $alias_arr = array();
                                                                                         
				if ($layout_def['table_key'] == 'self' && $layout_def['name'] == 'id')
				{
					return 'primaryid';	
				}

        if ( ! empty($layout_def['group_function']) && $layout_def['group_function']=='count')
        {
                return 'count';
        }
                                                                                         
        if ( ! empty($layout_def['table_alias']))
        {
                array_push($alias_arr,$layout_def['table_alias']);
        }

        if ( ! empty($layout_def['group_function']) && $layout_def['group_function'] != 'weighted_amount' && $layout_def['group_function'] != 'weighted_sum')
        {
                array_push($alias_arr,$layout_def['group_function']);
        } else if ( ! empty($layout_def['column_function']))
        {
                array_push($alias_arr,$layout_def['column_function']);
        } else if ( ! empty($layout_def['qualifier']))
        {
                array_push($alias_arr,$layout_def['qualifier']);
        }
                                                                                         
        if ( ! empty($layout_def['name']))
        {
                array_push($alias_arr,$layout_def['name']);
        }
                                                                                         
				global $used_aliases,$alias_map;

        $alias = strtolower(implode("_",$alias_arr));
				$short_alias = substr($alias,0,28);

				if ( empty($used_aliases[$short_alias]))
				{
					$alias_map[$alias] = $short_alias;
				  $used_aliases[$short_alias] = 1;
          return $short_alias;
				} else if ( ! empty($alias_map[$alias]) )
				{
					return $alias_map[$alias];
				} else {
					$alias_map[$alias] = $short_alias.'_'.$used_aliases[$short_alias];
				  $used_aliases[$short_alias]++;
					return $alias_map[$alias];
				}
 }

 function queryFilterEmpty(&$layout_def)
 {
    return '( '.$this->_get_column_select($layout_def).' IS NULL OR '.$this->_get_column_select($layout_def)."='' )\n";
 }

 function queryFilterIs(&$layout_def)
 {
 	return '( '.$this->_get_column_select($layout_def)."='".PearDatabase::quote($layout_def['input_name0'])."')\n";
 }

 function queryFilterNot_Empty(&$layout_def)
 {
    return '( '.$this->_get_column_select($layout_def).' IS NOT NULL AND '.$this->_get_column_select($layout_def)."<>'' )\n";
 }

}
?>
