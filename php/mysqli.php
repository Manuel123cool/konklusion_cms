<?php
/*  Konklusion_cms: open source blog manager
    Copyright (C) 2020  Manuel Maria Kümpel

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

include 'array.php';
class DB_Create extends myArray { 
    private $servername = "localhost";
    private $username = "#username";
    private $password = "#password";
    private $dbname = "konklusion_cms";
    private function connDB($creatingDB) {
        $conn = null;
        if ($creatingDB) {
            $conn = new mysqli($this->servername, $this->username, 
                $this->password);
        } else {
            $conn = new mysqli($this->servername, $this->username, 
                $this->password, $this->dbname);
        }
        if ($conn->connect_error) {
            exit('Connection failed: ' . $conn->connect_error);
        }
        return $conn;
    }
    public function idToOne() {
        $conn = $this->connDB(false);
        $sql = 'ALTER TABLE `pages_data` AUTO_INCREMENT=1';
        if($conn->query($sql) === true) {
            //echo "Id to one created successfully";
        } else {
            echo "Error creating id to one: " . $conn->error;
        } 
        $conn->close();
    }
    public function database($dbname = 'konklusion_cms') {
        $conn = $this->connDB(true);
        $sql = 'CREATE DATABASE IF NOT EXISTS ' . $dbname;
        if ($conn->query($sql) === true) {
            //echo 'Database created successfully';
        } else {
            echo 'Error creating database: ' . $conn->error;
        }
        $conn->close();
    }    
    public function table() {
        $conn = $this->connDB(false);
        $sql = 'CREATE TABLE IF NOT EXISTS pages_data (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            newTougthtsPaggesLength TINYINT,
            thoughtsArchivPaggesLength TINYINT,
            mainPage TEXT,
            newThougths MEDIUMTEXT,
            thoughtsArchive MEDIUMTEXT 
        )';
        if ($conn->query($sql) === true) {
            //echo "Table pages_data created successfully";
        } else {
            echo "Error creating table: " . $conn->error;
        }
        $conn->close();
    }
    public function newRecords() {
        global $myArray;
        $file_content = file_get_contents('data/main-page.txt');
        $myArray->mainPageArticles = unserialize($file_content);

        $file_content = file_get_contents('data/new-thoughts.txt');
        $myArray->nTArticles = unserialize($file_content);

        $file_content = file_get_contents('data/thoughts-archiv.txt');
        $myArray->tA_Articles = unserialize($file_content);

        $conn = $this->connDB(false);
        if (!(
            $stmt = $conn->prepare('INSERT INTO pages_data (
            newTougthtsPaggesLength, 
            thoughtsArchivPaggesLength,
            mainPage,
            newThougths,
            thoughtsArchive) 
            VALUES (?, ?, ? , ?, ?)'
            ))) {
            echo "Prepare failed: 
                (" . $conn->errno . ") " . $conn->error;
        }   
        $stmt->bind_param(
            "iisss", 
            $nTPaggesLength,
            $tAPaggesLength,
            $mainPage,
            $newThougths,
            $thoughtsArchive
        );
        $nTArray = $this->main($myArray->nTArticles);
        $tA_Array = $this->main($myArray->tA_Articles);
        $nTPaggesLength = $this->paggesLength($myArray->nTArticles);
        $tAPaggesLength = $this->paggesLength($myArray->tA_Articles);
        $mainPage = serialize($myArray->mainPageArticles);
        $newThougths = serialize($nTArray[0]);
        $thoughtsArchive = serialize($tA_Array[0]);
        $stmt->execute();
        for ($i = 1; $i < $this->reBiggerLength(); $i++) {
            $nTPaggesLength = null;
            $tAPaggesLength = null;
            $newThougths = null;
            $thougthsArchive = null;
            $mainPage = null;
            if ($i < $this->paggesLength($myArray->nTArticles)) {
                $newThougths = serialize($nTArray[$i]);
            } 
            if ($i < $this->paggesLength($myArray->tA_Articles))  {
                $thoughtsArchive = serialize($tA_Array[$i]);
            }
            $stmt->execute();
        }
        //echo "New records created successfully";
        $stmt->close();
        $conn->close();
    }
    public function getData($data, $index, $firstpage = false) {
        $conn = $this->connDB(false);
        if ($firstpage) {
            $index = 0;
        }
        //echo '<br>' . $data . ' ' . $index;
        $sql = 'SELECT ' . $data . ' FROM pages_data where id =' . ++$index;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if ($data === 'newTougthtsPaggesLength' || 
                    $data === 'thoughtsArchivPaggesLength') {
                    return $row[$data];
                } else {
                    return (unserialize($row[$data]));
                }
            }
        } else {
            echo("Error description: " . mysqli_error($conn));
        }
        $conn->close();
    }
    public function deleteTableData() {
        $conn = $this->connDB(false);
        $sql = 'DELETE FROM pages_data';
        if ($conn->query($sql) === true) {
            //echo "Deletion created successfully";
        } else {
            echo "Error creating deletion: " . $conn->error;
        }
    }
}
$dbCreate = new DB_Create;
$dbCreate->database();
$dbCreate->table();
