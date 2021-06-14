<?php
require_once './app/models/venta.php';
require_once './app/models/cliente.php';
require_once "./app/mw/AutentificadorJWT.php";
require_once 'IApiUsable.php';

use \App\Models\Hortaliza as Hortaliza;
use \App\Models\Usuario as Usuario;
use \App\Models\Venta as Venta;
use \App\Models\Cliente as Cliente;

class VentaController implements IApiUsable
{
    public function TraerUno($request, $response, $args) {
        $idVenta=$args['id'];
        $hortaliza = Venta::where('id', $idVenta)->first();
        $payload = json_encode($hortaliza);
        $response->getBody()->write($payload);
        return $response
         ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) {
        $lista = Venta::all();
        $payload = json_encode(array("listaVentas" => $lista));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $idHortaliza = $parametros['idHortaliza'];
        $idCliente = $parametros['idCliente'];
        $cantidad = $parametros['cantidad'];
        $fechaVenta = date("Y-m-d");
        //Obtenemos usuario
        $header = $request->getHeaderLine('Authorization');
	    $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);	
        $usuario = Usuario::where('mail','=', $data->usuario)->first();
        if($usuario != null)
        {
            $cliente = Cliente::where('id', $idCliente)->first();
            if($cliente !=null)
            {
                $idHortaliza=$parametros['idHortaliza'];
                $hortaliza = Hortaliza::where('id', $idHortaliza)->first();
                if($hortaliza != null && $hortaliza->stock >= $cantidad)
                {
                    // Creamos la venta
                    $hortaliza->stock = $hortaliza->stock - $cantidad;
                    $ruta = $hortaliza->nombre. "+" .$hortaliza->tipo .".jpg";
                    $hortaliza->save();
                    $venta = new Venta();
                    $venta->id_hortaliza = $idHortaliza;
                    $venta->id_empleado = $usuario->id;
                    $venta->id_cliente = $idCliente;
                    $venta->cantidad = $cantidad;
                    $venta->total ="$" . $hortaliza->precio * $cantidad;
                    $venta->fecha_venta = $fechaVenta;
                    $venta->foto = "./app/fotoVentas/" . $hortaliza->id ."+". $cliente->nombre . ".jpg";
                    copy($hortaliza->foto,$venta->foto);
                    $venta->save();
                    $payload = json_encode(array("mensaje" => "Venta creada con exito"));
                }else
                {
                    $payload = json_encode(array("mensaje" => "No existe la hortaliza o no hay stock"));
                }
            }else
            {
                $payload = json_encode(array("mensaje" => "No existe el cliente"));
            }
            
        }else
        {
            $payload = json_encode(array("mensaje" => "No existe el empleado"));
        }
        

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $idHortaliza = $args['id'];
        $hortaliza = Hortaliza::find($idHortaliza);
        $ruta = substr($hortaliza->foto,20);
        rename($hortaliza->foto, "./app/fotoHortaliza/(deleted)".$ruta);
        $hortaliza->foto = "./app/fotoHortaliza/(deleted)".$ruta;
        $hortaliza->save();
        $hortaliza->delete();
        $payload = json_encode(array("mensaje" => "Hortaliza borrado con exito"));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $nombreModificado = $parametros['nombre'];
        $precioModificado = $parametros['precio'];
        $stockModificado = $parametros['stock'];
        if($parametros['tipo'] == "verdura" || $parametros['tipo'] == "fruta")
        {
            $tipoModificado = $parametros['tipo'];
        }else
        {
            $tipoModificado = "verdura";
        }
        $idHortaliza = $parametros['id'];
        // Conseguimos el objeto
        $hortaliza = Hortaliza::where('id', '=', $idHortaliza)->first();
        $ruta = substr($hortaliza->foto,20);
        copy($hortaliza->foto, "./app/fotoHortaliza/backup/".$ruta);
        
        // Si existe
        if ($hortaliza !== null) {
            $hortaliza->nombre = $nombreModificado;
            $hortaliza->precio = $precioModificado;
            $hortaliza->stock = $stockModificado;
            $hortaliza->tipo = $tipoModificado;
            rename($hortaliza->foto, "./app/fotoHortaliza/". $hortaliza->nombre ."+". $hortaliza->tipo . ".jpg");
            $hortaliza->foto = "./app/fotoHortaliza/" . $hortaliza->nombre ."+". $hortaliza->tipo . ".jpg";
            $hortaliza->save();
            $payload = json_encode(array("mensaje" => "Hortaliza modificada con exito"));
        } else {
            $payload = json_encode(array("mensaje" => "Hortaliza no encontrada"));
        }
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');	
    }
}

?>