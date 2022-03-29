<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CD2TEC@PHPTEST</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.0/css/all.min.css" integrity="sha512-3PN6gfRNZEX4YFyz+sIyTF6pGlQiryJu9NlGhu9LrLMQ7eDjNgudQoFDK3WSNAayeIKc6B8WXXpo4a7HqxjKwg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="/index.js"></script>
    </head>
    <body>
        <nav id="navbar" class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="ms-2 navbar-brand" href="/"><i class="fas fa-fw fa-check"></i> cd2tec @ PHPTest</a>
        </nav>
        <div id="app" class="container-fluid mt-3">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-fw fa-search"></i> Buscar endereço
                        </div>
                        <div class="card-body">
                            <label class="w-100">
                                Pesquisar endereço por CEP:
                                <div class="input-group">
                                    <input class="form-control" type="text" name="cep" id="cep_field">
                                    <button type="button" id="search_button" onclick="SearchCEP();" class="btn btn-primary input-group-append"><i class="fas fa-fw fa-search"></i></button>
                                </div>
                                <span id="status_label"><i class="fas fa-arrow-right"></i> Digite um CEP e aperte o botão para começar.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="info_modal" class="modal" tabindex="-1" role="dialog">
            <div id="info_modal_dialog" class="modal-dialog show fade" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="info_modal_title" class="modal-title">undefined</h5>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="fas fa-fw fa-times"></i></button>
                    </div>
                    <div id="info_modal_body" class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>