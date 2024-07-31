$('document').ready(function() {
    $('#btn_add_producto').click(function(e) {
        e.preventDefault();
        $('.addsection').append("<div class='form-group col-md-3'> <div class='form-group'> <label for='productos'>Seleccione producto:</label>  <select class='form-select container' multiple name='productos[]' required>  <?php  foreach ($resultado as $datos){ ?> <option value=<?php echo $datos->ID_PRODUCTO ?>><?php echo $datos->ID_PRODUCTO.' '.$datos->NOMBRE_PRODUCTO ?></option>   <?php }  ?>  </select>  </div>  </div>");
    })
});