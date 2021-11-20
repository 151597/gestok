<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\TipoAtivo;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use \PDF;


class QrCodeController extends Controller
{
    public function qrCode(){

        $loadAtivo = DB::table('objeto_ativo')
        ->select('id_objeto', 'numero_serie', 'descricao', 'patrimonio')
        ->whereNull('deleted_at')
        ->get();

        return view("qrCodeView", ['loadAtivo' => $loadAtivo]);
    }

    public function qrCodePDF(Request $request){

        $tamQr = $request->input('tamQr')*3.779;
        $qntPorLinha = $request->input('qntPorLinha');
        $tamFonte = $request->input('tamFonte');
        $info = $request->input('info');
        $textoQr = $request->input('textoQr');
        $qrAtivo = $request->input('qrAtivo');

        $loadAtivo = DB::table('objeto_ativo')
        ->select('numero_serie', 'descricao', 'patrimonio')
        ->whereIn('id_objeto', $qrAtivo)
        ->get();

        $infoQRCode = [];

        foreach($loadAtivo as $ativo){
            $strAtivos = "";
            $count = 1;
            foreach($info as $in){
                if($count < sizeof($info) && !empty($strAtivos)){
                    $strAtivos = $strAtivos." - ".$ativo->$in;
                }else{
                    $strAtivos = $ativo->$in;
                }
            }
            if(!empty($textoQr)){
                array_push($infoQRCode, $strAtivos." - ".$textoQr);
            }else{
                array_push($infoQRCode, $strAtivos);
            }
            
        }
                
        
        return \PDF::loadView("qrCodePdf",[
            'tamQr' => $tamQr,
            'qntPorLinha' => $qntPorLinha,
            'infoQRCode' => $infoQRCode,
            'tamFonte' => $tamFonte,
        ])
        
        ->stream('qrcodes.pdf');
        //return view("qrCode",['cod' => $cod]);
    }

    
}

//https://shouts.dev/generate-qr-code-in-laravel