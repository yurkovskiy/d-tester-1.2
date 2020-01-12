<?php

/*
+--------------------------------------------------------------------------
|   Invision Power Board v1.2
|   ========================================
|   by Matthew Mecham
|   (c) 2001 - 2003 Invision Power Services
|   http://www.invisionpower.com
|   ========================================
|   Web: http://www.invisionboard.com
|   Email: matt@invisionpower.com
|   Licence Info: http://www.invisionboard.com/?license
+---------------------------------------------------------------------------
|
|   > MySQL DB abstraction module
|   > Module written by Matt Mecham
|   > Date started: 14th February 2002
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
*/



class db_driver {

    var $obj = array ( "sql_database"   => ""         ,
                       "sql_user"       => "root"     ,
                       "sql_pass"       => ""         ,
                       "sql_host"       => "localhost",
                       "sql_port"       => ""         ,
                       "persistent"     => "0"         ,
                       "cached_queries" => array(),
                     );
                     
     var $query_id      = "";
     var $connection_id = "";
     var $query_count   = 0;
     var $record_row    = array();
     var $return_die    = 0;
     var $error         = "";
     var $error_no	= 0;
     var $failed        = 0;
                  
    /*========================================================================*/
    // Connect to the database                 
    /*========================================================================*/  
                   
    function connect() {
    
    	if ($this->obj['persistent'])
    	{
    	    $this->connection_id = mysql_pconnect( $this->obj['sql_host'] ,
												   $this->obj['sql_user'] ,
												   $this->obj['sql_pass'] 
												);
        }
        else
        {
			$this->connection_id = mysql_connect( $this->obj['sql_host'] ,
												  $this->obj['sql_user'] ,
												  $this->obj['sql_pass'] 
												);
		}
		
        if ( !mysql_select_db($this->obj['sql_database'], $this->connection_id) )
        {
            echo ("ERROR: Неможливо під&#180;єднатися до бази даних ".$this->obj['sql_database']);
        }
    }
    
    
    
    /*========================================================================*/
    // Process a query
    /*========================================================================*/
    
    function query($the_query) {
    	
    	$this->query_id = mysql_query($the_query, $this->connection_id);
      
        if (!$this->query_id )
        {
            $this->fatal_error("MySQL неможливо виконати запит: $the_query");
        }
        
        $this->query_count++;
        
        $this->obj['cached_queries'][] = $the_query;
        
        return $this->query_id;
    }
    
    /*========================================================================*/
    // Get last query identificator
    /*========================================================================*/
    
    function get_query_result()
    {
    	return $this->query_id;
    }
    
    /*========================================================================*/
    // Get last query number of fields
    /*========================================================================*/
    
    function get_fields_num($query_id = "")
    {
    	if($query_id == "")
    	{
    		$query_id = $this->query_id;
    	}
    	
    	return mysql_num_fields($query_id);
    }    
    
    /*========================================================================*/
    // Fetch a row based on the last query
    /*========================================================================*/
    
    function fetch_row($query_id = "") {
    
    	if ($query_id == "")
    	{
    		$query_id = $this->query_id;
    	}
    	
        $this->record_row = mysql_fetch_array($query_id, MYSQL_BOTH);
        
        return $this->record_row;
        
    }

    /*========================================================================*/
    // Fetch the number of rows affected by the last query
    /*========================================================================*/
    
    function get_affected_rows() {
        return mysql_affected_rows($this->connection_id);
    }
    
    /*========================================================================*/
    // Fetch the number of rows in a result set
    /*========================================================================*/
    
    function get_num_rows() {
        return mysql_num_rows($this->query_id);
    }
    
    /*========================================================================*/
    // Fetch the last insert id from an sql autoincrement
    /*========================================================================*/
    
    function get_insert_id() {
        return mysql_insert_id($this->connection_id);
    }  
    
    /*========================================================================*/
    // Return the amount of queries used
    /*========================================================================*/
    
    function get_query_count() {
        return $this->query_count;
    }
    
    /*========================================================================*/
    // Free the result set from mySQLs memory
    /*========================================================================*/
    
    function free_result($query_id="") {
    
   		if ($query_id == "") {
    		$query_id = $this->query_id;
    	}
    	
    	@mysql_free_result($query_id);
    }
    
    /*========================================================================*/
    // Shut down the database
    /*========================================================================*/
    
    function close_db() { 
        return mysql_close($this->connection_id);
    }
    
    /*========================================================================*/
    // Return an array of tables
    /*========================================================================*/
    
    function get_table_names() {
    
		$result     = mysql_list_tables($this->obj['sql_database']);
		$num_tables = @mysql_numrows($result);
		for ($i = 0; $i < $num_tables; $i++)
		{
			$tables[] = mysql_tablename($result, $i);
		}
		
		mysql_free_result($result);
		
		return $tables;
   	}
   	
    /*========================================================================*/
    // Return an array of fields
    /*========================================================================*/
    
    function get_result_fields($query_id="") {
    
   		if ($query_id == "")
   		{
    		$query_id = $this->query_id;
    	}
    
		while ($field = mysql_fetch_field($query_id))
		{
            $Fields[] = $field;
		}
		
		//mysql_free_result($query_id);
		
		return $Fields;
   	}
    
    /*========================================================================*/
    // Basic error handler
    /*========================================================================*/
    
    function get_field_name($field_num)
    {
    	return mysql_field_name($this->query_id, $field_num);
    }
    
    function get_field_type($field_num) {
    	return mysql_fieldtype($this->query_id, $field_num);
    }
    
    function get_field_len($field_num) {
    	return mysql_field_len($this->query_id, $field_num);
    }
    
    function get_field_flags($field_num) {
    	return mysql_field_flags($this->query_id, $field_num);
    }
    
    function escape_string($str) {
    	return mysql_escape_string($str);
    }
    
    function fatal_error($the_error) {
    	    	
    	// Are we simply returning the error?
    	
    	if ($this->return_die == 1)
    	{
    		$this->error    = mysql_error();
    		$this->error_no = mysql_errno();
    		$this->failed   = 1;
    		return;
    	}
    	
    	$the_error .= "\n\nMySQL помилка: ".mysql_error()."\n";
    	$the_error .= "MySQL код помилки: ".$this->error_no."\n";
    	$the_error .= "дата: ".date("l dS of F Y h:i:s A");
    	
    	$out = "<html><head><title>d-tester Database Error</title>
    		   <style>P,BODY{ font-family:arial,sans-serif; font-size:11px; }</style></head><body>
    		   &nbsp;<br><br><blockquote><b>Виникла помилка при звернені до бази даних.</b><br>
    		   Ви можете перезавантажити сторінку, натиснувши <a href=\"javascript:window.location=window.location;\">тут</a>, Якщо це
    		   допоможе зверніться до адміністратора системи, натиснувши <a href='mailto:bz_yuriy@mail.ru?subject=SQL+Error'>тут</a>
    		   <br><br><b>Помилка була згенерована при виконанні</b><br>
    		   <form name='mysql'><textarea rows=\"15\" cols=\"60\">".htmlspecialchars($the_error)."</textarea></form><br>Ми сподіваємося на ВАШЕ розуміння</blockquote></body></html>";
    		   
    
        echo($out);
        die("");
    }
    
} // end class


?>