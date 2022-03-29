<?php    

    if(!empty($_GET['cep']))
    {

        $cep = $_GET['cep'];

        $mysqli = new mysqli("localhost", "root", "", "banco");

        /* check connection */
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        $stmt = $mysqli->prepare("SELECT * FROM ceps WHERE cep=?");

        $stmt->bind_param("i", $cep);

        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        $stmt->close();

        if(!empty($row))
        {
            //Existe no banco
            $xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
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
            header("HTTP/1.1 200 OK");
            header('Content-type: application/xml');
            echo $xml_data->asXML();
            die();
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
                        header("HTTP/1.1 400 Bad Request");
                        echo "CEP não encontrado";
                        exit;
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

                    $stmt = $mysqli->prepare("INSERT INTO ceps(cep, logradouro, complemento, bairro, localidade, uf) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isssss", $cep, $std['logradouro'], $std['complemento'], $std['bairro'], $std['localidade'], $std['uf']);
                    $stmt->execute();
                    $stmt->close();

                    $xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
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
                    header("HTTP/1.1 200 OK");
                    header('Content-type: application/xml');
                    echo $xml_data->asXML();
                    die();    
                }
            }catch(\Exception $e){
                echo "err: ".$e->getMessage();
            }
            header("HTTP/1.1 400 Bad Request");
            echo "Erro desconhecido";
            exit;    
        }
    }
    header("HTTP/1.1 400 Bad Request");
    echo "CEP vazio";
    exit;
?>