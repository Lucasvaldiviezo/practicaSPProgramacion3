<?php

require_once './app/models/venta.php';
require_once './app/models/cliente.php';
require_once "./app/mw/AutentificadorJWT.php";

use \App\Models\Hortaliza as Hortaliza;
use \App\Models\Usuario as Usuario;
use \App\Models\Venta as Venta;
use \App\Models\Cliente as Cliente;

class Estadisticas{
    
    public function TraerVentasEmpleado($request, $response, $args) {
        $idEmpleado = $args['id'];
        $empleado = Usuario::where('id', '=', $idEmpleado)->first();
        $lista = Venta::where('id_empleado',"=", $idEmpleado)->get();
        $listaJson = json_decode(json_encode(array("listaVentas" => $lista)));
        
        echo '-El empleado '. $empleado->id ." ". $empleado->nombre . " ". $empleado->apellido . " realizo estas ventas: \n";
        foreach($listaJson->listaVentas as $venta)
        {
            echo " ID: " . $venta->id . " | Cliente: " . $venta->id_cliente . " | Hortaliza: " . $venta->id_hortaliza . " | Cantidad: " . $venta->cantidad . " | Total: " . $venta->total . " | Fecha " . $venta->fecha_venta . "\n";
        }
    }

    public function TraerHortalizaMasVendida($request, $response, $args) {
        $lista = Venta::all();
        $listaJson = json_decode(json_encode(array("listaVentas" => $lista)));
        $listaHortalizas = [];
        foreach($listaJson->listaVentas as $venta)
        {
            if(!array_key_exists($venta->id_hortaliza,$listaHortalizas))
            {
                $listaHortalizas[$venta->id_hortaliza] = $venta->cantidad;
            }else
            {
                $listaHortalizas[$venta->id_hortaliza] += $venta->cantidad;
            }
        }
        $cantMayor = 0;
        $hortalizaMayor = 0;
        ksort($listaHortalizas);
        foreach($listaHortalizas as $idHortaliza=>$cant)
        {
            if($cant > $cantMayor)
            {
                $cantMayor = $cant;
                $hortalizaMayor = $idHortaliza;
            }
        }
        $hortaliza = Hortaliza::where('id','=',$hortalizaMayor)->first();
        echo 'La horataliza que mas vendio es la: ' . $hortaliza->id . " " . $hortaliza->nombre . " " . $hortaliza->tipo . " con una cantidad de $cantMayor";
    }
}

?>