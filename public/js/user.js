
$('input[id=pflichtler]').change(function(){
    var z = document.getElementsByName('pflicht0');
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