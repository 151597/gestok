@extends('home')
@section('title', 'Cadastro de Local de Estoque')
@section('title2', 'Cadastro de Local de Estoque')
@section('content')
<!-- FORMULÀRIO -->
<form id="frmEstoque" class="form-inline" action="">
  <input type="hidden" name="id_estoque" id="id_estoque">
  <div class="form-group">
    <label for="cadastroEstoque">Nome:&nbsp</label>
    <input type="text" class="form-control" name="cadastroEstoque" id="cadastroEstoque" required style="border: 1px solid #ced4da;" placeholder="Nome">
  </div>
  &nbsp
  <button type="submit" id="saveEstoque" class="btn btn-success"><span class="fa fa-floppy-o"></span>  SALVAR</button>
</form><!-- FIM FORMULÀRIO -->
<br><br><br>
<table class="display" id="tableEstoque" style="width:100%;"> <!-- TABELA -->
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Nome</th>
      <th scope="col" id="tableAction">Ações</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row"></th>
      <td></td>
      <td>
        <a><span class="glyphicon glyphicon-edit" style="cursor:pointer;"></span></a>
        <a><span style="position:relative;left:10px;cursor:pointer;" class="glyphicon glyphicon-trash"></span></a>
      </td>
    </tr>
  </tbody>
</table><!-- FIM TABELA -->

<script>

$(window).load(function () {
    $('#tableEstoque').DataTable({
        "searching": false,
        "paging": false,
        "info": false,
        "scrollX": true,
        "ajax": {
          data: { "_token": "{{ csrf_token() }}" },
          method: "get",
          url: '/' + 'load' + '/' + 'estoque',
          dataType: "json"
      	},
	"language":{
	  url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
	},
      	"columnDefs":[
                { "orderable": false, "targets": 2}
               
             ],
      	"columns": [
            {
                mRender: function (data, type, row) {
                    return row.id_estoque ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.nome ;
                }
            },
            {
                mRender: function (data, type, row) {
                  return '<a onClick="edicao(\''+row.id_estoque+'\', \''+row.nome+'\')"><span class="glyphicon glyphicon-edit" style="cursor:pointer;"></span></a>&nbsp&nbsp<a class="table-check"><input type="checkbox" class="form-check-input" name="check" id="' + row.id_estoque + '"></a>'
                }
            },
          ],
          "order":[[1,"asc"]]
    });

    $('#tableEstoque').on('click', '.table-check', function () {
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
            url: '/' + 'delete' + '/' + 'estoque' + '/' + check,
            success: function(result) {
                alert("EXCLUIDO COM SUCESSO!");
                $('#tableEstoque').DataTable().ajax.reload();
                $("#tableAction").html('Ações');
            },
            error: function(result){
              alert("ERRO!");
            }
          });
        }
    });     
});

$(document).on("submit", "#frmEstoque", function(event) {
    event.preventDefault();

    var data = $('#cadastroEstoque').val();
    var id_estoque = $('#id_estoque').val();

      $.ajax({
        data: {
            id_estoque: id_estoque,
            data: data,
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/' + 'salvar' + '/' + 'estoque',
        success: function(result) {
            alert("SALVO COM SUCESSO!");
            $('#tableEstoque').DataTable().ajax.reload();
            clearFields();
        },
        error: function(result){
          alert("ERRO!");
        }
      });
});   

function edicao(id, nome){
  $("#id_estoque").val(id);
  $("#cadastroEstoque").val(nome);
  $("#saveEstoque").html('');
  $("#saveEstoque").html('<span class="fa fa-floppy-o"></span>  ATUALIZAR');
}

function clearFields(){
  $("#id_estoque").val('');
  $("#cadastroEstoque").val('');
  $("#saveEstoque").html('');
  $("#saveEstoque").html('<span class="fa fa-floppy-o"></span>  SALVAR');
}
</script>
@stop
