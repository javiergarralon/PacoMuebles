var meses = new Array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
var f = new Date();
var dia = parseInt(f.getDate());
if (dia < 10) {
    dia = '0' + dia;
}
document.write(dia + "/" + meses[f.getMonth()] + "/" + f.getFullYear());