<?php
require_once './app/models/usuario.php';
require_once "./app/mw/AutentificadorJWT.php";
require_once 'IApiUsable.php';

use \App\Models\Usuario as Usuario;

class UsuarioController implements IApiUsable
{
    public function TraerUno($request, $response, $args) {
        $idUsuario=$args['id'];
        $usuario = Usuario::where('id', $idUsuario)->first();
        $payload = json_encode($usuario);
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

    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $clave = $parametros['clave'];
        $mail = $parametros['mail'];
        $sexo = $parametros['sexo'];
        if($parametros['puesto'] == "admin" || $parametros['puesto'] == "vendedor")
        {
            $puesto = $parametros['puesto'];
        }else
        {
            $puesto = "vendedor";
        }
        // Creamos el usuario
        $usuario = new Usuario();
        $usuario->nombre = $nombre;
        $usuario->apellido = $apellido;
        $usuario->mail = $mail;
        $usuario->clave = $clave;
        $usuario->puesto = $puesto;
        $usuario->sexo = $sexo;
        $usuario->save();
        $payload = json_encode(array("mensaje" => "usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $idUsuario = $args['id'];
        $usuario = Usuario::find($idUsuario);
        $usuario->delete();
        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $nombreModificado = $parametros['nombre'];
        $apellidoModificado = $parametros['apellido'];
        $mailModificado = $parametros['mail'];
        $claveModificada = $parametros['clave'];
        $sexoModificado = $parametros['sexo'];
        $puestoModificado = $parametros['puesto'];
        $idUsuario = $parametros['id'];
        // Conseguimos el objeto
        $usuario = Usuario::where('id', '=', $idUsuario)->first();
        // Si existe
        if ($usuario !== null) {
            $usuario->nombre = $nombreModificado;
            $usuario->apellido = $apellidoModificado;
            $usuario->mail = $mailModificado;
            $usuario->clave = $claveModificada;
            $usuario->sexo = $sexoModificado;
            $usuario->puesto = $puestoModificado;
            $usuario->save();
            $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
        } else {
            $payload = json_encode(array("mensaje" => "Usuario no encontrado"));
        }
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');	
    }
    


}

?>