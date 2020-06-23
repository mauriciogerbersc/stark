@extends('layout.app')

@section('conteudo-jumbotron')
<h1 class="display-5">Cadastro Visitante</h1>
@endsection

@section('conteudo')

<div class="container">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Página Inicial</a></li>
            <li class="breadcrumb-item active" aria-current="page">Visitantes Cadastrados</li>
        </ol>
    </nav>

    <table class="table table-striped table-sm" style="text-align: center; ">
        <thead>
            <tr class="info">
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Nascimento</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody id="visitantes-lista" name="visitantes-lista">
            @foreach($visitantes as $visitante)
            <tr>
                <td>{{$visitante->id}}</td>
                <td>{{$visitante->nome}}</td>
                <td>{{$visitante->cpf}}</td>
                <td>{{$visitante->nascimento}}</td>
                <td>{{$visitante->email}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="alert  alert-dismissible fade d-none show  messageBox mt-4" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

    </div>

    <button type="button" class="btn btn-primary mt-5" data-toggle="modal" data-target="#myModal" id="modal">
        Cadastrar novo visitante
    </button>

   

</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Cadastrar Novo Visitante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="formSalas" id="formSalas" method="POST" action="{{route('salvar_visitantes')}}">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome">
                        <div class="invalid-feedback">
                            Por favor informe o nome.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">CPF:</label>
                        <input type="text" class="form-control" id="cpf" name="cpf">
                        <div class="invalid-feedback">
                            Por favor informe o CPF.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Data de Nascimento:</label>
                        <input type="text" class="form-control" id="nascimento" name="nascimento">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Sala:</label>
                        <select name="sala" id="sala" class="form-control">
                            <option selected value="0">Selecione uma sala</option>
                            @foreach($salas as $sala)
                            <option value="{{$sala->id}}">{{$sala->sala}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Por favor selecione uma sala.
                        </div>
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
    $(document).ready(function() {

        $("#modal").click(function() {
            $('#formSalas').trigger("reset");
        });

        $("#enviarFormulario").click(function(e) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            })

            e.preventDefault();

            $error = false;

            if ($('#nome').val() == '') {
                $('#nome').addClass('is-invalid');
                $error = true;
            } else {
                $('#nome').removeClass('is-invalid');
            }

            if ($('#cpf').val() == '') {
                $('#cpf').addClass('is-invalid');
                $error = true;
            } else {
                $('#cpf').removeClass('is-invalid');
            }

            if ($('#sala option:selected').val() == 0) {
                $('#sala').addClass('is-invalid');
                $error = true;
            } else {
                $('#sala').removeClass('is-invalid');
            }

            if ($error == true) {
                return false;
            }

            var formData = {
                nome: $('#nome').val(),
                cpf: $("#cpf").val(),
                email: $("#email").val(),
                nascimento: $("#nascimento").val(),
                sala: $("#sala").val()
            }

            $.ajax({
                url: "{{ route('salvar_visitantes') }}",
                type: 'POST',
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    $(".messageBox").addClass('d-none');
                },
                success: function(data) {
                    if (data.retorno == false) {
                        $(".messageBox").html('O limite de pessoas na <strong>' + $("#sala option:selected").html() + '</strong> é <strong>' + data.limit + ' pessoas</strong> e já foi excedido.')
                            .removeClass('d-none')
                            .addClass('alert-danger');
                    } else {
                        $(".messageBox").html('Cadastro realiziado com sucesso')
                            .removeClass('d-none')
                            .removeClass('alert-danger')
                            .addClass('alert-success');

                        var item = '<tr><td>' + data.id + '</td><td>' + data.nome + '</td><td>' + data.cpf + '</td><td>' + data.nascimento + '</td><td>' + data.email + '</td></tr>'
                        $('#visitantes-lista').append(item);
                    }

                },
                complete: function(data) {
                    $('#myModal').modal('hide')
                }

            });
        });
    });
</script>
@endsection