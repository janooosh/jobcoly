var selecterClass = "custom-select d-block w-100";
/*
GET ALL SELECT FIELDS
*/
var student = document.getElementById("student");
var olydorf = document.getElementById("olydorf");
var olycatX = document.getElementById("olycatDiv");
var verein = document.getElementById("vereinDiv");





/*
SET EVENT LISTENERS
*/
student.addEventListener("change", isStudent);
olydorf.addEventListener("change", isOlydorf);
olycatX.addEventListener("change", isOlycat);
verein.addEventListener("change", isVerein);
/*
FORMS THERE?
*/
var olycatStatus = false;
var studentStatus = false;
var olycatXStatus = false;
var adressStatus=false;
var vereinStatus=false;
//FUNCTIONS

function isVerein() {
    var vereinRow = document.getElementById("rowIsVerein");
    var initiator = document.getElementById("vereinSel");
    var initiatorValue = initiator.options[initiator.selectedIndex].value;
    if(vereinStatus==false && initiatorValue == 1) {
        vereinRow.classList.add("mb-3");
        /* START BETRIEB */
        var betriebContainer = document.getElementById("betriebDiv");

        //InnerBetriebContainer

        var innerBetriebContainer = document.createElement("DIV");
        innerBetriebContainer.className="form-check form-check-inline";
        innerBetriebContainer.setAttribute("id","betriebSelect");

        //Betrieb
        var betriebLabel = makeLabel("betriebSelect","Arbeitest du in einem Betrieb?");
        var spacer = document.createElement("br");
        betriebContainer.appendChild(betriebLabel);
        betriebContainer.appendChild(spacer);

        betriebContainer.appendChild(innerBetriebContainer);

        // Bierstube
        var bierstubeInput = makeInput("isBierstube","isBierstube","checkbox",null,"form-check-input",0);
        bierstubeInput.setAttribute("value",1);
        var bierstubeLabel = makeLabel("isBierstube","Bierstube");
        bierstubeLabel.className="form-check-label";
        bierstubeLabel.setAttribute("style","margin-right:20px;");
        innerBetriebContainer.appendChild(bierstubeInput);
        innerBetriebContainer.appendChild(bierstubeLabel);

        // Disco/Lounge
        var discoInput = makeInput("isDisco","isDisco","checkbox",null,"form-check-input",0);
        discoInput.setAttribute("value",1);
        var discoLabel = makeLabel("isDisco","Disco/Lounge");
        discoLabel.className="form-check-label";
        discoLabel.setAttribute("style","margin-right:20px;");
        innerBetriebContainer.appendChild(discoInput);
        innerBetriebContainer.appendChild(discoLabel);

        /*END BETRIEB*/

        /*START Bist du ... */
        var bistduContainer = document.getElementById("bistduDiv");

        //InnerBetriebContainer

        var innerBistduContainer = document.createElement("DIV");
        innerBistduContainer.className="form-check form-check-inline";
        innerBistduContainer.setAttribute("id","bistduSelect");

        //Bisch Du...
        var bistduLabel = makeLabel("bistduSelect","Bist du...");
        var spacer = document.createElement("br");
        bistduContainer.appendChild(bistduLabel);
        bistduContainer.appendChild(spacer);

        bistduContainer.appendChild(innerBistduContainer);

        // Präside
        var prasideInput = makeInput("isPraside","isPraside","checkbox",null,"form-check-input",0);
        prasideInput.setAttribute("value",1);
        var prasideLabel = makeLabel("isPraside","Präside?");
        prasideLabel.className="form-check-label";
        prasideLabel.setAttribute("style","margin-right:20px;");
        innerBistduContainer.appendChild(prasideInput);
        innerBistduContainer.appendChild(prasideLabel);

        // Dauerjobber
        var dauerjobInput = makeInput("isDauerjob","isDauerjob","checkbox",null,"form-check-input",0);
        dauerjobInput.setAttribute("value",1);
        var dauerjobLabel = makeLabel("isDauerjob","Dauerjobber?");
        dauerjobLabel.className="form-check-label";
        dauerjobLabel.setAttribute("style","margin-right:20px;");
        innerBistduContainer.appendChild(dauerjobInput);
        innerBistduContainer.appendChild(dauerjobLabel);
        /* END Bist du... */

        /* START AUSSCHUSS */
        var ausschussContainer = document.getElementById("ausschussDiv");

        var ausschussTexts = ["Kein ordentliches Mitglied","Controlling/Consulting","Fotoclub","Kult","Film & Theater","Gras","Veranstaltungsausschuss","Werkstattausschuss","International Committee of Olydorf","Olympisches Komitee","Finanzausschuss","Töpferausschuss","Kicker-Ausschuss"];
        var ausschussVals = ["0","CTA","FOTO","KULT","FTA","GRAS","VA","WA","ICO","KOMITEE","FA","TA","KICKER"];

        var ausschussLabel = makeLabel("ausschussSelect","Ausschuss...");
        var ausschussSelect = makeSelect("ausschussSelect","ausschussSelect",selecterClass,false,ausschussTexts,ausschussVals);
        ausschussContainer.appendChild(ausschussLabel);
        ausschussContainer.appendChild(ausschussSelect);
        /* END AUSSCHUSS */

        /* START AUSSCHUSS HINWEIS */
        var ausschusshinweisContainer = document.getElementById("ausschusshinweisDiv");

        ausschusshinweisContainer.className = "alert alert-warning";
        var pBreak = document.createElement("BR");
        var pText = document.createTextNode("Ausschuss: Bitte nur ordentliche Ausschussmitglieder, keine außerordentlichen/ehemaligen. Danach werden die Solidaritätsschichten ermittelt.");
        ausschusshinweisContainer.appendChild(pText);
        /* END AUSSCHUSS HINWEIS */
        vereinStatus=true;
    }
    else {
        removeChilds(document.getElementById("betriebDiv"));
        removeChilds(document.getElementById("bistduDiv"));
        removeChilds(document.getElementById("ausschussDiv"));
        removeChilds(document.getElementById("ausschusshinweisDiv"));
        vereinStatus=false;
    }

    if(vereinStatus==false) {
        vereinRow.classList.remove("mb-3");
    }
    
    
}

function makeVereinInfo() {
    var buttonlabel = makeLabel("infotrigger","");
    var clicker = document.createElement("BUTTON");
    clicker.className="btn btn-primary btn-sm";
    clicker.setAttribute("id","infotrigger");
    clicker.setAttribute("data-toggle","modal");
    clicker.setAttribute("data-target","#VereinInfo");
    var t = document.createTextNode("Bin ich Mitglied?");
    clicker.appendChild(t);
    document.getElementById("vereinInfoDivButton").appendChild(buttonlabel);
    document.getElementById("vereinInfoDivButton").appendChild(clicker);



}

function isStudent() {
    var jay = student.value; 
    var rowContainer = document.getElementsByName("ifStudent");
    var ids = ["studiengang","uni","semester"];
    var names = ["studiengang","uni","semester"];
    var types = ["text","select","number"];
    var phs = ["M.Sc. Partyographie","",""];
    var labelTexts = ["Studiengang", "Universität","Semester"];
    var requireds = [false,false,false];

    var names2=["Auswählen...","TU München", "LMU München", "Hochschule München", "Andere"];
    var values2=["none","TUM","LMU","HM","Andere"];

    if(jay==1&&studentStatus==false) {
        var containers = rowContainer;
        //Add space
        for(var x=0;x<containers.length;x++) {
            //Get all the "ifStudent" divs
            var c = containers[x];

            //Set Space
            c.classList.add("mb-3");

            //Label
            var label = makeLabel(ids[x],labelTexts[x]);
            c.appendChild(label);
            var ph = phs[x];

            if(types[x]=="text" || types[x]=="number") {
                var cl = "form-control";
                var feld = makeInput(ids[x],names[x],types[x],ph,cl,requireds[x]);
                c.appendChild(feld);
            }

            else if(types[x] =="select") {
                var feld = makeSelect(ids[x],names[x],selecterClass,requireds[x],names2,values2);
                c.appendChild(feld);
            }
        }
        studentStatus=true;
    }
    else if(jay!=1) {
        var t;
        for(var x=0;x<rowContainer.length;x++) {
            removeChilds(rowContainer[x]);
            rowContainer[x].classList.remove("mb-3");
        }
        studentStatus=false;
    }
}

function isOlycat() {
    var id="olycatDetail";
    var name=id;
    var required=1;
    var type="text";
    var ph = "N 44, A 1122, ..."; 
    var required=1;
    var cl = "form-control";
    var labelText = "Nr. *";
    var currentContainer = document.getElementById("olycat");
    var currentContainerValue = currentContainer.options[currentContainer.selectedIndex].value;
    var newContainer = document.getElementById("olycatForDetails");
    //var containerValue = container.child.getAttribute("value");
    if(currentContainerValue && olycatXStatus==false) {
        //Make Room Selection
        var label = makeLabel(id,labelText);
        var feld = makeInput(id,name,type,ph,cl,required);
        newContainer.appendChild(label);
        newContainer.appendChild(feld);

        //Make Verein Selection
        var labelV = makeLabel("verein","Bist du Mitglied im Verein? *")
        var vals = ["",1,0];
        var texts = ["Auswählen...","Ja","Nein"];
        var feldV = makeSelect("vereinSel","vereinSel",selecterClass,true,texts,vals);
        
        var vereinContainer = document.getElementById("vereinDiv");
        vereinContainer.appendChild(labelV);
        vereinContainer.appendChild(feldV);
        makeVereinInfo();
        olycatXStatus=true;
        
    }
    else if(currentContainerValue!="Hochhaus" && currentContainerValue!="Bungalow" && currentContainerValue!="HJK" ){
        //Delete Room Selection
        removeChilds(document.getElementById("olycatForDetails"));
        removeChilds(document.getElementById("vereinDiv"));
        removeChilds(document.getElementById("vereinInfoDivButton"));
        olycatXStatus=false;
        removeChilds(document.getElementById("betriebDiv"));
        removeChilds(document.getElementById("bistduDiv"));
        removeChilds(document.getElementById("ausschussDiv"));
        removeChilds(document.getElementById("ausschusshinweisDiv"));
        document.getElementById("rowIsVerein").classList.remove("mb-3");
        vereinStatus=false;
        
    }
     /*if (!currentContainerValue && olycatXStatus==true) {
        //Delete Room Selection
        removeChilds(document.getElementById("olycatForDetails"));
        removeChilds(document.getElementById("vereinDiv"));
        removeChilds(document.getElementById("vereinInfoDiv"));
        olycatXStatus=false;

    }*/
}

function isOlydorf() {
    var id="olycat";
    var name="olycat";
    var required = true;
    var names = ["Auswählen...","Hochhaus","Bungalow","HJK"];
    var vals = ["", "Hochhaus", "Bungalow", "HJK"]; //Must match names!

    var container = document.getElementById("olycatDiv");
    var jay = olydorf.value;
    
    //"JA"
    if(jay==1&&olycatStatus==false) {
        var select = makeSelect(id,name,selecterClass,required,names,vals,required);
        container.appendChild(makeLabel(id,"Zimmer *"));
        if(select) container.appendChild(select);
        olycatStatus=true;
        
    }
    //"NEIN"
    else if(jay!=1&&olycatStatus==true) {
        //Remove
        removeChilds(container);
        olycatStatus=false; 
        removeChilds(document.getElementById("olycatForDetails"));
        removeChilds(document.getElementById("vereinDiv"));
        removeChilds(document.getElementById("vereinInfoDivButton"));
        olycatXStatus=false;
        removeChilds(document.getElementById("betriebDiv"));
        removeChilds(document.getElementById("bistduDiv"));
        removeChilds(document.getElementById("ausschussDiv"));
        removeChilds(document.getElementById("ausschusshinweisDiv"));
        document.getElementById("rowIsVerein").classList.remove("mb-3");
        vereinStatus=false;

    }
    if(jay==0 && adressStatus==false) {
        drawAdress();
    }
        if(jay!=0) {
        var kids = document.getElementsByName("ifnoOlydorf");
        for(var x=0;x<kids.length;x++) {
            removeChilds(kids[x]);
        }
        adressStatus=false;
    }

    return;
}


function removeChilds(container) {
    while (container.firstChild) {
        container.removeChild(container.firstChild);
    }
}

function makeLabel (f,text) {
    var label = document.createElement("LABEL");
    label.htmlFor=f;
    label.innerHTML=text;
    
    return label;
}

function makeInput (id, name, type, placeholder, cl, required) {
    var inputer = document.createElement("INPUT");

    inputer.id=id;
    inputer.name=name;
    inputer.type=type;
    inputer.placeholder=placeholder,
    inputer.className=cl;
    if(required==1) {
        inputer.required=true;
    }
    else inputer.required=false;

    return inputer;
}

function makeSelect (id,name,cl,required,optionTexts,optionValues) {
    var selecter = document.createElement("SELECT");
    
    selecter.name=name;
    selecter.id=id;
    selecter.className=cl;
    selecter.required=required;
    var oT = optionTexts.length;
    var oV = optionValues.length;

    //Options
    if(oT!=oV) return false;
    for(var x=0;x<oT;x++) {
        var option = document.createElement("OPTION");
        option.value=optionValues[x];
        option.text=optionTexts[x];
        selecter.add(option);
    }
    return selecter;
}

function drawAdress() {
    var containers = document.getElementsByName("ifnoOlydorf");
    var ids=["strasse","hausnummer","plz","ort","ehemalig"];
    var names=ids;
    var types=["text","text","text","text","select"];
    var phs = ["Spiridon-Louis-Weg","27","80809","München",""];
    var labelTexts = ["Straße *","Nr. *","PLZ *","Ort *","Ehemalige/r?*"];
    var requireds = [1,1,1,1,1];
    var cl="form-control";
    for(var x=0;x<containers.length;x++) {
        var c = containers[x];
        c.classList.add("mb-3");
        var label = makeLabel(ids[x],labelTexts[x]); 
        if(types[x]=="select") {
            var feld=makeSelect(ids[x],names[x],selecterClass,'required',["Bitte auswählen","Ja","Nein"],[0,1,0]);
        }
        else {                  
            var feld=makeInput(ids[x],names[x],types[x],phs[x],cl,requireds[x]);
        }
        c.appendChild(label);
        c.appendChild(feld);
    }
    adressStatus=true;
}