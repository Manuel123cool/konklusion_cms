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

"use strict";
let ajaxByDOM = {
    mainPage: function() {
        let xmlhttp0 = new XMLHttpRequest();
        xmlhttp0.addEventListener('readystatechange', (e) => {
            if (xmlhttp0.readyState==4 && xmlhttp0.status==200) {
                let mainPageArticels = xmlhttp0.responseText;
                mainPageArticels = JSON.parse(mainPageArticels);
                insert.mainPageArray = mainPageArticels;
                insert.mainPage();
            }
        });
        xmlhttp0.open('GET', 'php/ajax.php?page=mainPage&index=no', true);
        xmlhttp0.send();
    },
    page: function(newThoughts, index) {
        try {
            if (newThoughts && insert.newThoughtsArray[index].length) {
                insert.newThoughts(index);
                return;
            } else if (!newThoughts && insert.thoughtsArchiveArray[index].length) {
                insert.thoughtsArchive(index);
                return;
            }
        } catch (arr) {
            console.log(arr);
        }
            
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.addEventListener('readystatechange', (e) => {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                let json = null;
                try {
                    json = JSON.parse(xmlhttp.responseText);
                }
                catch(err) {
                    document.getElementById("insertwrapper").innerHTML = 
                        'Maybe wrong site index. ';
                }
                let page = json.array;

                if (newThoughts) {
                    insert.newThoughtsPaggesLenghth = json.paggesLength;
                    insert.newThoughtsArray[index] = page;
                    insert.newThoughts(index);
                } else {
                    insert.archivPaggesLength = json.paggesLength
                    insert.thoughtsArchiveArray[index] = page;
                    insert.thoughtsArchive(index);
                }
            }
        });
        let pageSite = newThoughts ? 'newThougths' : 'thoughtsArchive'; 
        xmlhttp.open('GET', 
            'php/ajax.php?page=' + pageSite + '&index=' + index, true);
        xmlhttp.send();
    }
}
