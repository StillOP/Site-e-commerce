<?php

include_once("param.inc.php");

class database
{
     private function connect($host=HOST, $database=DATABASE, $user=USER, $password=PASSWORD)
     {
      $dsn="mysql:host=".$host.";dbname=".$database.";charset=utf8";
        
        try { $dbc = new PDO($dsn,$user,$password); return $dbc; }
        
        catch(PDOException $except) { echo "Unable to connect to the database"; return false; }   
     }
    
    public function select($request)
    {
        $dbc = $this->connect();
        $result=$dbc->query($request);
        $error = $dbc->errorInfo(); 
        
        if($result==false) { echo "Unable to select in the database, ",$error[2]; $dbc->errorCode(); }
        else
        {
            $tab[0] = $result->rowCount();
            for($i = 0; $i < $tab[0]; $i++)
            {
                $tab[] = $result->fetchObject();
            }
            return $tab;
        }
    }
    
    public function insert($request)
    {
        $dbc = $this->connect();
        $result = $dbc->exec($request);
        if($result != 1)
        {
            $error = $dbc->errorInfo();
            echo "Unable to insert in the database, ", $dbc->errorCode(), $error[2];
        }
        return $dbc->lastInsertId();
    }
    
    public function update($request)
    {
        $dbc = $this->connect();
        $result = $dbc->exec($request);
        if($result != 1)
        {
            echo "Unable to update in the database, ", $dbc->errorCode();
        }
    }
    
    public function delete($request)
    {
        $dbc = $this->connect();
        $result = $dbc->exec($request);
        if($result == 0)
        {
            echo "Unable to delete in the database, ", $dbc->errorCode();
        }
    }
    
    public function format($element) 
    {
        $var = $element;
        
        $dbc = $this->connect();
        $var = trim($var);
        $var = htmlspecialchars($var);
        $var = $dbc->quote($var);
        
        return $var;
    }
}
