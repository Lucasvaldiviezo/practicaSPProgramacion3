<?php
require_once './app/models/hortaliza.php';
require_once "./app/mw/AutentificadorJWT.php";
require_once 'IApiUsable.php';

use \App\Models\Hortaliza as Hortaliza;

class HortalizaController implements IApiUsable
{
    public function TraerUno($request, $response, $args) {
        $idHortaliza=$args['id'];
        $hortaliza = Hortaliza::where('id', $idHortaliza)->first();
        $payload = json_encode($hortaliza);
        $response->getBody()->write($payload);
        return $response
         ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) {
        $lista = Hortaliza::all();
        $payload = json_encode(array("listaHortalizas" => $lista));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosTipo($request, $response, $args) {
        $tipo = $args['tipo'];
        $lista = Hortaliza::where('tipo', $tipo)->get();
        $payload = json_encode(array("listaHortalizas" => $lista));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $stock = $parametros['stock'];
        if($parametros['tipo'] == "verdura" || $parametros['tipo'] == "fruta")
        {
            $tipo = $parametros['tipo'];
        }else
        {
            $tipo = "verdura";
        }
        // Creamos la Hortaliza
        $hortaliza = new Hortaliza();
        $hortaliza->nombre = $nombre;
        $hortaliza->precio = $precio;
        $hortaliza->stock = $stock;
        $hortaliza->tipo = $tipo;
        $hortaliza->foto = "./app/fotoHortaliza/" . $hortaliza->nombre ."+". $hortaliza->tipo . ".jpg";
        move_uploaded_file($_FILES["foto"]["tmp_name"],$hortaliza->foto);
        $hortaliza->save();
        $payload = json_encode(array("mensaje" => "Hortaliza creada con exito"));

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