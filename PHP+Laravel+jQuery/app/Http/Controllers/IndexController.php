<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function api(Request $request)
    {
        if(!empty($request->query('cep')))
        {
            $cep = $request->query('cep');
            $row = DB::table("ceps")->where("cep", $cep)->first();

            if(!empty($row))
            {
                //Existe no banco
                $xml_data = new \SimpleXMLElement('<?xml version="1.0"?><data></data>');
                foreach( $row as $key => $value )
                {
                    if( is_array($value) ) {
                        if( is_numeric($key) ){
                            $key = 'item'.$key; //dealing with <0/>..<n/> issues
                        }
                        $subnode = $xml_data->addChild($key);
                        array_to_xml($value, $subnode);
                    } else {
                        $xml_data->addChild("$key",htmlspecialchars("$value"));
                    }
                }
                return response($xml_data->asXML(), 200)->header('Content-Type', 'application/xml');
            }else{
                //Consultar viacep

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "https://viacep.com.br/ws/$cep/xml/");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $output = curl_exec($ch);

                if (!curl_errno($ch))
                {
                    switch (curl_getinfo($ch, CURLINFO_HTTP_CODE))
                    {
                        case 200:  # OK
                            break;
                        default:
                            return response("CEP não encontrado", 400);
                            break;
                    }
                }
                
                curl_close($ch);     

                try{
                    $xmlparser = xml_parser_create();
                    xml_parse_into_struct($xmlparser,$output,$values);
                    xml_parser_free($xmlparser);            

                    $std = array();
                    $std['cep'] = $cep;
                    
                    foreach($values as $value)
                    {
                        switch($value['tag'])
                        {
                            case "LOGRADOURO":
                                $std['logradouro'] = (empty($value['value']) ? "" : $value['value']);
                                break;
                            case "COMPLEMENTO":
                                $std['complemento'] = (empty($value['value']) ? "" : $value['value']);
                                break;
                            case "BAIRRO":
                                $std['bairro'] = (empty($value['value']) ? "" : $value['value']);
                                break;
                            case "LOCALIDADE":
                                $std['localidade'] = (empty($value['value']) ? null : $value['value']);
                                break;
                            case "UF":
                                $std['uf'] = (empty($value['value']) ? null : $value['value']);
                                break;
                        }
                    }

                    //Confere se a cidade está preenchida (algumas cidades usam o mesmo CEP para todas as ruas)
                    if(!empty($std['localidade']))
                    {
                        //Gravar no banco

                        DB::table("ceps")
                        ->insert([
                            "cep" => $cep, 
                            "logradouro" => $std['logradouro'], 
                            "complemento" => $std['complemento'], 
                            "bairro" => $std['bairro'],
                            "localidade" => $std['localidade'], 
                            "uf" => $std['uf']
                        ]);

                        $xml_data = new \SimpleXMLElement('<?xml version="1.0"?><data></data>');
                        foreach( $std as $key => $value )
                        {
                            if( is_array($value) ) {
                                if( is_numeric($key) ){
                                    $key = 'item'.$key; //dealing with <0/>..<n/> issues
                                }
                                $subnode = $xml_data->addChild($key);
                                array_to_xml($value, $subnode);
                            } else {
                                $xml_data->addChild("$key",htmlspecialchars("$value"));
                            }
                        }
                        return response($xml_data->asXML(), 200)->header('Content-Type', 'application/xml');
                    }
                }catch(\Exception $e){
                    echo "err: ".$e->getMessage();
                }
                return response("Erro desconhecido", 400);
            }
        }
        return response("CEP vazio", 400);
    }
}
