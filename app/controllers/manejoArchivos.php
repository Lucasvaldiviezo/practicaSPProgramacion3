<?php

require_once './app/models/venta.php';
require_once './app/models/cliente.php';
require_once "./app/mw/AutentificadorJWT.php";
require_once './app/pdf/fpdf.php';

use \App\Models\Hortaliza as Hortaliza;
use \App\Models\Usuario as Usuario;
use \App\Models\Venta as Venta;
use \App\Models\Cliente as Cliente;

class ManejoArchivos
{

    public function GuardarDatos($request, $response, $next)
    {
        $parametros = $request->getParsedBody();
        $formato=$parametros['formato'];
        if($formato == "pdf")
        {
            $id = $parametros['id'];
        }
        if($formato == 'csv')
        {
            $bool = $this->GuardarCSV($request,$response,$next);
        }else if($formato == 'pdf')
        {
            $bool = $this->GuardarPDF($request,$response,$next,$id);
        }
    }

    public function GuardarCSV($request,$response,$next)
    {
        $lista = Hortaliza::where('stock','>',0)->get();
        $hortalizas = json_encode(array("listaCompleta" => $lista));
        $archivo = fopen("./app/archivos/hortalizas.csv","a");
        $bool = fwrite($archivo, $this->DatosToCSV($hortalizas));
        $payload = json_encode(array("mensaje" => "Se guardo el CSV de empleados"));
        fclose($archivo);
        if($bool == false)
        {
            $payload = json_encode(array("mensaje" => "No se guardo el archivo"));
        }

        $response->getBody()->write($payload);
        return $bool;
    }

    public function GuardarPDF($request,$response,$next,$id)
    {
        $bool = false;
        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,15,"Hortaliza","0","1","C");
        $hortaliza = Hortaliza::where('id', $id)->first();
        if($hortaliza != null)
        {
            $pdf->SetFont('Arial','',10);
            $datos = $this->DatosToPDF($hortaliza);
            $datos = iconv('UTF-8', 'windows-1252', $datos);
            $pdf->MultiCell(0,4,$datos,"0","L");
            if($datos != null)
            {
                $payload = json_encode(array("mensaje" => "Se guardo el PDF"));
                $pdf->Output('F',"./app/archivos/hortalizaId".'.pdf',true);
                $bool = true;
            }else
            {
                $payload = json_encode(array("mensaje" => "No se guardo el PDF"));
            }
        }else
        {
            $payload = json_encode(array("mensaje" => "No existe la hortaliza"));
        }
        $response->getBody()->write($payload);
        return $bool;
    }

    public function DatosToCSV($datos)
    {
        $lista = json_decode($datos);
        $cadena = "";
        foreach($lista->listaCompleta as $dato)
        {
            $cadena .= "{" . $dato->id . "," . $dato->nombre . "," . $dato->precio . "," . $dato->stock . "," . $dato->tipo . "," . $dato->foto . "}" . ",\n";
        }
        return $cadena;  
    }

    public function DatosToPDF($datos)
    {
        $cadena = "";
        $cadena .= "- ID: " . $datos->id . ", Nombre: " . $datos->nombre . ", Precio: " . $datos->precio . ", Stock: " . $datos->stock . ", Tipo: " . $datos->tipo . ", Foto: " . $datos->foto;
        return $cadena;    
    }
}
?>