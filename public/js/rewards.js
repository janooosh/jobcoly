//Init
const tmaxContainer = document.getElementById('tmaxcontainer');
const tmax = StringToMin(document.getElementById('ttotal').innerHTML);
const tvergebenContainer = document.getElementById('tvergebencontainer');
const tvergeben = 0;



//Event Listeners
document.addEventListener("DOMContentLoaded", init);

    var listener = [];
    var g_fields = document.getElementsByName('gut[]');
    console.log(g_fields.length);
    var a_fields = document.getElementsByName('awe[]');
    for(var g = 0;g<g_fields.length;g++) {
        listener.push(g_fields[g]);
    }
    for (var a = 0;a<a_fields.length;a++) {
        listener.push(a_fields[a]);
    }

    for(var x=0;x<listener.length;x++) {
        listener[x].addEventListener('change',globalsAnpassen);
        listener[x].addEventListener('change',zeileAnpassen);
    }

//Init Function - Überprüft und Färbt Werte
function init() {
    //Vergeben > Offen ?
        //Total
        var total_container = document.getElementById('ttotal');
        var total_value = StringToMin(total_container.innerHTML);
        //Vergeben
        var vergeben_container = document.getElementById('tvergebencontainer');
        var vergeben_value = StringToMin(vergeben_container.innerHTML);
        //Offen
        var offen_container = document.getElementById('tmaxcontainer');
        var offen_value = StringToMin(offen_container.innerHTML);
        //Calc
        if(vergeben_value>total_value) {
            colorizer(vergeben_container,'red');
            colorizer(offen_container,'red');
        }
        //else if()
        else {
            colorizer(vergeben_container,'green');
            colorizer(offen_container,'green');
        }
    //Reihe: Verfügbar 

}

function colorizer(container,color) {
    container.style='color:'+color+'!important;';
}

//Functions
    function globalsAnpassen() {
        //Get all Inputs
        var subtract = 0;

        for(var x=0;x<listener.length;x++) {
            if(listener[x].value) {
                subtract+=StringToMin(listener[x].value);
            }
        }
        addTvergeben(subtract);
        substractTMax(subtract);
    }

    function zeileAnpassen() {
        var g_summe = 0; //Gutschein Summe
        var a_summe = 0; //AWE Summe
        //Get Anzahl an Zeien
        var rows = document.getElementsByName('tma');
        for(var x=0;x<rows.length;x++) {
            //Get ID
            var i = rows[x].id.charAt(3);

            //Get Values from g & awe
            var g = document.getElementById('gut'+i).value;
            var a = document.getElementById('awe'+i).value;
            var tv = StringToMin(document.getElementById('tve'+i).innerHTML);
            var tt = StringToMin(document.getElementById('tma'+i).innerHTML);
            var pf = parseInt(document.getElementById('pfl'+i).innerHTML)*60;
            var g_anzahl = parseInt(document.getElementById('seg'+i).innerHTML);
            var a_anzahl = parseInt(document.getElementById('sea'+i).innerHTML);
            if(g=="") {
                g=0;
            }
            else {
                g = StringToMin(g);
            }
            if(a=="") {
                a=0;
            }
            else {
                a = StringToMin(a);
            }
            /**
             * g -> gutscheine MINUTEN
             * a-> awe MINUTEN
             * tv -> Zeit verfügbar MINUTEN
             * tt -> Gesamtzeit der Gruppe MINUTEN
             * pf -> Pflichtschicht MINUTEN
             * g_anzahl -> Anzahl Gutscheine für die Gruppe (INT)
             * a_anzahl -> Stundensatz AWE für die Gruppe (INT)
             */

            //Passe TV an
            tv = tt-(g+a);
            var tv_c='black';
            if(tv<0) {
                tv_c='red';
            }
            else if(tv>0) {
                tv_c='green';
            }
            updateElement('tve'+i,MinToString(tv),tv_c);

            console.log("vergeben: "+getVergeben());
            //Schalte AWE frei? Es Gesamtzahl > pf UND Gutscheinstunden mindestens niedrigstes pf UND Zeit in Gutscheine darf nicht Gesamt-Zeit überschreiten
            if(getVergeben()>=pf && countGutscheine()>=LowestPf() && g<tt) {
                document.getElementById('awe'+i).disabled=false;
            }
            else {
                document.getElementById('awe'+i).value="00:00";
                document.getElementById('awe'+i).disabled=true;
            }
            
            //Berechne Gutscheine & AWE
            var g_new = (g_anzahl * g/60).toFixed(2);
            var a_new = (a_anzahl * a/60).toFixed(2);

            g_summe += g_anzahl * g/60;
            a_summe += a_anzahl * a/60;

            updateElement('azg'+i,g_new,'black');
            updateElement('aza'+i,a_new,'black');
        }
        //Passe Summe an
        g_summe = Math.round(g_summe);
        a_summe = a_summe.toFixed(2);
        var g_summe_container = document.getElementById('gutscheine_summe');
        var a_summe_container = document.getElementById('awe_summe');
        g_summe_container.innerHTML = g_summe;
        a_summe_container.innerHTML = a_summe;
    }

    /**
     * 
     * 
     */
    function updateElement(element_id,value,color) {
        var e=document.getElementById(element_id);  
        e.style='color:'+color+';';
        e.innerHTML = value;
    }

    /**
     * 
     * Reduziert t_max um min Minuten und zeigt aktualisierten Wert an
     */
    function addTvergeben(min) {
        var neu = tvergeben + min;
        if(neu<=tmax){
            tvergebenContainer.style="color:green;";
        }
        else {
            tvergebenContainer.style="color:red";
        }
        tvergebenContainer.innerHTML=MinToString(neu);
    }

    /**
     * 
     * Fügt Subtract zu tVergeben hinzu, zeigt aktualisierten Wert an.
     */
    function substractTMax(min) {
        var neu = tmax - min;
        if(neu>0) {
            tmaxContainer.style="color:green;";
        }
        else if(neu<0) {
            tmaxContainer.style="color:red";
        }
        tmaxContainer.innerHTML=MinToString(neu);
    }



//Helper

/**
 * 
 * converts Minutes to String in format hh:mm
 */
function MinToString(min) {
    var h = Math.trunc(min/60);
    var m = min%60;
    var negative = '';
    if(h.toString().includes('-')) {
        h = h.toString().substring(1,h.length);
        negative='-';
    }
    if(h.toString().length==1) {
        h='0'+h;
    }
    if(m.toString().includes('-')) {
        m = m.toString().substring(1,m.length);
        negative='-';
    }
    if(m.toString().length==1) {
        m='0'+m;
    }

    var out = h+':'+m;
    return negative+out;
}

/**
 * 
 * converts String to Minute (input: format hh:mm)
 */
function StringToMin(str) {
    if(str=="") {
        return 0;
    }
    var h = Math.trunc(parseInt(str.substring(0,2)));
    var m = parseInt(str.substring(3,5));
    var out = h*60 + m;
    return out;
}

/**
 * 
 */
function getVergeben() {
    return StringToMin(tvergebenContainer.innerHTML);
}

/**
 * counts Stunden die für Gutscheine gearbeitet werden/wurden
 */

 function countGutscheine() {
     var g_fields = document.getElementsByName('gut[]');
     var tg = 0;
     for(var x = 0; x<g_fields.length;x++) {
        //if(g_fields[x].value!="") {
            tg+=StringToMin(g_fields[x].value);
        //}
     }
     return tg;
 }

 /**
  * Findet niedrigstes pf (AWE nach...) wert in der Tabelle.
  * Relevant zur AWE Freischaltung.
  */
 function LowestPf() {
    var p_fields = document.getElementsByName('pfl');
    var ps = [];
    for(var x=0;x<p_fields.length;x++) {
        ps.push(parseInt(p_fields[x].innerHTML)*60);
    }
    
    return Math.min.apply(null, ps);
 }