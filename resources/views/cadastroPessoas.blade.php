@extends('home')
@section('title', 'Cadastro de Pessoas')
@section('title2', 'Cadastro de Pessoas')
@section('content')
<style>
/*.dataTables_wrapper .dataTables_length{
  float:right;
}*/
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] {
  -moz-appearance: textfield;
}
</style>
<!-- FORMULÀRIO -->
<form id="frmPessoas" class="form-inline" action="">
  <input type="hidden" name="id_pessoa" id="id_pessoa">
  <div class="input-append">
    <label for="cadastroMatricula">Matrícula:</label>
    <input type="number" class="form-control" name="cadastroMatricula" id="cadastroMatricula" style="border: 1px solid #ced4da;" required placeholder="Matrícula">
    <label for="cadastroPessoas">Nome:</label>
    <input type="text" class="form-control" name="cadastroPessoas" id="cadastroPessoas" style="border: 1px solid #ced4da;" required placeholder="Nome">
    <label for="setor">Setor:</label>
    <select name="setor" id="setor" class="form-control" style="border: 1px solid #ced4da;" required>
      <option value="">-- SELECIONE --</option>
      <option value="Supervisão Operacional">Supervisão Operacional</option>
      <option value="Treinamento">Treinamento</option>
      <option value="Qualidade">Qualidade</option>
      <option value="Tráfego">Tráfego</option>
      <option value="Suporte de T.I.">Suporte de T.I.</option>
      <option value="Desenvolvimento T.I.">Desenvolvimento</option>
      <option value="Recursos Humano">Recursos Humanos</option>
      <option value="Coordenação">Coordenação</option>
      <option value="Gerência">Gerência</option>
      <option value="Outros">Outros</option>
    </select>
    <button type="submit" id="savePessoas" class="btn btn-success"><span class="fa fa-floppy-o"></span> SALVAR</button>
  </div>
         
</form><!-- FIM FORMULÀRIO -->
<br>
<table class="display" id="tablePessoas" style="width:100%;"> <!-- TABELA -->
  <thead>
    <tr>
      <th class="col-sm-2">#</th>
      <th class="col-sm-2">Matrícula</th>
      <th class="col-sm-2">Nome</th>
      <th class="col-sm-2">Setor</th>
      <th class="col-sm-2" id="tableAction">Ações</th>
    </tr>
  </thead>
  <tbody>
    
  </tbody>
</table><!-- FIM TABELA -->

<script>

$(window).load(function () {
    $('#tablePessoas').DataTable({
        "searching": true,
        "paging": true,
        "info": false,
        "scrollX": true,
        "ajax": {
          data: { "_token": "{{ csrf_token() }}" },
          method: "get",
          url: '/' + 'load' + '/' + 'pessoas',
          dataType: "json"
	},
	"language":{
	  url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
	},
      "columnDefs":[
                { "orderable": false, "targets": 4}
               
             ],
      "columns": [
            {
                mRender: function (data, type, row) {
                    return row.id_pessoa ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.matricula ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.nome ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.setor ;
                }
            },
            {
                mRender: function (data, type, row) {
                  return '<a onClick="edicao(\''+row.id_pessoa+'\', \''+row.matricula+'\', \''+row.nome+'\', \''+row.setor+'\')"><span class="glyphicon glyphicon-edit" style="cursor:pointer;"></span></a>&nbsp&nbsp<a class="table-check"><input type="checkbox" class="form-check-input" name="check" id="' + row.id_pessoa + '"></a>'
                }
            },
          ],
          "order":[[2,"asc"]]
    });

    $('#tablePessoas').on('click', '.table-check', function () {
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
            url: '/' + 'delete' + '/' + 'pessoas' + '/' + check,
            success: function(result) {
                alert("EXCLUIDO COM SUCESSO!");
                $('#tablePessoas').DataTable().ajax.reload();
                $("#tableAction").html('Ações');
            },
            error: function(result){
              alert("ERRO!");
            }
          });
        }
    });         
});

$(document).on("submit", "#frmPessoas", function(event) {
    event.preventDefault();

    var matricula = $('#cadastroMatricula').val();
    var nome = $('#cadastroPessoas').val();
    var id_pessoa = $('#id_pessoa').val();
    var setor = $('#setor').val();

      $.ajax({
        data: {
            id_pessoa: id_pessoa,
            matricula: matricula,
            nome: nome,
            setor: setor,
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/' + 'salvar' + '/' + 'pessoas',
        success: function(result) {
            alert("SALVO COM SUCESSO!");
            $('#tablePessoas').DataTable().ajax.reload();
            clearFields();
        },
        error: function(result){
          alert("ERRO!");
        }
      });
});  

function edicao(id, matricula, nome, setor){
  $("#id_pessoa").val(id);
  $("#cadastroMatricula").val(matricula);
  $("#cadastroPessoas").val(nome);
  $("#setor").val(setor);
  $("#savePessoas").html('');
  $("#savePessoas").html('<span class="fa fa-floppy-o"></span>  ATUALIZAR');
  document.documentElement.scrollTop = 0;
}

function clearFields(){
  $("#id_pessoa").val('');
  $('#cadastroMatricula').val('');
  $('#cadastroPessoas').val('');
  $("#savePessoas").html('');
  $("#savePessoas").html('<span class="fa fa-floppy-o"></span>  SALVAR');
}
</script>
@stop
