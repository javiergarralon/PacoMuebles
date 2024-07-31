function hora_act() {
    var h = new Date();
    var hora = parseInt(h.getHours());
    var min = parseInt(h.getMinutes());

    if (hora < 10) {
        hora = '0' + h.getHours();
    } else {
        hora = h.getHours();
    }
    if (min < 10) {
        min = '0' + h.getMinutes();
    } else {
        min = h.getMinutes();
    }
    horaImprimir = hora + ":" + min;
    document.form_reloj.reloj.value = horaImprimir;
    setTimeout("hora_act()", 500);
}