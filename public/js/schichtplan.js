//Event Listener
//document.getElementById('oks').addEventListener('change',verstecker);

/*function verstecker() {
    //Get value
    var v = document.getElementById('oks').value;
    if(v.checked) {
        alert("j");
        alert("ho" + document.getElementsByName('frei').length);
        
    }
    else {
        alert("nope");
    }
} */

$('input[id=oks]').change(function(){
    var z = document.getElementsByName('frei');
    if($(this).is(':checked')) {
        for(var x=0;x<z.length;x++) {
            z[x].style.display='none';
        }
    } else {
        for(var x=0;x<z.length;x++) {
            z[x].style.display='';
        }
    }
});

$('input[id=uks]').change(function(){
    var z = document.getElementsByName('ok');
    if($(this).is(':checked')) {
        for(var x=0;x<z.length;x++) {
            z[x].style.display='none';
        }
    } else {
        for(var x=0;x<z.length;x++) {
            z[x].style.display='';
        }
    }
});