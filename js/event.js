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
    //https://html-online.com/articles/get-url-parameters-javascript/
    getUrlVars: function (){
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
    },
    getUrlParam: function (parameter) {
        var urlparameter = 'empty';
        if(window.location.href.indexOf(parameter) > -1){
            urlparameter = this.getUrlVars()[parameter];
        }
        if (urlparameter == undefined) {
            urlparameter = 'empty';
        }
        return urlparameter.replace('#', '');
    },
    checkPar: function () {
        let pagePar = this.getUrlParam('page');
        let index = 0;
        if (this.getUrlParam('index') != 'empty') {
            index = this.getUrlParam('index') - 1;
        }
        switch (pagePar) {
            case 'newthoughts':
                ajaxByDOM.page(true, index);
                return true;
            case 'thougthsarchiv':
                ajaxByDOM.page(false, index);
                return true;
            case 'mainpage':
                ajaxByDOM.mainPage();
                return true;
        }
        return false;
    },
    insertParam: function(value, siteIndex = false)
    {
        value = encodeURIComponent(value);
        let key = 'page';
        let oldPar = '';
        if (siteIndex) {
            if (urlPar.getUrlParam('page') == 'thougthsarchiv') {
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
        let parEmty = false;
        try {
                parEmty = urlPar.getUrlParam('page') == 'empty';
        } catch(err) {
                urlPar.insertParam('mainpage');
        }
        
        if (parEmty) {
            ajaxByDOM.mainPage();
        } else {
            try {
                if (!urlPar.checkPar()) {
                    urlPar.insertParam('mainpage');
                }
            } catch(err) {
                urlPar.insertParam('mainpage', false);
            }
        }
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
