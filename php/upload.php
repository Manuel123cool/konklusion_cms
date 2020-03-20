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

include 'edit.php';
include 'mysqli.php';

global $dbCreate;

$firstIndex = $_GET['article_number'] - 1;
$secondIndex = 0;

$entryAllowed = $_GET['name'] === '#name' && $_GET['password'] === '#password';

if ($_GET['which_text'] === 'text') {
    $secondIndex = 1;
}

if (isset($_GET['page'], $_GET['mode'], 
    $_GET['article_number']) && $entryAllowed) {
    $editArrayFile = new EditArrayFile($_GET['page']);
    switch ($_GET['mode']) {
        case 'edit':
            $editArrayFile->editPage($firstIndex, 
                $secondIndex, $_GET['text']);
            break;
        case 'new-insert':
            $editArrayFile->insert($_GET['text'], 0, $secondIndex);
            break;
        case 'insert':
            $editArrayFile->insert($_GET['text'], $firstIndex, $secondIndex);
            break;
        case 'delete':
            $editArrayFile->delete($firstIndex);
            break;
    }
}
$dbCreate->deleteTableData();
$dbCreate->idToOne();
$dbCreate->newRecords();
?>
