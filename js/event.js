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
let urlPar = {
    checkPar: function (rePageChar = false) {
        let url = window.location.href;
        let indexPage = url.search("page=");
        let indexIndex = url.search("index=");
        let firstPageChar = 'm';
        
        if (indexPage != -1) {
            firstPageChar = url.charAt(indexPage + 5); 
        } 

        if (rePageChar) {
            return firstPageChar;
        }
 
        let index = 0;
        if (indexIndex != -1) {
           index = url.charAt(indexIndex + 6) - 1;
        }
 
        switch (firstPageChar) {
            case 'n':
                ajaxByDOM.page(true, index);
                return true;
            case 't':
                ajaxByDOM.page(false, index);
                return true;
            default:
                ajaxByDOM.mainPage();
                return true;
        }
    },
    insertParam: function(value, siteIndex = false)
    {
        value = encodeURIComponent(value);
        let key = 'page';
        let oldPar = '';
        if (siteIndex) {
            if (this.checkPar(true) == 't') {
                oldPar = 'page=thougthsarchiv&';
            } else {
                oldPar = 'page=newthoughts&';
            }
            key = 'index';
        }
        let url = 'index.html?' + oldPar + key + '=' + value;
        window.history.pushState(null, null, url);
        this.checkPar();
    }
}

let myEvent = function() {
    window.onpopstate = function(event) {
        urlPar.checkPar();
    }
    function init() {
        urlPar.checkPar();
    }
    document.addEventListener('DOMContentLoaded', init);
    
    function mainPageEvent(e) {
        e.preventDefault();
        urlPar.insertParam('mainpage');
    }
    
    function newThoughtsEvent(e) {
        e.preventDefault();
        urlPar.insertParam('newthoughts');
    } 

    function thougthsArchivEvent(e) {
        e.preventDefault();
        urlPar.insertParam('thougthsarchiv');
    }
    
    function nTLinkEvent(e) {
        e.preventDefault();
        let index = e.currentTarget.innerText;
        urlPar.insertParam(index, true);
    }
    
    function tALinkEvent(e) {
        e.preventDefault();
        let index = e.currentTarget.innerText;
        urlPar.insertParam(index, true);
    }
    return {
        mainPage: mainPageEvent,
        newThoughts: newThoughtsEvent,
        thougthsArchiv: thougthsArchivEvent,
        tALinkEvent: tALinkEvent,
        nTLinkEvent: nTLinkEvent
    }
}();
