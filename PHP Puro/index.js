function SearchCEP(element)
{
    //Ler CEP e remover caracteres que não são numeros
    let cep = document.getElementById("cep_field").value;
    cep = cep.replace(/[^\d]/g, "");
    
    //Fazer a requisição AJAX e atualizar a UI
    let xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) 
        {
            // Request concluiu com sucesso!
            if (xmlhttp.status == 200) {
                console.log(xmlhttp.responseText);
                //document.getElementById("myDiv").innerHTML = xmlhttp.responseText;
                document.getElementById("status_label").innerHTML = "<i class='fas fa-check'></i> CEP encontrado!";
                document.getElementById("search_button").innerHTML = "<i class='fas fa-fw fa-search'></i>";    
                
                let parser = new DOMParser();
                let xmlDoc = parser.parseFromString(xmlhttp.responseText,"text/xml");

                //xmlDoc.getElementsByTagName("title")[0].childNodes[0].nodeValue;

                document.getElementById("info_modal_title").innerHTML = "<i class='fas fa-check-circle'></i> CEP encontrado!";          
                document.getElementById("info_modal_body").innerHTML = `
                <p style='text-align: center'><i style='color: green' class='fas fa-fw fa-3x fa-check-circle'></i></p>
                CEP encontrado, abaixo seguem os detalhes<hr>
                CEP: ${ (xmlDoc.getElementsByTagName("cep")[0].childNodes[0] === undefined ? "" : xmlDoc.getElementsByTagName("cep")[0].childNodes[0].nodeValue) }<br>
                Estado: ${ (xmlDoc.getElementsByTagName("uf")[0].childNodes[0] === undefined ? "" : xmlDoc.getElementsByTagName("uf")[0].childNodes[0].nodeValue) }<br>
                Cidade: ${ (xmlDoc.getElementsByTagName("localidade")[0].childNodes[0] === undefined ? "" : xmlDoc.getElementsByTagName("localidade")[0].childNodes[0].nodeValue) }<br>
                Bairro: ${ (xmlDoc.getElementsByTagName("bairro")[0].childNodes[0] === undefined ? "" : xmlDoc.getElementsByTagName("bairro")[0].childNodes[0].nodeValue) }<br>
                Logradouro: ${ (xmlDoc.getElementsByTagName("logradouro")[0].childNodes[0] === undefined ? "" : xmlDoc.getElementsByTagName("logradouro")[0].childNodes[0].nodeValue) }<br>
                <br>
                Referencia (XML):<br>
                <textarea class="w-100" rows="6">${xmlhttp.responseText}</textarea>`;

                ShowModal();
            }
            else {
                //Houve um erro.
                document.getElementById("status_label").innerHTML = "<i class='fas fa-times-circle'></i> Erro buscando: " + xmlhttp.responseText;
                document.getElementById("search_button").innerHTML = "<i class='fas fa-fw fa-search'></i>";  

                document.getElementById("info_modal_title").innerHTML = "<i class='fas fa-times-circle'></i> Erro na busca";          
                document.getElementById("info_modal_body").innerHTML = "<p style='text-align: center'><i style='color: red' class='fas fa-fw fa-3x fa-times-circle'></i></p>Não foi possivel localizar este CEP, confira os dados e tente novamente.<br><br>Referencia: " + xmlhttp.responseText;          

                ShowModal();
            }
            document.getElementById("search_button").removeAttribute("disabled");
            document.getElementById("cep_field").removeAttribute("disabled");
            document.getElementById("search_button").innerHTML = "<i class='fas fa-fw fa-search'></i>";        
        }
    };

    xmlhttp.open("GET", `/api.php?cep=${cep}`, true);
    xmlhttp.send();

    document.getElementById("status_label").innerHTML = "<i class='fas fa-spin fa-spinner'></i> Buscando CEP...";
    document.getElementById("search_button").innerHTML = "<i class='fas fa-fw fa-spin fa-spinner'></i>";

    document.getElementById("search_button").setAttribute("disabled", true);
    document.getElementById("cep_field").setAttribute("disabled", true);
}

function ShowModal()
{
    let modal = document.getElementById("info_modal");
    let modal_dialog = document.getElementById("info_modal_dialog");

    const backdrop = document.createElement('div');
    backdrop.classList.add('modal-backdrop', 'fade', 'show');
    document.body.classList.add('modal-open');
    document.body.appendChild(backdrop);
    modal.style.display = 'block';
    modal.setAttribute('aria-hidden', 'false', 'show');
    modal.classList.add('show');
    modal_dialog.classList.add('show');
}

function HideModal() 
{
    let modal = document.getElementById("info_modal");
    let modal_dialog = document.getElementById("info_modal_dialog");

    const backdrop = document.querySelector('.modal-backdrop.fade.show');
    document.body.classList.remove('modal-open');
    modal.setAttribute('aria-hidden', 'true');
    backdrop.classList.remove('show');
    modal.classList.remove('show');
    modal_dialog.classList.remove('show');
    modal.style.display = 'none';
    backdrop.remove();
}