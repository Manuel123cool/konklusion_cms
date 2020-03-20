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
let insert = {
    mainPageArray: null,
    thoughtsArchiveArray: [],
    newThoughtsArray: [],
    archivPaggesLength: null,
    newThoughtsPaggesLenghth: null,
    insertWrapper: {
        value: document.getElementById('insertwrapper'),
        writable: false
    },
    elemId: function(id) {
        return document.getElementById( id );
    },
    insertConIntoMain: function (elemName, textName, newCon, reNewElem) {
        let newElem = document.createElement(elemName);
        newElem.insertAdjacentHTML('beforeend', textName);
        newCon.appendChild(newElem);
        if (reNewElem) {
            return newElem;
        } else {
            return newCon; 
        }
    },
    insertIntoCon: function (elemName, textName, newCon) {
        let newElem = document.createElement(elemName);
        newElem.insertAdjacentHTML('beforeend', textName);
        newCon.appendChild(newElem);
        return newElem;
    },
    setHref: function () {
        let element = document.querySelectorAll('a');
        element.forEach(function (currentValue) {
            currentValue.setAttribute('href', '');
        });
    },
    createLinkList: function(newThoughts) {           
        let aside = document.createElement('aside');
        let elem = this.insertConIntoMain('a', 1, aside, true);
        elem.setAttribute('id', 'page_' + 1);
        this.insertWrapper.value.appendChild(aside);
        let paggesLength = newThoughts ? this.newThoughtsPaggesLenghth : 
            this.archivPaggesLength;
        for (let i = 2; i <= paggesLength; i++) {
            elem = this.insertIntoCon('a', i, aside);
            elem.setAttribute('id', 'page_' + i);
        }
        this.setHref();
    },
    listernerToLinksList: function(newThoughts) {
        let linkArray = document.querySelectorAll('main a');
        if (newThoughts) {
            linkArray.forEach((currentValue) => {
                currentValue.addEventListener('click', myEvent.nTLinkEvent);
            });
        } else {
            linkArray.forEach((currentValue) => {
                currentValue.addEventListener('click', myEvent.tALinkEvent);
            });
        }
    },
    reomoveCon: function() {
        let elem = this.elemId("insertwrapper");
        while (elem.firstChild) {
            elem.removeChild(elem.firstChild);
        }
    },
    addArticles: function (array) {
        for (let i in array) {
            let newArticle = document.createElement('article');
            for (let y in array[i]) {
                switch (y) {
                  case '0':
                    this.insertConIntoMain('h3', array[i][y],
                        newArticle);
                    this.insertWrapper.value.appendChild(newArticle);
                    newArticle.setAttribute('id', i);
                    break;
                  default:
                    this.insertIntoCon('p', array[i][y],
                        this.elemId(i));
                }
            }
        }
    },
    addIntoMainPage: function() {
        let mainPageArray = this.mainPageArray;
        for (let i in mainPageArray) {
            let newArticle = document.createElement('article');
            for (let y in mainPageArray[i]) {
                switch (y) {
                  case '0':
                    let elem = this.insertConIntoMain('h3', 
                        mainPageArray[i][y], newArticle);
                    this.insertWrapper.value.appendChild(newArticle);
                    elem.setAttribute('id', i);
                    break;
                  case '1':
                    this.insertIntoCon('p', mainPageArray[i][y],
                        this.elemId(i));
                    break;
                  default:
                    if (mainPageArray[i][y]) {
                        let elem = this.insertIntoCon('a', 'mehr lesen', 
                            this.elemId(i));
                        elem.setAttribute('id', 'pagelink-' + ++i);
                    }
                }
            }
        }
        this.setHref();
    },
    navLinkList: function() {
        this.elemId('header').addEventListener('click', myEvent.mainPage);
        this.elemId('newthoughts').addEventListener('click', myEvent.newThoughts);
        this.elemId('thougthsarchiv').addEventListener('click', myEvent.thougthsArchiv);
    },
    mainPage: function () {  
        this.reomoveCon();
        this.addIntoMainPage();
        this.navLinkList();
        this.elemId('pagelink-1').addEventListener('click', myEvent.newThoughts);
        this.elemId('pagelink-2').addEventListener('click', myEvent.thougthsArchiv);
    },
    thoughtsArchive: function(index) {
        this.reomoveCon();
        this.addArticles(this.thoughtsArchiveArray[index]);
        this.createLinkList(false);
        this.listernerToLinksList(false);
        this.navLinkList();
    },
    newThoughts: function(index) {
        this.reomoveCon();
        this.addArticles(this.newThoughtsArray[index]);
        this.createLinkList(true);
        this.listernerToLinksList(true);
        this.navLinkList();
    },
}
