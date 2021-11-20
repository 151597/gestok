@extends('home')
@section('title', 'Cadastro de Usuários')
@section('title2', 'Cadastro de Usuários')
@section('content')
<style>
.dataTables_wrapper .dataTables_length{
  float:right;
}

</style>
<div style="justify-content: center;">
    <form id="frmUsuario" class="form-group" action="">
    <input type="hidden" name="id" id="id">
        <div class="row">
            <div class="col-md-4">
                <label for="name">Nome:</label></label>&nbsp;<span style="color: #f00;">*</span>
                <input id="name" style="border: 1px solid #ced4da;" maxlength="255" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nome">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="email">E-mail:</label></label>&nbsp;<span style="color: #f00;">*</span>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="E-mail">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            @if(Auth::user()->funcao == 1)
                <div class="col-md-4">
                    <label for="funcao">Função:</label>&nbsp;<span style="color: #f00;">*</span>
                    <select class="form-control" name="funcao" id="funcao" required >
                        <option value="">-- SELECIONE --</option>
                        <option value="1">Administrador</option>
                        <option value="2">Suporte T.I.</option>
                        <option value="3">Compras</option>
                    </select>
                </div>
            @endif
        </div>
        <br>
        <div class="row">
            <div class="col-md-4">
                <label for="password">Senha:</label></label>&nbsp;<span style="color: #f00;">*</span>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Senha">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="password_confirmation">Confirmar Senha:</label></label>&nbsp;<span style="color: #f00;">*</span>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmar Senha">
            </div>
        </div>
        <br>
        <div style="height: 100%;display: flex;justify-content: center;align-items: center;">
            <button type="submit" id="saveUser" class="btn btn-success">
                <span class="fa fa-floppy-o"></span> SALVAR
            </button>
        </div>
    </form>
</div>
<table class="display" id="tableUsuarios" style="width:100%;"> <!-- TABELA -->
  <thead>
    <tr>
      <th scope="col-sm-2">#</th>
      <th scope="col-sm-2">Email</th>
      <th scope="col-sm-2">Nome</th>
      <th scope="col-sm-2">Função</th>
      <th scope="col-sm-2" id="tableAction">Ações</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>everton@ribas</td>
      <td>Everton Romero Rivas</td>
      <td>
            <a><span class="glyphicon glyphicon-edit" style="cursor:pointer;"></span></a>
            <a><span style="position:relative;left:10px;cursor:pointer" class="glyphicon glyphicon-trash"></span></a>
      </td>
    </tr>
  </tbody>
</table><!-- FIM TABELA -->

<script>
$(window).load(function () {
    $('#tableUsuarios').DataTable({
        "searching": false,
        "paging": true,
        "info": false,
        "scrollX": true,
        "ajax": {
          data: { "_token": "{{ csrf_token() }}" },
          method: "get",
          url: '/load/register',
          dataType: "json"
      	},
	"language":{
	  url: '/assets/plugins/datatables/js/Portuguese-Brasil.json'
	},
      "columnDefs":[
                { "orderable": false, "targets": 4,
                  "width": "17%", "targets": 4}
               
             ],
      "columns": [
            {
                mRender: function (data, type, row) {
                    return row.id ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.email ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.name ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.funcao ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return '<a onClick="edicao(\''+row.id+'\', \''+row.name+'\', \''+row.email+'\', \''+row.id_funcao+'\')"><span class="glyphicon glyphicon-edit" style="cursor:pointer;"></span></a>&nbsp&nbsp'+
                '<?php if((Auth::user()->funcao == 1)):?><a class="table-check"><input type="checkbox" class="form-check-input" name="check" id="' + row.id + '"></a><?php endif ?>'
                }
            },
          ],
          "order":[[2,"asc"]]
    });

    $('#tableUsuarios').on('click', '.table-check', function () {
      var check = [];
          $("input[name='check']").each(function() {
            if ($(this).prop("checked")) {
                check.push($(this).prop("id"));
            }
        });
        console.log(check);
        if(check.length > 0){
          $("#tableAction").html('<a class="table-delete btn btn-danger" style="position:relative; right:10px;"><span style="cursor:pointer;" class="glyphicon glyphicon-trash" title="DELETAR"></span>&nbsp;&nbsp;Remover Ítens</a>');
        }else{
          $("#tableAction").html('Ações');
        }
    });

    $('#tableAction').on('click', '.table-delete', function () {
        var check = [];
            $("input[name='check']").each(function() {
              if ($(this).prop("checked")) {
                  check.push($(this).prop("id"));
              }
          });

        if(confirm("Tem certeza que deseja Excluir?") === true){
          $.ajax({
            data: {
                data: check,
                "_token": "{{ csrf_token() }}"
            },
            method: "delete",
            url: '/' + 'delete' + '/' + 'register' + '/' + check,
            success: function(result) {
                alert("EXCLUIDO COM SUCESSO!");
                $('#tableUsuarios').DataTable().ajax.reload();
                $("#tableAction").html('Ações');
            },
            error: function(result){
              alert("ERRO!");
            }
          });
        }
    });            
});

$(document).on("submit", "#frmUsuario", function(event) {
    event.preventDefault();

    var id = $('#id').val();
    var name = $('#name').val();
    var password = $('#password').val();
    var email = $('#email').val();
    var funcao = $('#funcao').val();
    var password_confirmation = $('#password_confirmation').val();


      $.ajax({
        data: {
            id: id,
            name: name,
            password: password,
            email: email,
            funcao: funcao,
            password_confirmation: password_confirmation,
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/' + 'salvar' + '/' + 'usuario',
        success: function(result) {
            alert("SALVO COM SUCESSO!");
            $('#tableUsuarios').DataTable().ajax.reload();
            clearFields();
        },
        error: function(result){
            
            if(typeof result.responseJSON.errors !== 'undefined'){
                if(result.responseJSON.errors.email){
                    alert('Os dados fornecidos são inválidos. O e-mail já foi cadastrado!');
                }else{
                    alert('Os dados fornecidos são inválidos. A confirmação da senha não corresponde!');
                }
            }else{
                alert('Você não possui permissão para cadastro!');
            }
            
        }
      });
});  

function edicao(id, nome, email, funcao){
    $("#id").val(id);
    $("#name").val(nome);
    $("#email").val(email);
    $("#funcao").val(funcao);
    $('#password').val('');
    $('#password_confirmation').val('');
    $("#saveUser").html('');
    $("#saveUser").html('<span class="fa fa-floppy-o"></span>  ATUALIZAR');

    if(<?= json_encode(Auth::user()->email) ?> != email){
        $('#password').removeAttr("required", "required");
        $('#password_confirmation').removeAttr("required", "required");
        $('#password').attr("readonly", "readonly");
        $('#password_confirmation').attr("readonly", "readonly");
    }else if(<?= json_encode(Auth::user()->email) ?> == email){
        $('#password').removeAttr("readonly", "readonly");
        $('#password_confirmation').removeAttr("readonly", "readonly");
        $('#password').removeAttr("required", "required");
        $('#password_confirmation').removeAttr("required", "required");
    }else{
        $('#password').removeAttr("readonly", "readonly");
        $('#password_confirmation').removeAttr("readonly", "readonly");
        $('#password').attr("required", "required");
        $('#password_confirmation').attr("required", "required");
    }

    document.documentElement.scrollTop = 0;
}

function clearFields(){
    /*$("#id").val('');
    $('#name').val('');
    $('#email').val('');
    $('#password').val('');
    $('#password_confirmation').val('');*/

    document.getElementById('frmUsuario').reset();
    $("#saveUser").html('');
    $("#saveUser").html('<span class="fa fa-floppy-o"></span>  SALVAR');
}
</script>
@endsection
