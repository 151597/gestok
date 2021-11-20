@extends('home')
@section('title', 'Cadastro de Armários')
@section('title2', 'Cadastro de Armários')
@section('content')


<!-- FORMULÀRIO -->
<div style="display: flex;justify-content: center;">
<form id="frmArmario" class="form-group" action="">
  <input type="hidden" name="id" id="id">
  <div class="row">
    <div class="col-md-6">
      <label for="numeracao">Número</label>&nbsp;<span style="color: #f00;">*</span>
      <input type="number" min="0" class="form-control" name="numeracao" id="numeracao" required placeholder="Número">
    </div>
    <div class="col-md-6">
      <label for="ativo">Situação:</label>
      <br>
      <select class="form-control" name="ativo" id="ativo" required>
        <option value="">-- SELECIONE --</option>
        <option value="1">Ativo</option>
        <option value="0">Não Ativo</option>
      </select>
    </div>
  </div>

  
  <br>
  <div class="text-center">
    <button type="submit" id="saveArmario" class="btn btn-success"><span class="fa fa-floppy-o"></span> SALVAR</button>
  </div>
  
</form><!-- FIM FORMULÀRIO -->
</div>

<table class="display" id="tableArmario" style="width:100%;"> <!-- TABELA -->
  <thead>
    <tr>
      <th scope="col">Numeração</th>
      <th scope="col">Ativo</th>
      <th scope="col" id="tableAction">Ações</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table><!-- FIM TABELA -->

<script>


$(window).load(function () {
    $('#tableArmario').DataTable({
        "searching": true,
        "paging": true,
        "info": false,
        "scrollX": true,
        "ajax": {
          data: { "_token": "{{ csrf_token() }}" },
          method: "get",
          url: '/load/armarios',
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
                    return row.numeracao ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.ativo ;
                }
            },
            {
                mRender: function (data, type, row) {
                  return '<a onClick="edicao(\''+row.numeracao+'\', \''+row.ativoReal+'\')"><span class="glyphicon glyphicon-edit" style="cursor:pointer;"></span></a>&nbsp&nbsp<a class="table-check"><input type="checkbox" class="form-check-input" name="check" id="' + row.numeracao + '"></a>'
                }
            },
          ]
    });

    $('#tableArmario').on('click', '.table-check', function () {
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
            url: '/delete/armario/' + check,
            success: function(result) {
                alert("EXCLUIDO COM SUCESSO!");
                $('#tableArmario').DataTable().ajax.reload();
                $("#tableAction").html('Ações');
            },
            error: function(result){
              alert("ERRO!");
            }
          });
        }
    });                
});

$(document).on("submit", "#frmArmario", function(event) {
    event.preventDefault();

      $.ajax({
        data: {
          numeracao: parseInt($('#numeracao').val()),
          ativo: parseInt($('#ativo').val()),
          "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/salvar/armario',
        success: function(result) {
            alert("SALVO COM SUCESSO!");
            $('#tableArmario').DataTable().ajax.reload();
            $("#saveArmario").html('<span class="fa fa-floppy-o"></span>  SALVAR');
            clearFields();
        },
        error: function(result){
          alert("ERRO!");
        }
      });
});   

function edicao(numeracao, ativo){
  //clearFields();

  $('#numeracao').val(numeracao);
  $("#ativo").val(ativo);

  $("#saveArmario").html('<span class="fa fa-floppy-o"></span>  ATUALIZAR');

  document.documentElement.scrollTop = 0;
  
}

function clearFields(){
  $('#numeracao').val(''); 
  $('#ativo').val('');
}
</script>
@stop
