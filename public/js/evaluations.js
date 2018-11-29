
/**
 * EVENTLISTENER
 */


/**
 * SEARCH Table
 * Durchsucht die Tabelle targetTable nach dem Suchbegriff aus der Funktion.
 * SourceElement ist das "callende" Textfeld.
 */
function searchTable(sourceElement, targetTable) {
    //Get search value
    var query = document.getElementById(sourceElement).value;
    //Get Table
    var target = document.getElementById(targetTable);
    //Get rows 
    var rows = target.rows;
    //Iterate through each row [start at x=1, exclude header row!]
    for(var x=1;x<rows.length;x++) {
        var cells = rows[x].cells;
        var treffer = 0;
        //Iterate through all cells in a row (Start bei 1, da ID nicht betrachtet werden soll, finish bei length-1 da zeitstempel uninteressant)
        for(var y=1;y<cells.length-1;y++) {
            //Match?
            if(cells[y].innerHTML.toLowerCase().indexOf(query.toLowerCase()) > -1) {
                treffer++;
            }
        }
        //End of row, hide?
        if(treffer<1) {
            rows[x].style.display = 'none';
        }
        else if(treffer>0) {
            rows[x].style.display='';
        }
    }
}

/**
 * DateTimeCounter
 * 
 * Javascript counter, prints (innerHTML) a counter to a given datetime to a given dom element
 * 
 * @param end = Ende
 * @param element = DOM element to innerHTML
 */

function jayCounter(end, element) {
    // Set the date we're counting down to
    var countDownDate = new Date(end).getTime();

    // Update the count down every 1 second
    var x = setInterval(function () {

        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        document.getElementById(element).innerHTML = days + "T, " + hours + "h "
            + minutes + "m " + seconds + "s ";

        // If the count down is finished, write some text 
        if (distance < 0) {
            clearInterval(x);
            document.getElementById(element).innerHTML = "Abgelaufen";
        }
    }, 1000);

}