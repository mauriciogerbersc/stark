@extends('layout.app')

@section('conteudo-jumbotron')
<h1 class="display-5">Stark Industries</h1>
<p class="lead">Salas Disponíveis</p>
@endsection

@section('conteudo')

<div class="container">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Página Inicial</li>
        </ol>
    </nav>


    <table class="table table-striped table-sm" style="text-align: center; ">
        <thead>
            <tr class="info">
                <th>ID </th>
                <th>Sala</th>
                <th>Visitantes/Capacidade</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="salas-lista" name="salas-lista">

        </tbody>
    </table>


    <a href="/visitantes" class="btn btn-dark mt-3 btn-lg">
        Cadastrar novo visitante
    </a>

    <button type="button" class="btn btn-primary mt-3 btn-lg" data-toggle="modal" data-target="#myModal">
        Cadastrar nova Sala
    </button>

</div>

<!-- Modal Visitantes Sala -->

<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Visitantes Sala</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="lista "></ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cadastro Sala -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Cadastrar Nova Sala</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="formSalas" method="POST" action="{{route('salvar_salas')}}">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Sala:</label>
                        <input type="text" class="form-control" id="sala" name="sala">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Capacidade:</label>
                        <input type="number" class="form-control" id="capacidade" name="capacidade">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="enviarFormulario">Salvar</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script type="text/javascript" charset="utf-8">
    listar_salas();

    function listar_salas() {

     

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })

        $.ajax({
            url: "{{ route('listar_salas') }}",
            type: "GET",
            dataType: 'json',
            beforeSend: function() {
                $('#salas-lista td').parent().remove();
            },
            success: function(response) {
                $.each(response, function(key, item) {
                    console.log(item);
                    $("#salas-lista").append("<tr><td>"+item.id+"</td><td>"+item.sala+"</td><td>"+item.visitantes+"/"+item.capacidade+"</td><td><button class='btn btn-warning btn-detail open_modal' value='"+item.id+"'>Visualizar Sala</button></td></tr>");
                });
            }
        });
    }


    $(document).ready(function() {


        $(document).on('click', '.saiu', function() {

            var sala_visitante_id = $(this).val();
            console.log( $(this).val());

            var url = '/visitantes/' + sala_visitante_id + '/out';

            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    $(".lista").html('');
                    if (typeof data !== 'undefined') {
                        $.each(data, function(key, item) {
                            console.log(item);
                            $(".lista").append("<li id='" + item.id + "'>" + item.visitante + " <button class='btn btn-warning btn-sm saiu d-flex' value='" + item.id + "'>Saiu da sala</button></li>");
                        });
                        
                    } else {
                        $(".lista").append('<li>Nenhum visitante ativo na sala.</li>');
                    }
                },
                complete: function(data){
                    
                    listar_salas();
                },  
                error: function(data) {
                    console.log('Error:', data);
                }
            });
           

        });

        $(document).on('click', '.open_modal', function() {

            $(".lista").html('');
            var sala = $(this).val();
            var url = '/sala/' + sala;

            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    if (typeof data !== 'undefined') {
                        $.each(data, function(key, item) {
                            console.log(item);
                            $(".lista").append("<li id='" + item.sala + "'>" + item.visitante + " <button class='btn btn-warning btn-sm saiu d-flex' value='" + item.sala + "'>Saiu da sala</button></li>");
                        });
                    } else {
                        $(".lista").append('<li>Nenhum visitante ativo na sala.</li>');
                    }
                },
                complete: function(data) {
                    $('#modalDetail').modal('show');
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });

        });

        $("#enviarFormulario").click(function(e) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })

            e.preventDefault();

            var formData = {
                sala: $('#sala').val(),
                capacidade: $("#capacidade").val()
            }

            $.ajax({
                url: "{{ route('salvar_salas') }}",
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(data) {
                    var item = '<tr><td>' + data.id + '</td><td>' + data.sala + '</td><td>0/' + data.capacidade + '</td><td><button class="btn btn-warning btn-detail open_modal" value="' + data.id + '">Visualizar Sala</button></td></tr>';
                    $('#salas-lista').append(item);
                },
                complete: function(data) {
                    $('#myModal').modal('hide');
                    
                }

            });
        });
    });
</script>
@endsection