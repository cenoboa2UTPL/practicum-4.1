<?php 
namespace Http\controllers;
use FPDF;
use Http\pageextras\PageExtra;
use lib\BaseController;
use models\Plan_Atencion;
class RecetaController extends BaseController
{
    /// generar reporte 
    private static $Model;
    public static function informe_receta_medica()
    {
        self::NoAuth();
        
       if(isset($_GET['v']) and self::profile()->rol === self::$profile[3])
       {
        self::$Model = new Plan_Atencion;
        $RecetaDetalle = self::$Model->procedure("proc_receta_show","C",[self::get("v")]);

        if(count($RecetaDetalle) > 0)
        {
            $receta = new FPDF('P','mm',array(130,210));
            $receta->SetTitle("Receta médica - ".$RecetaDetalle[0]->paciente,1);
            $receta->AddPage();/// Añadimos una nueva página
            $receta->SetY(10);
            $receta->SetX(6);
            $receta->SetDrawColor(112, 128, 144);
            $receta->SetFillColor(240, 248, 255);
            $receta->SetTextColor(0,0,0);
            $receta->SetFont("Arial","B",12);
            $receta->Cell(118,10,utf8__('Receta médica'),1,1,'C',1);
            $receta->SetY(20);
            $receta->SetX(6);
            $receta->SetFont("Arial","",10);
            $receta->Cell(35,23,$receta->Image("public/asset/img/essalud.png",6,20,35,23),1,0,'C');
            $receta->SetFont("Arial","B",16);
            $receta->SetTextColor(0,0,0);
            $receta->Cell(53,23,utf8__('EsSalud-Carhuaz'),1,1,'C');
            $receta->SetFont("Arial","",13);
            $receta->SetY(20);
            $receta->SetX(94);
            $receta->Cell(30,23,str_replace("-","/",self::FechaActual("d-m-Y")),1,1,'C');
    
            $receta->SetFont("Arial","B",10);
            $receta->SetY(43);
    
    
            $receta->SetX(6);
            $receta->Cell(35,7,'Especialista',1,0,'L');
            $receta->SetFont("Arial","",10);
            $receta->Cell(83,7,utf8__($RecetaDetalle[0]->medico_atencion),1,1,'L');
            $receta->SetX(6);
            $receta->SetTextColor(0,0,0);
            $receta->SetFont("Arial","B",10);
            $receta->Cell(35,7,utf8__('Paciente'),1,0,'L');
            $receta->SetFont('Arial','',10);
            $receta->Cell(83,7,utf8__($RecetaDetalle[0]->paciente),1,1,'L');
            $receta->SetX(6);
            $receta->SetFont("Arial","B",10);
            $receta->Cell(35,7,utf8__('Fecha atención'),1,0,'L');
            $receta->SetFont("Arial","",10);
            $receta->Cell(83,7,self::getFechaText($RecetaDetalle[0]->fecha_atencion).'          '.$RecetaDetalle[0]->horacita,1,1,'L');
            $receta->SetFont("Arial","B",10);
            $receta->SetX(6);
            $receta->SetFont("Arial",'B',10);
            $receta->Cell(118,7,'Tratamiento',1,1,'L');
            $receta->SetX(6);
            $receta->SetFont('Arial','',10);
            $receta->MultiCell(118,7,utf8__(str_replace("\n"," - ",$RecetaDetalle[0]->desc_plan)),1);
            $receta->SetFont("Arial","B",10);
            $receta->SetX(6);
            $receta->Cell(35,7,'Plan de tratamiento ',1,0,'L');
            $receta->SetFont("Arial","",10);
            $receta->SetFillColor(105, 105, 105);
            $receta->Cell(83,7,$RecetaDetalle[0]->plan_tratamiento,1,1,'L');
            $receta->SetFont("Arial","B",10);
            $receta->SetX(6);
            $receta->SetFont("Arial","B",10);
            $receta->Cell(118,7,utf8__('Descripción de los exámenes'),1,1,'L');
            $receta->SetX(6);
            $receta->SetFont("Arial","",8);
            $receta->MultiCell(118,7,utf8__($RecetaDetalle[0]->desc_analisis_requerida == null?'----------------------------------------------------------------------------':$RecetaDetalle[0]->desc_analisis_requerida),1);

            $receta->SetX(6);
    
            $receta->SetFont("Arial","B",10);
            $receta->Cell(118,7,utf8__("Próxima cita"),1,0,"L");

            $receta->SetX(30);
            $receta->SetFont("Arial","",10);
            $receta->Cell(94,7,self::getFechaText($RecetaDetalle[0]->proxima_cita_medica),1);

            $receta->Ln();
            $receta->SetX(6);
            $receta->SetFont("Arial","B",10);
            $receta->Cell(118,7,'Productos recetados',1,1,'L');
            $receta->SetFont("Arial","I",7);
            $receta->SetX(6);
    
            $recetaDet = '';
            
            foreach($RecetaDetalle as $rec)
            {
               $recetaDet.='* '.$rec->medicamento.PHP_EOL.$rec->dosis.PHP_EOL.'Cantidad : '.$rec->cantidad.PHP_EOL;
            }
            $receta->MultiCell(118,5,utf8__($recetaDet).
            '---------------------------------------------------------------------------------------------------------------------------------------------'.PHP_EOL.'TIEMPO TRATAMIENTO : '.$RecetaDetalle[0]->tiempo_dosis.PHP_EOL,1);
            // Arial italic 
            $receta->Ln(11);
            $receta->SetFont('Arial','B',10);
            $receta->SetDrawColor(105, 105, 105);
            $receta->Cell(0,10,'_____________________________________',0,1,'C');
            $receta->SetFont("Arial","B",10);
            $receta->Cell(0,0,utf8__("Firma Dr. ".$RecetaDetalle[0]->medico_atencion),0,1,'C');
            
    #'D', "Receta médica - ".$RecetaDetalle[0]->paciente.".pdf", true 
            $receta->Output();/// mostramos el pdf
        }
        else
        {
            PageExtra::PageNoAutorizado();
        }
       }
       else
       {
        PageExtra::PageNoAutorizado();
       }
    }
}