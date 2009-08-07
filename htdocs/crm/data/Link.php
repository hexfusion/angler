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
/*********************************************************************************
* $Id: Link.php,v 1.45 2006/06/21 17:49:03 ajay Exp $
* Description:  Defines the base class for new data type, Relationship, methods in the class will
* be used to manipulate relationship between object instances.
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/

require_once('modules/Relationships/Relationship.php');

class Link {

	/* Private variables.*/
	var $_log;
	var $_relationship_name; //relationship this attribute is tied to.
	var $_bean; //stores a copy of the bean.
	var $_relationship= '';
	var $_bean_table_name;
	var $_bean_key_name='id';
	var $_db;
	var $_swap_sides = false;
	
	//if set to true role column will not be added to the filter criteria.
	var $ignore_role_filter=false;
	//if set to true distinct clause will be added to the select list.
	var $add_distinct=false;	
	//value of this variable dictates the action to be taken when a duplicate relationship record is found. 
	//1-ignore,2-update,3-delete.
	var $when_dup_relationship_found=2;

	// a value for duplicate variable is stored by the _relatinship_exists method.
	var $_duplicate_key;
	var $_duplicate_where;
	
	/* Parameters:
	 * 		$_rel_name: use this relationship key.
	 * 		$_bean: reference of the bean that instantiated this class.
	 * 		$_fieldDef: vardef entry for the field.
	 * 		$_table_name: optional, fetch from the bean's table name property.
	 * 		$_key_name: optional, name of the primary key column for _table_name 
	 */
	function Link($_rel_name, &$_bean, $fieldDef, $_table_name='', $_key_name=''){
		$GLOBALS['log']->debug("Link Constructor, relationship name: ".$_rel_name);
		$GLOBALS['log']->debug("Link Constructor, Table name: ".$_table_name);
		$GLOBALS['log']->debug("Link Constructor, Key name: ".$_key_name);
		//_pp(func_get_args());
		$this->_relationship_name=$_rel_name;
		$this->_bean=&$_bean;
		$this->_relationship=new Relationship();
		//$this->_relationship->retrieve_by_string_fields(array('relationship_name'=>$this->_relationship_name));	
		$this->_relationship->retrieve_by_name($this->_relationship_name);		

		$this->_db = & PearDatabase::getInstance();
		
		//Following behavior is tied to a property(ignore_role) value in the vardef. It alters the values of 2 properties, ignore_role_filter and add_distinct.
		//the property values can be altered again before any requests are made.
		if (!empty($fieldDef) && is_array($fieldDef)) {
			if (array_key_exists('ignore_role', $fieldDef)) {
				if ($fieldDef['ignore_role'] == true) {
					$this->ignore_role_filter=true;
					$this->add_distinct=true;
				}
			}
		}
		
		$this->_bean_table_name=(!empty($_table_name)) ? $_table_name : $_bean->table_name;
		if (!empty($key_name)) {
			$this->_bean_key_name=$_key_name;
		} else {
			
			if ($this->_relationship->lhs_table != $this->_relationship->rhs_table) {
				
				if ($_bean->table_name == $this->_relationship->lhs_table)
					$this->_bean_key_name=$this->_relationship->lhs_key;

				if ($_bean->table_name == $this->_relationship->rhs_table)
					$this->_bean_key_name=$this->_relationship->rhs_key;
					
			}
		}
		if ($this->_relationship->lhs_table == $this->_relationship->rhs_table && isset($fieldDef['side']) && $fieldDef['side'] == 'right') {
			$this->_swap_sides = true;
		}
		//default to id if not set.
		if (empty($this->_bean_key_name))$this->_bean_key_name='id';
			
		$GLOBALS['log']->debug("Link Constructor, _bean_table_name: ".$this->_bean_table_name);
		$GLOBALS['log']->debug("Link Constructor, _bean_key_name: ".$this->_bean_key_name);
		if (!empty($this->_relationship->id)) $GLOBALS['log']->debug("Link Constructor, relationship record found.");
		else $GLOBALS['log']->debug("Link Constructor, No relationship record.") ;
	
	}

	/* This method will return the following based on cardinality of the relationship.
	 *  one-to-many, many-to-many: empty array if not data is found else arry of keys.
	 *  many-to-one, one-to-one: null if no linked data is found, else key value.
	 * 
	 * For a self referencing relationship the function will behave as if the user is trying
	 * to access the child records. To get to the parent records use the getParent() method.
	 */
	function get() {
		$query = $this->getQuery();
		$result = $this->_db->query($query, true);
		$list = Array();
		while($row = $this->_db->fetchByAssoc($result))
		{
			$list[] = $row['id'];
		}
		return $list;		
	}

	function getRelatedTableName() {
		
		$bean_is_lhs=$this->_get_bean_position();
		if (!isset($bean_is_lhs)) {
			$GLOBALS['log']->debug("Invalid relationship parameters. Exiting..");
			return null;
		}
		
		if ($bean_is_lhs) {
			return $this->_relationship->rhs_table; 
		} else {
			return $this->_relationship->lhs_table; 
		}
	}

	function getRelatedModuleName() {

		$bean_is_lhs=$this->_get_bean_position();
		if (!isset($bean_is_lhs)) {
			$GLOBALS['log']->debug("Invalid relationship parameters. Exiting..");
			return null;
		}
		
		if ($bean_is_lhs) {
			return $this->_relationship->rhs_module; 
		} else {
			return $this->_relationship->lhs_module; 
		}
	}
	
	function _get_bean_position() {
		//current beans module and table are on the left side or the right side.
		$position = false;
		if ($this->_relationship->lhs_table == $this->_bean_table_name &&  $this->_relationship->lhs_key == $this->_bean_key_name) {
			$position =  true; 
			
		}		
		if ($this->_relationship->rhs_table == $this->_bean_table_name &&  $this->_relationship->rhs_key == $this->_bean_key_name) {
			$position =  false;
		}
		if($this->_swap_sides){
			return 	!$position;
		}
		return $position;	
	}
	
	function getJoin($params, $return_array =false)
	{
		$join_type= ' INNER JOIN ';
			if(isset($params['join_type'])){
					$join_type = $params['join_type'];
			}
		$id = -1;	
		$join = '';
		$bean_is_lhs=$this->_get_bean_position();

		if ($this->_relationship->relationship_type=='one-to-one' or $this->_relationship->relationship_type=='many-to-one' or
   			($this->_relationship->relationship_type=='one-to-many' && !$bean_is_lhs)) 
		{
			if ($bean_is_lhs) {
			    $table = $this->_relationship->rhs_table;
			    $key = $this->_relationship->rhs_key;
			    $other_table = $this->_relationship->lhs_table;
			    $other_key = $this->_relationship->lhs_key;
			} else {
			    $key = $this->_relationship->lhs_key;
			    $table = $this->_relationship->lhs_table;

				if ( ! empty($params['join_table_alias']))
				{
			    	$table_with_alias = $table. " ".$params['join_table_alias'];
			    	$table = $params['join_table_alias'];
				}
			    $other_table = $this->_relationship->rhs_table;
			    $other_key = $this->_relationship->rhs_key;
			}
			
		    $join = $join_type . ' '. $table_with_alias . " ON\n".$table.'.'.$key.'= '.$other_table.'.'.$other_key ." AND ". $table.".deleted=0\n";

		}

		if ($this->_relationship->relationship_type == 'one-to-many' && $bean_is_lhs) {

			    $table = $this->_relationship->rhs_table;
			    $key = $this->_relationship->rhs_key;
			    $other_table = $this->_relationship->lhs_table;
			    $other_key = $this->_relationship->lhs_key;
					if ( ! empty($params['join_table_alias']))
					{
			    	$table_with_alias = $table. " ".$params['join_table_alias'];
			    	$table = $params['join_table_alias'];
					}
			
			    $join = $join_type . ' '.$table_with_alias . " ON\n".$table.'.'.$key.'= '.$other_table.'.'.$other_key ." AND ". $table.".deleted=0\n";

		}

		if ($this->_relationship->relationship_type=='many-to-many' ) 
		{
			if ( ! empty($params['join_table_alias']))
			{
		   		$table_with_alias = $this->_relationship->join_table. " ".$params['join_table_alias'];
		   		$table = $params['join_table_alias'];
				$rel_table_with_alias = 
					$this->_relationship->join_table. " ".
					$params['join_table_link_alias'];
				$rel_table = $params['join_table_link_alias'];
			}
			
			if ( $bean_is_lhs )
			{
				$join .= $join_type . ' '.$rel_table_with_alias.' ON '.$this->_relationship->lhs_table.".".$this->_relationship->lhs_key."=".$rel_table.".".$this->_relationship->join_key_lhs."  AND ".$rel_table.".deleted=0\n";
			} else 
			{
				$join .= $join_type . ' '.$rel_table_with_alias.' ON '.$this->_relationship->rhs_table.".".$this->_relationship->rhs_key."=".$rel_table.".".$this->_relationship->join_key_rhs."  AND ".$rel_table.".deleted=0\n";
			}
			if (!empty($this->_relationship->relationship_role_column) && !$this->ignore_role_filter) 	
			{
				$join.=" AND ".$rel_table.'.'.$this->_relationship->relationship_role_column;
				//role column value.
				if (empty($this->_relationship->relationship_role_column_value)) 
				{
					$join.=' IS NULL';
				} else {
					$join.= "='".$this->_relationship->relationship_role_column_value."'";
				}
				$join.= "\n";
			}
			if ( ! empty($params['join_table_alias']))
			{
				if ( $bean_is_lhs )
				{
			  	$table_with_alias = $this->_relationship->rhs_table. " ".$params['join_table_alias'];
				} else {
			  	$table_with_alias = $this->_relationship->lhs_table. " ".$params['join_table_alias'];
				}
			   	$table = $params['join_table_alias'];
			}
				if ( $bean_is_lhs )
				{
			$join .= $join_type . ' '.$table_with_alias.' ON '.$table.".".$this->_relationship->lhs_key."=".$rel_table.".".$this->_relationship->join_key_rhs." AND ".$table.".deleted=0";
				} else {
			$join .= $join_type . ' '.$table_with_alias.' ON '.$table.".".$this->_relationship->rhs_key."=".$rel_table.".".$this->_relationship->join_key_lhs." AND ".$table.".deleted=0";
				}
			$join.= "\n";
		}
		if($return_array){
			$ret_arr = array();
			$ret_arr['join'] = $join;
			$ret_arr['type'] = $this->_relationship->relationship_type;
			if ( $bean_is_lhs ){
				
				$ret_arr['rel_key'] = 	$this->_relationship->join_key_rhs;
			}else{
				
				$ret_arr['rel_key'] = 	$this->_relationship->join_key_lhs;
			}
			return $ret_arr;
		}
		return $join;
	}


	function _add_deleted_clause($deleted=0,$add_and='',$prefix='') {
		
		if (!empty($prefix)) $prefix.='.';
		if (!empty($add_and)) $add_and=' '.$add_and.' ';
		
		if ($deleted==0)  return $add_and.$prefix.'deleted=0'; 
		if ($deleted==1) return $add_and.$prefix.'deleted=1';
		else return '';
	}
	
	function _add_optional_where_clause($optional_array, $add_and='',$prefix='') {
		
		if (!empty($prefix)) $prefix.='.';
		if (!empty($add_and)) $add_and=' '.$add_and.' ';
		
		if(!empty($optional_array)){
			return $add_and.$prefix."".$optional_array['lhs_field']."".$optional_array['operator']."'".$optional_array['rhs_value']."'";
		}	 
		return '';
	//end functio _add_optional_where_clause
	}	
	
	
	
	function getQuery($return_as_array=false, $sort_array = array(),$deleted=0, $optional_where="", $return_join = false) {

		$select='';
		$from='';
		$join = '';
		$where='';
		$join_tables = array();
		$bean_is_lhs=$this->_get_bean_position();
		
		if (!isset($bean_is_lhs)) {
			$GLOBALS['log']->debug("Invalid relationship parameters. Exiting..");
			return null;
		}
		
		$GLOBALS['log']->debug("getQuery, Bean is LHS: ".$bean_is_lhs);
		$GLOBALS['log']->debug("getQuery, Relationship type=".$this->_relationship->relationship_type);
		$GLOBALS['log']->debug("getQuery, Relationship role column name=".$this->_relationship->relationship_role_column);
		
		if ($this->_relationship->relationship_type=='one-to-one' or $this->_relationship->relationship_type=='many-to-one' or 
		     ($this->_relationship->relationship_type=='one-to-many' && !$bean_is_lhs)) {

			$GLOBALS['log']->debug("Processing one-to-one,many-to-one,one-to-many.");

			if ($this->add_distinct) {
				$select='SELECT DISTINCT id';
			} else {
				$select='SELECT id';			
			}
			
			if ($bean_is_lhs) {
			    $from= 'FROM '.$this->_relationship->rhs_table;
			    $where='WHERE '.$this->_relationship->rhs_table.'.'.$this->_relationship->rhs_key."= '".$this->_bean->id."'"; 
			    if (!empty($this->_relationship->relationship_role_column) && !$this->ignore_role_filter) {
			    	$where.=" AND ".$this->_relationship->rhs_table.'.'.$this->_relationship->relationship_role_column;

			    	//role column value.
			    	if (empty($this->_relationship->relationship_role_column_value)) {
			    		$where.=' IS NULL';
			    	} else {
			    		$where.= "='".$this->_relationship->relationship_role_column_value."'";
			    	}
			    }
			    
			    //added deleted clause.
			    $where.=$this->_add_deleted_clause($deleted,'AND',$this->_relationship->rhs_table );
			    

				if($optional_where!=""){
				//process optional where
					$where.=$this->_add_optional_where_clause($optional_where,'AND');
				}	 
		    
			    
			} 
			else {
			    $from= 'FROM '.$this->_relationship->lhs_table;
			    $where='WHERE '.$this->_relationship->lhs_table.'.'.$this->_relationship->lhs_key."= '".$this->_bean->{$this->_relationship->rhs_key}."'"; 
			    //added deleted clause.
			    $where.=$this->_add_deleted_clause($deleted,'AND', $this->_relationship->lhs_table);
			    

				if($optional_where!=""){
				//process optional where
					$where.=$this->_add_optional_where_clause($optional_where,'AND');
				}	 
			    
			}
		}
		
		if ($this->_relationship->relationship_type == 'one-to-many' && $bean_is_lhs) {
			$GLOBALS['log']->debug("Processing one-to-many.");
			
			if ($this->add_distinct) {
				$select='SELECT DISTINCT id';
			} else {
				$select='SELECT id';			
			}						    
			$from= 'FROM '.$this->_relationship->rhs_table;
		    $where='WHERE '.$this->_relationship->rhs_table.'.'.$this->_relationship->rhs_key."= '".$this->_bean->id."'"; 
		    if (!empty($this->_relationship->relationship_role_column) && !$this->ignore_role_filter) {
		    	$where.=" AND ".$this->_relationship->rhs_table.'.'.$this->_relationship->relationship_role_column;
		    	//role column value.
		    	if (empty($this->_relationship->relationship_role_column_value)) {
		    		$where.=' IS NULL';
		    	} else {
		    		$where.= "='".$this->_relationship->relationship_role_column_value."'";
		    	}
		    }
		    //added deleted clause.
		    $where.=$this->_add_deleted_clause($deleted,'AND',$this->_relationship->rhs_table);

			if($optional_where!=""){
			//process optional where
				$where.=$this->_add_optional_where_clause($optional_where,'AND');
			}	 
		
		}

		if ($this->_relationship->relationship_type=='many-to-many' ) {
			$GLOBALS['log']->debug("Processing many-to-many.");
			
			if ($bean_is_lhs) {
				if ($this->add_distinct) {
					$select="SELECT DISTINCT ".$this->_relationship->rhs_table.".id";			
				} else {
					$select="SELECT ".$this->_relationship->rhs_table.".id";			
				}				
				
			    $from= 'FROM '.$this->_relationship->rhs_table;
			    $subjoin=' INNER JOIN '.$this->_relationship->join_table.' ON ('.$this->_relationship->rhs_table.".".$this->_relationship->rhs_key."=".$this->_relationship->join_table.".".$this->_relationship->join_key_rhs." AND ".$this->_relationship->join_table.".".$this->_relationship->join_key_lhs."='".$this->_bean->id."'";
				$join_tables[] = $this->_relationship->join_table;
			    if (!empty($this->_relationship->relationship_role_column) && !$this->ignore_role_filter) {
			    	$subjoin.=" AND ".$this->_relationship->join_table.'.'.$this->_relationship->relationship_role_column;

			    	//role column value.
			    	if (empty($this->_relationship->relationship_role_column_value)) {
			    		$subjoin.=' IS NULL';
			    	} else {
			    		$subjoin.= "='".$this->_relationship->relationship_role_column_value."'";
			    	}
			    }
			    $subjoin.=')';
			   $join .= $subjoin;
			    $from .= $subjoin;
			  
				
				//add deleted clause.
				if ($deleted == 0 or $deleted==1) {
			    	$where.=' WHERE '.$this->_add_deleted_clause($deleted,'',$this->_relationship->join_table).$this->_add_deleted_clause($deleted,'AND',$this->_relationship->rhs_table);
				}
				
	
				if($optional_where!=""){
				//process optional where
					$where.=$this->_add_optional_where_clause($optional_where,'AND', $this->_relationship->rhs_table);
				}	 
				
				
			} 
			else {
				if ($this->add_distinct) {
					$select="SELECT DISTINCT ".$this->_relationship->lhs_table.".id";			
				} else {
					$select="SELECT ".$this->_relationship->lhs_table.".id";			
				}				
				
			    $from= 'FROM '.$this->_relationship->lhs_table;
			    $subjoin=' INNER JOIN '.$this->_relationship->join_table.' ON ('.$this->_relationship->lhs_table.".".$this->_relationship->lhs_key."=".$this->_relationship->join_table.".".$this->_relationship->join_key_lhs." AND ".$this->_relationship->join_table.".".$this->_relationship->join_key_rhs."='".$this->_bean->id."'";
			    $join_tables[] = $this->_relationship->join_table;
			    if (!empty($this->_relationship->relationship_role_column) && !$this->ignore_role_filter) {
			    	$subjoin.=" AND ".$this->_relationship->relationship_role_column;

			    	//role column value.
			    	if (empty($this->_relationship->relationship_role_column_value)) {
			    		$subjoin.=' IS NULL';
			    	} else {
			    		$subjoin.= "='".$this->_relationship->relationship_role_column_value."'";
			    	}
			    }
				$subjoin.=')';
				$join .= $subjoin;
			    $from .= $subjoin;
				//add deleted clause.
				if ($deleted == 0 or $deleted==1) {
			    	$where.=' WHERE '.$this->_add_deleted_clause($deleted,'',$this->_relationship->join_table).$this->_add_deleted_clause($deleted,'AND',$this->_relationship->lhs_table);
				}
				

				if($optional_where!=""){
				//process optional where
					$where.=$this->_add_optional_where_clause($optional_where,'AND', $this->_relationship->lhs_table);
				}	 

			}
		}
		if ($return_as_array) {
			$query_as_array['select']=$select;
			$query_as_array['from']=$from;
			$query_as_array['where']=$where;
			if($return_join){
					$query_as_array['join'] = $join;
					$query_as_array['join_tables'] = $join_tables;		
			}
			return $query_as_array;
		}
		else {
			$query = $select.' '.$from.' '.$where;
			$GLOBALS['log']->debug("Link Query=".$query);
			return $query;
		}
	}

	function getBeans($template, $sort_array = array(), $begin_index = 0, $end_index = -1, $deleted=0, $optional_where="") {
		$query = $this->getQuery(false,array(), $deleted, $optional_where); //get array of IDs
		return $this->_bean->build_related_list($query, $template);
	}

	function _add_one_to_many_table_based($key,$bean_is_lhs) {
		
		if ($bean_is_lhs) {
			$set_key_value=$this->_bean->id;
			$where_key_value=$key;
		}
		else {
			$set_key_value=$key;
			$where_key_value=$this->_bean->id;		
		}
		
		$query= 'UPDATE '.$this->_relationship->rhs_table;
		$query.=' SET '.$this->_relationship->rhs_table.'.'.$this->_relationship->rhs_key."='".$set_key_value."'";
		
		//add role column to the query.
		if (!empty($this->_relationship->relationship_role_column)) {
			$query.=' ,'.$this->_relationship->relationship_role_column."='".$this->_relationship->relationship_role_column_value."'";
		}
		$query.=' WHERE '.$this->_relationship->rhs_table.".id='".$where_key_value."'";

		$GLOBALS['log']->debug("Relationship Query ".$query);
		
		$result=$this->_db->query($query, true);
	}
	
	/* handles many to one*/
	function _add_many_to_one_bean_based($key) {
		
		//make a copy of this bean to avoid recursion.
		$bean=new $this->_bean->object_name;
		$bean->retrieve($this->_bean->id);
				
	   	$bean->{$this->_relationship->lhs_key}=$key; 	
	   	
	   	//set relationship role.
	   	if (!empty($this->_relationship->relationship_role_column)) {
	   		$bean->{$this->_relationship->relationship_role_column}=$this->_relationship->relationship_role_column_value; 			    		
	   	}
	   	$bean->save();	
	}

	
	/* use this function to create link between 2 objects
	 * 1:1 will be treated like 1 to many.
	 * todo handle self referencing relationships
	 * the function also allows for setting of values for additional field in the table being
	 * updated to save the relationship, in case of many-to-many relationships this would be the join table. 
	 * the values should be passed as key value pairs with column name as the key name and column value as key value.
	 */
	function add($rel_keys,$additional_values=array()) {

		if (!isset($rel_keys) or empty($rel_keys)) {
			$GLOBALS['log']->debug("Link.add, Null key passed, no-op, returning... ");
			return;
		}
		if (!is_array($rel_keys)) {
			$keys[]=$rel_keys;
		} else {
			$keys=$rel_keys;
		}
	
		$bean_is_lhs=$this->_get_bean_position();
		if (!isset($bean_is_lhs)) {
			$GLOBALS['log']->debug("Invalid relationship parameters. Exiting..");
			return null;
		}		

		//if multiple keys are passed then check for unsupported relationship types.
		if (count($keys) > 1) {
			if (($this->_relationship->relationship_type == 'one-to-one') 
				or ($this->_relationship->relationship_type == 'one-to-many' and !$bean_is_lhs)
				or ($this->_relationship->relationship_type == 'many-to-one')) {
					$GLOBALS['log']->error("Invalid parameters passed to function, the relationship does not support addition of multiple records.");
					return;
				}
		}

	    foreach($keys as $key) {
			
			//fetch the related record using the key and update.
			if ($this->_relationship->relationship_type=='one-to-one' or $this->_relationship->relationship_type == 'one-to-many') {			
				$this->_add_one_to_many_table_based($key,$bean_is_lhs);
			}
			
			//updates the bean passed to the instance....
			//todo remove this case.
			if ($this->_relationship->relationship_type=='many-to-one') {
				$this->_add_many_to_one_bean_based($key);				
		    }
		    
		    //insert record in the link table. 
			if ($this->_relationship->relationship_type=='many-to-many' ) {
				
				//add keys from the 2 tables to the additional keys array..
				if ($bean_is_lhs) {
					$additional_values[$this->_relationship->join_key_lhs]=$this->_bean->id;
					$additional_values[$this->_relationship->join_key_rhs]=$key;
				} else {
					$additional_values[$this->_relationship->join_key_rhs]=$this->_bean->id;
					$additional_values[$this->_relationship->join_key_lhs]=$key;									
				}
				//add the role condition.
				if (!empty($this->_relationship->relationship_role_column) && !empty($this->_relationship->relationship_role_column_value)) {
					$additional_values[$this->_relationship->relationship_role_column]=$this->_relationship->relationship_role_column_value;
				}
				//add deleted condition.
				$additional_values['deleted']=0;

				$this->_add_many_to_many($additional_values);
				
				//reverse will be set to true only for self-referencing relationships.
				if (!empty($this->_relationship->reverse) && $this->_relationship->reverse == true){
					//swap key values;
					$temp=$additional_values[$this->_relationship->join_key_lhs];
					$additional_values[$this->_relationship->join_key_lhs]=$additional_values[$this->_relationship->join_key_rhs];
					$additional_values[$this->_relationship->join_key_rhs]=$temp;

					$this->_add_many_to_many($additional_values);
				}
			} 
		}			
	}
	
	function _add_many_to_many($add_values) {

		//add date modified.
		$add_values['date_modified']=  gmdate("Y-m-d H:i:s", time());

		//check whether duplicate exist or not.
		if ($this->relationship_exists($this->_relationship->join_table,$add_values)) {
		
			switch($this->when_dup_relationship_found) {
				
				case 1: //do nothing.
					$GLOBALS['log']->debug("Executing default option, no action.");
					break;

				case 3: //delete the record first, then create a new entry.
					$this->_delete_row($this->_relationship->join_table,$this->_duplicate_key);
					$this->_insert_row($add_values);					
					break;

				default:
				case 2: //update the record.
					$this->_update_row($add_values,$this->_relationship->join_table,$this->_duplicate_where);
					break;
			}
		
		} else {
			$this->_insert_row($add_values);
		}
	}
	
	function _delete_row($table_name,$key) {
		$query='UPDATE '.$table_name." SET deleted=1, date_modified=" .gmdate("Y-m-d H:i:s", time())."' WHERE id='".$key."'";
		$GLOBALS['log']->debug("Relationship Delete Statement :".$query);

		$result=$this->_db->query($query, true);		
	}
	
	function _update_row(&$value_array,$table_name,$where) {

		$query='UPDATE '.$table_name.' SET ';
		$delimiter='';
		foreach ($value_array as $key=>$value) {
			$query.=$delimiter.$key."='".$value."' ";
			$delimiter=",";
		}
		$query.=$where;
		$GLOBALS['log']->debug("Relationship Update Statement :".$query);

		$result=$this->_db->query($query, true);		
	}
	
	function _insert_row(&$value_array) {
		//add key column
		$value_array['id']= create_guid();
		
		$columns_list='';
		$values_list='';
		$delimiter='';
		foreach ($value_array as $key=>$value) {
			$columns_list.=$delimiter.$key;
			$values_list .=$delimiter."'".$value."'";
			$delimiter=",";
		}
		$insert_string='INSERT into '.$this->_relationship->join_table.' ('.$columns_list.') VALUES ('.$values_list.')';
		$GLOBALS['log']->debug("Relationship Insert String :".$insert_string);
		
		$result=$this->_db->query($insert_string, true);
	}
	
	
	
	/* this method operates on all related record, takes action based on cardinality of the relationship.
	 * one-to-one, one-to-many: update the rhs table's parent id with null
	 * many-to-one: update the lhs table's parent-id with null.
	 * many-to-many: delete rows from the link table. related table must have delted and date_modified column.
	 * if related_is is null, the methods assumes that the parent bean (whose id is passed) is being deleted.
	 * if both id and related_id are passed the metod unlinks a single relationship.
	 * parameters: id of the bean being deleted.
	 * 	
	 */
	function delete($id,$related_id='') {
		$GLOBALS['log']->debug(sprintf("delete called with these parameter values. id=%s, related_id=%s",$id,$related_id));
		
		$_relationship=&$this->_relationship;
		$_bean=&$this->_bean;
		
		$bean_is_lhs=$this->_get_bean_position();
		if (!isset($bean_is_lhs)) {
			$GLOBALS['log']->debug("Invalid relationship parameters. Exiting..");
			return null;
		}		
	    if ($_relationship->relationship_type=='one-to-many' or $_relationship->relationship_type=='one-to-one' ) {
    		if ($bean_is_lhs) {
    			//update rhs_table set rhs_key = null, relation_column_name = null where rhs_key= this_bean_id
    			$query='UPDATE '.$_relationship->rhs_table.' SET '.$_relationship->rhs_key."=NULL, date_modified='".gmdate("Y-m-d H:i:s", time())."'"; 

    			if (!empty($_relationship->relationship_role_column) && !empty($_relationship->relationship_role_column_value)) {
    				$query.=','.$_relationship->relationship_role_column."= NULL ";
    				$query.=' WHERE '.$_relationship->relationship_role_column."= '".$_relationship->relationship_role_column_value."' AND ";
    			} else {
    				$query.=' WHERE ';
    			}
    			$query.=$_relationship->rhs_key."= '".$id."' ";
    			
    			//restrict to one row if related_id is passed.
    			if (!empty($related_id)) {
    				$query.=" AND ".$_relationship->rhs_table.".id='".$related_id."'";
    			}
    			
    		}
    		else {	
    			//do nothing because the row that stores the relationship keys is being deleted.
    			//todo log an error message here.
    			//if this is the case and related_id is passed then log a message asking the user
    			//to clear the relationship using the bean.
    		}
	    }	
	
		if ($_relationship->relationship_type=='many-to-one') {
    		//do nothing because the row that stores the relationship keys is being deleted.
			//todo log an error message here.    		
   			//if this is the case and related_id is passed then log a message asking the user
   			//to clear the relationship using the bean.
		}
	    
		if ($_relationship->relationship_type=='many-to-many' ) {
    		$query='UPDATE '.$_relationship->join_table." SET deleted=1, date_modified='".gmdate("Y-m-d H:i:s", time())."'";
    		if ($bean_is_lhs) {
    			if (!empty($this->_relationship->reverse) && ($this->_relationship->reverse == true or $this->_relationship->reverse == 1)){
    				if (empty($related_id)) {
    					$query.=" WHERE (".$_relationship->join_key_lhs."= '". $id ."' or ".$_relationship->join_key_rhs."='". $id ."')" ;
    				} else {
    					$query.=" WHERE (".$_relationship->join_key_lhs."= '". $id ."' AND ".$_relationship->join_key_rhs."='".$related_id."') OR (".$_relationship->join_key_rhs."='". $id ."' AND ".$_relationship->join_key_lhs."='".$related_id."')";   				
    				}
    			} else {
    				if (empty($related_id)) {
    					$query.=" WHERE ".$_relationship->join_key_lhs."= '". $id ."'";
    				} else {
    					$query.=" WHERE ".$_relationship->join_key_lhs."= '". $id ."' AND ".$_relationship->join_key_rhs."= '". $related_id."'";
    				}
    			}
    		} else {
                if (!empty($this->_relationship->reverse) && ($this->_relationship->reverse == true or $this->_relationship->reverse == 1)) {
                    if (empty($related_id)) {
                        $query.=" WHERE (".$_relationship->join_key_rhs."= '". $id ."' or ".$_relationship->join_key_lhs."='". $id ."')" ;
                    } else {
                        $query.=" WHERE (".$_relationship->join_key_rhs."= '". $id ."' AND ".$_relationship->join_key_lhs."='".$related_id."') OR (".$_relationship->join_key_lhs."='". $id ."' AND ".$_relationship->join_key_rhs."='".$related_id."')";                   
                    }
                } else {
                     if (empty($related_id)) {
                        $query.=" WHERE ".$_relationship->join_key_rhs."= '". $id ."'" ;
                     } else {
                        $query.=" WHERE ".$_relationship->join_key_rhs."= '". $id ."' AND ".$_relationship->join_key_lhs."= '". $related_id."'" ;
                     }              
                }
    			if (!empty($_relationship->relationship_role_column) && !empty($_relationship->relationship_role_column_value)) {
    				$query.=' AND '.$_relationship->relationship_role_column."= '".$_relationship->relationship_role_column_value."'";
    			}
    		}           
		}
		//if query string is not empty execute it.
		if (isset($query)) {
			$GLOBALS['log']->debug('Link.Delete:Delete Query: '.$query);
			$this->_db->query($query,true);
		}			
	}		
	
	function relationship_exists($table_name, $join_key_values) {
	
		//find the key values for the table.
		$dup_keys=$this->_get_alternate_key_fields($table_name);
		if (empty($dup_keys)) {
			$GLOBALS['log']->debug("No alternate key define, skipping duplicate check..");
			return false;
		}

		$delimiter='';
		$this->_duplicate_where=' WHERE ';
		foreach ($dup_keys as $key=>$field) {
			//look for key in  $join_key_values, if found add to filter criteria else abort duplicate checking.
			if (isset($join_key_values[$field])) {
				
				$this->_duplicate_where .= $delimiter.' '.$field."='".$join_key_values[$field]."'";						
				$delimiter='AND';
			} else {
				$GLOBALS['log']->error('Duplicate checking aborted, Please supply a value for this column '.$field);
				return false;			
			}
		}
		//add deleted check.
		$this->_duplicate_where .= $delimiter.' deleted=0';						
		
		$query='SELECT id FROM '.$table_name.$this->_duplicate_where;
		
		$GLOBALS['log']->debug("relationship_exists query(".$query.')');
		
		$result=$this->_db->query($query, true);
		$row = $this->_db->fetchByAssoc($result);
		
		if ($row == null) {
			return false; 
		}
		else {
			$this->_duplicate_key=$row['id'];
			return true;
		}
	}
	
	/* returns array of keys for duplicate checking, first check for an index of type alternate_key, if not found searches for
	 * primary key.
	 * 
	 */
	function _get_alternate_key_fields($table_name) {
		$alternateKey=null;
		$indices=Link::_get_link_table_definition($table_name,'indices');
		if (!empty($indices)) {
			foreach ($indices as $index) {
				foreach ($index as $key=>$value) {
					if ($key=='type' and $value=='alternate_key') {
						$alternateKey=$index['fields'];
					}
				}
			}
		}
		return $alternateKey;
	}
	
	/*
	 */
	function _get_link_table_definition($table_name,$def_name) {
		global $dictionary;
		$metafile= 'metadata/'.$table_name.'MetaData.php';
		include($metafile);
		if (isset($dictionary[$table_name][$def_name])) {
			return $dictionary[$table_name][$def_name];
		} else {
			$GLOBALS['log']->debug('Error fething field defs for join table '.$table_name);
			$GLOBALS['log']->debug('Looking meta in this file '.$metafile);
			return null;
		}
	}
	

		
}
?>
