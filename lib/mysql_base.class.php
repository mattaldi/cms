  <?php

  class mysql_base {
    
    /* public: connection parameters */
    public $Host     = "";
    public $Database = "";
    public $User     = "";
    public $Password = "";

    /* public: configuration parameters */
    public $Auto_Free     = 0;     ## Set to 1 for automatic mysqli_free_result()
    public $Debug         = 0;     ## Set to 1 for debugging messages.
    public $Halt_On_Error = "yes"; ## "yes" (halt with message), "no" (ignore errors quietly), "report" (ignore errror, but spit a warning)
    public $Seq_Table     = "db_sequence";

    /* public: result array and current row number */
    public $Record   = array();
    public $Row;

    /* public: current error number and error text */
    public $Errno    = 0;
    public $Error    = "";

    /* public: this is an api revision, not a CVS revision. */
    public $type     = "mysql";
    public $revision = "1.2";

    /* private: link and query handles */
    private $Link_ID  = 0;
    private $Query_ID = 0;
    


    /* public: constructor */
    public function __construct($query = ""){
        $this->query($query);
    }

    /* public: some trivial reporting */
    public function link_id() {
      return $this->Link_ID;
    }

    public function query_id() {
      return $this->Query_ID;
    }

    public function prepare($query) {
      // Ensure the connection is established
      if (!$this->connect()) {
          return false;
      }

      // Prepare the query
      $stmt = mysqli_prepare($this->Link_ID, $query);
      if ($stmt === false) {
          // Handle errors, maybe log them or throw an exception
          return false;
      }

      return $stmt;
  }


    /* public: connection management */
    public function connect($Database = "", $Host = "", $User = "", $Password = "") {
      /* Handle defaults */
      if ("" == $Database)
        $Database = $this->Database;
      if ("" == $Host)
        $Host     = $this->Host;
      if ("" == $User)
        $User     = $this->User;
      if ("" == $Password)
        $Password = $this->Password;
        
      /* establish connection, select database */
      
      if ( "0" == $this->Link_ID ) {
      $this->Link_ID=mysqli_connect($Host, $User, $Password);
          

        if (!$this->Link_ID) {
          $this->halt("connect($Host, $User, \$Password) failed.");
          return 0;
        }

        if (!mysqli_select_db($this->Link_ID,$Database)) {
          $this->halt("cannot use database ".$this->Database);
          return 0;
        }
      }
      
      return $this->Link_ID;
    }

    /* public: discard the query result */
    public function free() {
      if ($this->Query_ID) {
          mysqli_free_result($this->Query_ID);
          $this->Query_ID = 0;
      }
  }


    /* public: perform a query */
    public function query($Query_String) {
      /* No empty queries, please, since PHP4 chokes on them. */
      if ($Query_String == "")
        /* The empty query string is passed on from the constructor,
        * when calling the class without a query, e.g. in situations
        * like these: '$db = new DB_Sql_Subclass;'
        */
        return 0;

      if (!$this->connect()) {
        return 0; /* we already complained in connect() about that. */
      };

      # New query, discard previous result.
      if ($this->Query_ID) {
        $this->free();
      }

      if ($this->Debug)
        printf("Debug: query = %s<br>\n", $Query_String);

      $this->Query_ID = mysqli_query($this->Link_ID,$Query_String);
      $this->Row   = 0;
      //$this->Errno = mysqli_errno($this->Link_ID);
      //$this->Error = mysqli_error($this->Link_ID);
      if (!$this->Query_ID) {
        $this->halt("Invalid SQL: ".$Query_String);
      }

      # Will return nada if it fails. That's fine.
      return $this->Query_ID;
    }

    /* public: walk result set */
    public function next_record() {
      if (!$this->Query_ID) {
        $this->halt("next_record called with no query pending.");
        return 0;
      }

      $this->Record = mysqli_fetch_array($this->Query_ID);    
      $this->Row   += 1;
      $this->Errno  = mysqli_errno($this->Link_ID);
      $this->Error  = mysqli_error($this->Link_ID);

      $stat = is_array($this->Record);
      if (!$stat && $this->Auto_Free) {
        $this->free();
      }
      return $stat;
    }
  /* public: walk result set */
    public function row_record() {
      if (!$this->Query_ID) {
        $this->halt("next_record called with no query pending.");
        return 0;
      }

      $this->Record = mysqli_fetch_row($this->Query_ID);
      $this->Row   += 1;
      $this->Errno  = mysqli_errno($this->Link_ID);
      $this->Error  = mysqli_error($this->Link_ID);

      $stat = is_array($this->Record);
      if (!$stat && $this->Auto_Free) {
        $this->free();
      }
      return $stat;
    }

    public function select($table, $columns = [], $where = []) {
      // Ensure the connection is established
      if (!$this->connect()) {
          return false;
      }

      // Construct SELECT part
      $columnString = empty($columns) ? "*" : implode(", ", $columns);

      // Start building the query
      $query = "SELECT $columnString FROM $table";

      // Add WHERE conditions if provided
      if (!empty($where)) {
          $whereClauses = [];
          foreach ($where as $key => $value) {
              $value = mysqli_real_escape_string($this->Link_ID, $value);
              $whereClauses[] = "$key = '$value'";
          }
          $query .= " WHERE " . implode(" AND ", $whereClauses);
      }

      // Perform the query and return the result
      return $this->query($query);
  }




    /* public: table locking */
    public function lock($table, $mode="write") {
      $this->connect();
      
      $query="lock tables ";
      if (is_array($table)) {
        while (list($key,$value)=each($table)) {
          if ($key=="read" && $key!=0) {
            $query.="$value read, ";
          } else {
            $query.="$value $mode, ";
          }
        }
        $query=substr($query,0,-2);
      } else {
        $query.="$table $mode";
      }
      $res = mysqli_query($query, $this->Link_ID);
      if (!$res) {
        $this->halt("lock($table, $mode) failed.");
        return 0;
      }
      return $res;
    }
    
    public function unlock() {
      $this->connect();

      $res = mysqli_query("unlock tables");
      if (!$res) {
        $this->halt("unlock() failed.");
        return 0;
      }
      return $res;
    }

    /* return number of rows result (counting) */
    public function num_rows() {
      return @mysqli_num_rows($this->Query_ID);
    }

    public function num_fields() {
      return @mysqli_num_fields($this->Query_ID);
    }

    /* public: shorthand notation */
    public function nf() {
      return $this->num_rows();
    }

    public function np() {
      print $this->num_rows();
    }

    public function f($Name) {
      if(isset($this->Record[$Name]))
        return stripslashes($this->Record[$Name]);
      else 
        return "";
    }

    //mysqli_fetch_row($result)
    public function rowdata() {	
      return $this->Record;	
    }
    
    //mysqli_fetch_array()
    public function fetchArray() {
      return mysqli_fetch_array($this->Query_ID);
    }

    public function fetchAll($result) {
      $rows = [];
      while ($row = mysqli_fetch_assoc($result)) {
          $rows[] = $row;
      }
      return $rows;
  }

    
    /* private: error handling */
    public function halt($msg) {
      $this->Error = mysqli_error($this->Link_ID);
      $this->Errno = mysqli_errno($this->Link_ID);
      if ($this->Halt_On_Error == "no")
        return;

      $this->haltmsg($msg);

      if ($this->Halt_On_Error != "report")
        die("Session halted.");

    }

    public function haltmsg($msg) {
      printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
      printf("<b>MySQL Error</b>: %s (%s)<br>\n",
        $this->Errno,
        $this->Error);
    }
    
  }
  ?>