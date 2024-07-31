<?php
    session_start();
    if(!isset($_SESSION['USER'])){
        header('Location: index.php');
    }else{
            
        require ('fpdf/fpdf.php');
        include ('conexion2.php');
        define('EURO',chr(128));


        $fpdf = new FPDF();
        class pdf extends FPDF{
            public function header(){
                $this->setFont('Arial', 'B', 16);
                $this->SetTextColor(12,0,144);
                $this->Cell(200,5,utf8_decode('Nº Factura: '.$_GET['id']),0,0,'C');
                $this->Image('img/profile_pacomuebles.jpeg', 180, 5, 30, 30, 'jpeg');
            }

            public function footer(){
                $this->SetFont('Arial', 'B', 10);
                $this->SetY(-15);
                $this->SetTextColor(12,0,144);
                $this->Write(10, 'PACO MUEBLES #pacomuebilizate');
                $this->SetX(-30);
                $this->AliasNbPages('tpagina');
                $this->Write(10, $this->PageNo().'/tpagina');
            }
        }

        $fpdf = new pdf('P','mm','letter',true);
        $fpdf->AddPage('portarait','letter');


        include_once 'conexion.php';
        $idFactura =$_GET['id'];
        //creamos conexion a la base de datos y la consulta
        $sentencia = $conexion->prepare('SELECT PEDIDOS.ID_PEDIDO, PEDIDOS.ID_PROVEEDOR, PEDIDOS.ID_TIENDA, PEDIDOS.NOMBRE_PROVEEDOR, PEDIDOS.NOMBRE_TIENDA, FACTURAS.ID_FACTURA, FACTURAS.FECHA_EMISION, FACTURAS.IMPORTE_TOTAL FROM PEDIDOS 
                                        JOIN PRODUCTOSxPEDIDOS ON PEDIDOS.ID_PEDIDO = PRODUCTOSxPEDIDOS.ID_PEDIDO 
                                        JOIN FACTURAS ON FACTURAS.ID_PEDIDO = PRODUCTOSxPEDIDOS.ID_PEDIDO 
                                        WHERE FACTURAS.ID_PEDIDO = ? GROUP BY ID_PEDIDO');
        $sentencia->execute([$idFactura]);
        $resultado = $sentencia->fetch(PDO::FETCH_OBJ);

        $total_factura = $resultado->IMPORTE_TOTAL;

        $fpdf->Ln(20);
        $fpdf->SetFont('Arial','',12);
        $fpdf->Cell(20,8,utf8_decode('Nº Pedido:     '.$resultado->ID_PEDIDO));
        $fpdf->Ln();
        $fpdf->Cell(20,8, 'Proveedor:     '.$resultado->ID_PROVEEDOR.' - '.$resultado->NOMBRE_PROVEEDOR);
        $fpdf->Ln();
        $fpdf->Cell(20,8, 'Tienda:     '.$resultado->ID_TIENDA.' - '.$resultado->NOMBRE_TIENDA);
        $fpdf->Ln();
        $fpdf->Cell(20,8, utf8_decode('Fecha Emisión:     '.date("d/m/Y",strtotime($resultado->FECHA_EMISION))));
        $fpdf->Ln();

        $fpdf->SetFont('Arial','B',12);
        $fpdf->SetY(75);
        $fpdf->SetTextColor(12,0,144);
        $fpdf->Cell(0,5,'LISTA DE PRODUCTOS',0,0,'C');
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Ln(10);

        $fpdf->SetFontSize(10);
        $fpdf->SetFont('Arial', 'B');
        $fpdf->SetFillColor(11,63,71);
        $fpdf->SetTextColor(255,255,255);
        $fpdf->SetDrawColor(88,88,88);
        $fpdf->Cell(25,5,utf8_decode('Nº Producto'), 0, 0, 'C', 1);
        $fpdf->Cell(90,5,'Producto', 0, 0, 'C', 1);
        $fpdf->Cell(20,5,'Cantidad', 0, 0, 'C', 1);
        $fpdf->Cell(20,5,'P. Ud.', 0, 0, 'C', 1);
        $fpdf->Cell(20,5,'IVA', 0, 0, 'C', 1);
        $fpdf->Cell(20,5,'P. Total', 0, 1, 'C', 1);


        //creamos conexion a la base de datos y la consulta
        include_once 'conexion2.php';

        $consulta = "SELECT * FROM PRODUCTOSxPEDIDOS WHERE ID_PEDIDO = $idFactura";
        $resultado = mysqli_query($conexion2,$consulta);
        while($fila=$resultado->fetch_assoc()){
            $fpdf->SetFillColor(255, 217, 66);
            $fpdf->SetTextColor(0,0,0);
            $fpdf->Cell(25,5,utf8_decode($fila['ID_PRODUCTO']),0,0,'C',1);
            $fpdf->Cell(90,5,utf8_decode($fila['NOMBRE_PRODUCTO']),0,0,'C',1);
            $fpdf->Cell(20,5,utf8_decode($fila['CANTIDAD']).'uds.',0,0,'C',1);
            $fpdf->Cell(20,5,utf8_decode(number_format($fila['PRECIO_PRODUCTO'],2)),0,0,'C',1);
            $fpdf->Cell(20,5,utf8_decode($fila['IVA'].'%'),0,0,'C',1);
            $fpdf->Cell(20,5,utf8_decode(number_format($fila['PRECIO'],2)),0,1,'C',1);
        }
        $fpdf->SetFillColor(255, 217, 66);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Cell(335,15,utf8_decode('IMPORTE A PAGAR: '.number_format($total_factura,2).' e'),0,0,'C');

        $fpdf->SetTextColor(0,0,0);
        $fpdf->Output("PM_Fac".$_GET['id'].'.pdf','D');

    }
?>