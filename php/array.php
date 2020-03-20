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

class MyArray {
    public $nTArticles = null;
    public $tA_Articles = null;
    public $mainPageArticles = null;
    private function pageSize($array) {
        $pixelHeigth = null;
        $indexLength = -1;
        $stringLength = null;
        $updatetArray = null;
        foreach ($array as $key => $value) {
            $pixelHeigth += 60;
            $stringLength = strlen($value[1]);
            if ($stringLength > 100) {
                $pixelHeigth += ($stringLength / 100) * 20;
            } else {
                $pixelHeigth += 20;
            }
            if ($pixelHeigth > 2500) {
                return $indexLength;
            } else {
                $indexLength++;
            }
        }
        return ++$indexLength;
    }
    private function newByArr($newArray, $pageArray) {
        $pageArrayObject = new arrayobject($pageArray);
        $currentPageArray = $pageArrayObject->getArrayCopy();
        $arrayIndexLength = $this->pageSize($currentPageArray);
        $arrayPlaceMakerObject = new arrayobject($currentPageArray);
        $arrayPlaceMaker = $arrayPlaceMakerObject->getArrayCopy();
        $countedArchivPagges = $this->paggesLength($pageArray);
        for ($i = 0; $i < $countedArchivPagges; $i++) {
            $newArray[$i] = [];
            for ($j = 0; $j < $arrayIndexLength; $j++) {
                $newArray[$i][$j] = $arrayPlaceMaker[$j];
            }
            array_splice($arrayPlaceMaker, 0, $arrayIndexLength);
            $arrayIndexLength = $this->pageSize($arrayPlaceMaker);
        }
        return $newArray;
    }
    public function paggesLength($pageArray) {
        $newArrayObject = new arrayobject($pageArray);
        $newArray = $newArrayObject->getArrayCopy();
        $archivPaggesLength = null;
        $arrayIndexLength = null;
        $arrayIndexLength = $this->pageSize($newArray);
        while ($arrayIndexLength !== 0) {
            $archivPaggesLength++;
            array_splice($newArray, 0, $arrayIndexLength);
            $arrayIndexLength = $this->pageSize($newArray);
        }
        return $archivPaggesLength;
    }
    public function main($pageArray) {
        $newArray = array();
        $rearrangedArray = $this->newByArr($newArray, $pageArray);
        return $rearrangedArray;
    }
    public function reBiggerLength() {
        global $myArray;
        $newThoughts = $this->
            paggesLength($myArray->nTArticles);
        $thoughtsArchiv = $this->
            paggesLength($myArray->tA_Articles);
        if ($newThoughts > $thoughtsArchiv) {
            return $newThoughts;
        } else {
            return $thoughtsArchiv;
        }
    }
}
$myArray = new MyArray;
