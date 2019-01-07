/**
 * CONCEPT
 * 
 * - "One-Line" Filter
 * - Tabellen -> eindeutige ID
 * - Spalten (ths) -> eindeutige ID
 * 
 * Filterarten
 * - elements | Dropdown mit allen Items die in diese Spalte drin sind
 * - date | Datum (keine Uhrzeit!) | von - bis
 * - time | Uhrzeit (kein Datum!) | von - bis
 * - number | Zahl | von - bis
 * 
 * JEDER FILTER:
 * - Spalte
 * - Tabelle
 * - Art
 */

var filters = [];
var activeFilters = [];

class Filter {
    constructor(column, table, type) {
        this.column = column; //TH Objekt
        this.table = table; //Tabellenobjekt
        this.type = type; //elements | date | time | number
    }
}

/**
 * singleFilter
 * returns 0 falls filter nicht passt, 1 falls der filter passt
 */
function singleFilterElements(filter,row,werte) {
    var col = row.cells[filter.column.id];
    var zeroCounter = 0;

    if(werte.indexOf(col.innerHTML)<0) {
        return 0;
    }
    else return 1;

}

/**
 * findFilter
 * finds and returns filter object with given id
 */
function findFilter(id) {
    for(let filter of filters) {
        if(filter.column.id==id) {
            return filter;
        }
    }
    return null;
}

/**
 * addFilter(filter)
 * Fügt einen neuen Filter hinzu
 */
function addFilter(column,table,type) {
    //Create new Filter & add to Array
    var newFilter = new Filter(column,table,type);
    //Builds the DOM for the dropdown
    //buildFilter(newFilter);
    filters.push(newFilter);
    //alert(filters.length)
    //Append filter html to the th
    column.innerHTML += "<button type='button' class='btn' data-toggle='modal' data-target='#exampleModal' data-filter='"+newFilter.column.id+"'><span class='fa fa-filter'></span></button>";
}


/**
 * doFilter()
 * Passt die Tabelle nach den gegebenen Filtern an.
 * Basiert halt auf dem filters[] array
  */
function doFilter(filterID) {
    var filter = findFilter(filterID);
    //Set filter active
    activeFilters.push(filter);

    if(filter.type==='elements') {
        //Get checked elements
        var potentielle = document.getElementsByName('filterCheckbox');
        var werte = [];
        for(let p of potentielle) {
            if(p.checked) {
                werte.push(p.value);
            }
        } 

        //for each row
        var rows = filter.table.rows;
        for(var r=1;r<rows.length;r++) {
            let rowResults = [];
            for(let a of activeFilters) {
                rowResults.push(singleFilterElements(a,rows[r],werte));
            }
    
            //Iterate in rowResults, hide if there is a zero
            var zeroCounter = 0;
            for(let z of rowResults) {
                if(z == 0) {
                    zeroCounter++;
                }
            }
            if(zeroCounter>0) {
                //Zeile raus
                rows[r].style.display='none';
            }
        }
    }





}

function deleteFilter(filter) {

}


/**
 * setColumnIds
 * input: table object
 * Sets a numeric id to every th element of this table (increasing)
 */
function initFilter(tabelle) {
let table = document.getElementById(tabelle);
var elements = table.getElementsByTagName("TH");

var counter = 0;

for(let element of elements) {
    element.setAttribute('id',counter);
    if(element.hasAttribute('filter')) {
        addFilter(element,table,element.getAttribute('filter'));
    }
    counter++;
}
}

/**
 * FILTER UI FUNCTIONS
 * These functions build the filter interfaces for the dropdowns and return the whole DOM element to be appended to the dropdown.
 */

function buildSaver(filter) {
    return "<button type='button' class='btn btn-success' onclick='doFilter("+filter.column.id+")' data-dismiss='modal'><span class='fa fa-save'></span> Anwenden</button>";        
}

//spreads it
function buildFilter(filter) {
    if(filter.type=='elements') {
        return buildElementsFilter(filter);
    }
    
}

function buildElementsFilter(filter) {
    //FINDE ELEMENTE

    //Get column index
    var i = filter.column.id;

    //Empty array of elements
    var items = [];

    //Iterate through it
    var rows = filter.table.rows;
    for(var x=1;x<rows.length;x++) {
        if(items.indexOf(rows[x].cells[i].innerHTML)<0) {
            //Noch nicht drin
            items.push(rows[x].cells[i].innerHTML);
        }
    }

    var container = document.createElement("DIV");

    var table = document.createElement("TABLE");
    table.className = 'table table-hover table-bordered';

    var thead = document.createElement("THEAD");
    var tr = document.createElement("TR");
    var kasten = document.createElement("TH");
    kasten.setAttribute('scope','col');

    var inhalt = document.createElement("TH");
    inhalt.setAttribute('scope','col');

    tr.appendChild(kasten);
    tr.appendChild(inhalt);

    thead.appendChild(tr);

    //table.appendChild(thead);

    var tbody = document.createElement("TBODY");

    for(let z of items) {
        let newRow = document.createElement("TR");
        let colLeft = document.createElement("TD");
        colLeft.innerHTML = "<input class='form-check-input position-static' type='checkbox' name='filterCheckbox' value='"+z+"'>";
        let colRight = document.createElement("TD");
        colRight.innerHTML = z;
        newRow.appendChild(colLeft);
        newRow.appendChild(colRight);
        tbody.appendChild(newRow);
    }
    table.appendChild(tbody);
    container.appendChild(table);
    return container;
}

