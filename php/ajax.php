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

include 'mysqli.php';
global $dbCreate;
if (isset($_GET['page'], $_GET['index'])) {
    $page = $_GET['page'];
    switch ($page) {    
        case "mainPage":
            echo (json_encode($dbCreate->getData('mainPage', null, true)));
            break;
        case "newThougths":
            $index = $_GET['index'];
            $paggesLength = $dbCreate->getData('newTougthtsPaggesLength', 
                null, true);
            $array = $dbCreate->getData($page, $index);
            $json = array('array' => $array,
                'paggesLength' => $paggesLength);
            echo (json_encode($json));
            break;
        case "thoughtsArchive":
            $index = $_GET['index'];
            $paggesLength = $dbCreate->getData('thoughtsArchivPaggesLength', 
                null, true);
            $array = $dbCreate->getData($page, $index);
            $json = array('array' => $array,
                'paggesLength' => $paggesLength);
            echo (json_encode($json));

            break;
        default: 
            echo 'No value of $page is an 
                equation to one of the cases';
    }
}
