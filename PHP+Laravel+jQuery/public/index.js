function SearchCEP()
{
    //Ler CEP e remover caracteres que não são numeros
    let cep = $("#cep_field").val();
    cep = cep.replace(/[^\d]/g, "");
    
    var request = $.ajax( `/api?cep=${cep}` )
    .done(function(xmlDoc) {
        console.log(xmlDoc);
        $("#status_label").html("<i class='fas fa-check'></i> CEP encontrado!");
        $("#search_button").html("<i class='fas fa-fw fa-search'></i>");    
        
        $("#info_modal_title").html("<i class='fas fa-check-circle'></i> CEP encontrado!");          
        $("#info_modal_body").html(`
        <p style='text-align: center'><i style='color: green' class='fas fa-fw fa-3x fa-check-circle'></i></p>
        CEP encontrado, abaixo seguem os detalhes<hr>
        CEP: ${ (xmlDoc.getElementsByTagName("cep")[0].childNodes[0] === undefined ? "" : xmlDoc.getElementsByTagName("cep")[0].childNodes[0].nodeValue) }<br>
        Estado: ${ (xmlDoc.getElementsByTagName("uf")[0].childNodes[0] === undefined ? "" : xmlDoc.getElementsByTagName("uf")[0].childNodes[0].nodeValue) }<br>
        Cidade: ${ (xmlDoc.getElementsByTagName("localidade")[0].childNodes[0] === undefined ? "" : xmlDoc.getElementsByTagName("localidade")[0].childNodes[0].nodeValue) }<br>
        Bairro: ${ (xmlDoc.getElementsByTagName("bairro")[0].childNodes[0] === undefined ? "" : xmlDoc.getElementsByTagName("bairro")[0].childNodes[0].nodeValue) }<br>
        Logradouro: ${ (xmlDoc.getElementsByTagName("logradouro")[0].childNodes[0] === undefined ? "" : xmlDoc.getElementsByTagName("logradouro")[0].childNodes[0].nodeValue) }<br>
        <br>
        Referencia (XML):<br>
        <textarea class="w-100" rows="6">${new XMLSerializer().serializeToString(xmlDoc)}</textarea>`);

        $("#info_modal").modal("show");
    })
    .fail(function(data) {
        //Houve um erro.
        $("#status_label").html("<i class='fas fa-times-circle'></i> Erro buscando: " + data);
        $("#search_button").html("<i class='fas fa-fw fa-search'></i>");  

        $("#info_modal_title").html("<i class='fas fa-times-circle'></i> Erro na busca");          
        $("#info_modal_body").html("<p style='text-align: center'><i style='color: red' class='fas fa-fw fa-3x fa-times-circle'></i></p>Não foi possivel localizar este CEP, confira os dados e tente novamente.<br><br>Referencia: " + data);          

        $("#info_modal").modal("show");
    })
    .always(function() {
        $("#search_button").prop("disabled", false);
        $("#cep_field").prop("disabled", false);
        $("#search_button").html("<i class='fas fa-fw fa-search'></i>");        
    });

    $("#status_label").html("<i class='fas fa-spin fa-spinner'></i> Buscando CEP...");
    $("#search_button").html("<i class='fas fa-fw fa-spin fa-spinner'></i>");

    $("#search_button").prop("disabled", true);
    $("#cep_field").prop("disabled", true);
}