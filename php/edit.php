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

class EditArrayFile {
    private $page;
    private $directory;
    private $file_content;
    private $editArray;
    
    function __construct($pageArg) {
        $this->page = $pageArg;
        $this->directory = "data/$this->page" . '.txt';
        $this->file_content = file_get_contents($this->directory);
        $this->editArray = unserialize($this->file_content);
    } 
    private function checkEqualHeading($heading, $index) {
        foreach ($this->editArray as $key => $article) {
            if (isset($article[0])) {
                if($article[0] == $heading && $index != $key) {
                    echo 'Warnig: Equal headers';
                    return;
                }
            }
        }
    }
    public function editPage($firstIndex, $secondIndex, $string) {
        if ($firstIndex > 2 && $this->page == 'main-page') {
            echo 'Article number out of range';
            return null;
        } else if ($firstIndex >= count($this->editArray)){
            echo 'Article number out of range';
            return null;
        } 
        $this->checkEqualHeading($string, $firstIndex);
        
        $this->editArray[$firstIndex][$secondIndex] = $string;
        file_put_contents($this->directory, serialize($this->editArray));
    }
    public function insert($string, $index, $secondIndex) {
        if ($this->page === 'main-page') {
            echo 'No main-page operation';
            return null;
        } elseif ($index > count($this->editArray)) {
            echo 'Article number out of range';
            return null;
        } 
        
        $longerArray = array();
        for ($i = 0; $i < count($this->editArray) + 1; $i++) {
            $longerArray[$i] = array();
        }
        
        for ($i = 0; $i < $index; $i++) {
            $longerArray[$i] = $this->editArray[$i];
        }
        
        if ($secondIndex == 1) {
            $longerArray[$index][0] = 'Example';
            $longerArray[$index][1] = $string;
        } else {
            $longerArray[$index][0] = $string;
            $longerArray[$index][1] = 'Example';
        }
        
        for ($i = $index + 1; $i < count($longerArray); $i++) {
            $longerArray[$i] = $this->editArray[$i - 1];
        }
        
        $this->editArray = $longerArray;
        $this->checkEqualHeading($string, $index);
        
        file_put_contents($this->directory, serialize($this->editArray));
    }
    public function delete($index) {
        if ($this->page === 'main-page') {
            echo 'No main-page operation';
            return null;
        }
        array_splice($this->editArray, $index, 1);
        file_put_contents($this->directory, serialize($this->editArray));
    }
}
?>
