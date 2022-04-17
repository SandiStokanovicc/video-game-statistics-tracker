<?php
    class BaseDao{
        private $conn;
        private $table_name;

        public function __construct($table_name){
            $this->table_name = $table_name;
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $schema = "riot";
            $this->conn = new PDO("mysql:host=$servername; dbname=$schema", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        //read objects
        public function get_all(){
            $stmt = $this -> conn ->prepare("Select * from".$this->table_name);
            $stmt ->execute();
            return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        }

        //get objets by id
        public function get_by_id($id){
            $stmt = $this -> conn ->prepare("Select * from".$this->table_name."where id = _id");
            $stmt->execture(['id'=>$id]);
            $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            return reset($result);
        }
        // delete a record  
        public function delete($id){
            $stmt = $this->conn->prepare("delete from ".$this->table_name." where id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
          }

        // add a record
        public function add($entity){
            $query = "insert into ".$this->table_name." (";
            foreach ($entity as $column => $value) {
                $query .= $column.",";
            }
            $query = substr($query, 0, -2);
            $query .= ") VALUES (";
            foreach ($entity as $column => $value){
                $query .= ":".$column.", ";
            }
            $query = substr($query, 0, -2);
            $query .= ")";

            $stmt =$this->conn->prepare($query);
            $enttiy['id'] = $id;
            $stmt->execute($entity);
        }
        
        //update a record
        public function update($id, $entity, $id_column = "id"){
            $query = "UPDATE ".this->table_name." SET ";
            foreach($entity as $name => $value){
                $query .= $name ."= :". $name.",";
            }
            $query = substr($query, 0, -2);
            $query .= "Where ${id_column} =:id";

            $stmt=$this->conn->prepare($query);
            $entity['id'] =$id;
            $stmt ->execute($entity);
        }

        protected function query($query, $params){
            $stmt = $this->conn->prepare($query);
            $stmt = execute($params);
            return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        }

        protected function query_unique($query, $params){
            $result = $this ->query($query, $params);
            return reset($results);
        }


    }
?>