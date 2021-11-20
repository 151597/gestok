@extends('home')
@section('title', 'Relacionamento de Lacres')
@section('title2', 'Relacionamento de Lacres')
@section('content')
<!-- FORMULÀRIO -->

<div style="display: flex;justify-content: center;">
  <form id="frmRelAtivo" class="form-group" action="">
    <input type="hidden" id="check_id">

    <div class="row">
      <div class="col-md-12">
        <label for="cadastroUsuario">Funcionário:</label>&nbsp;<span style="color: #f00;">*</span>
        <div class="row">
          <div class="col-md-8">
            <input type="text" class="form-control" id="nome" readonly>
          </div>
          <div class="col-md-2">
            <a id="btnModal" class="btn btn-info"><i class="fa fa-search"></i> Pesquisar</a>
          </div>
        </div>
      </div>
    </div>
    <br>
        
    <div class="row">
      <div class="col-md-6">
        <label for="gestok">Matrícula Gestok</label>&nbsp;<span style="color: #f00;">*</span>
        <input type="text" class="form-control" readonly name="gestok" id="gestok" required>
      </div>
      <div class="col-md-6">
        <label for="num_lacre">Lacre Nº:</label>&nbsp;<span style="color: #f00;">*</span>
        <input type="number" class="form-control" min="0" max="99999999999999999999" name="num_lacre" id="num_lacre" required>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-12">
        <label for="descricao">Descrição:</label>
        <input maxlength="50" type="text" id="descricao" class="form-control">
      </div>
      
    </div>

    
    <br>
    <div class="row">
      <div class="col-md-3">
        <button type="button" onClick='limpaSalvar()' class="btn btn-info"><span class="fa fa-eraser"></span> Limpar</button>
      </div>
      <div class="col-md-6">
        <div class="text-center">
      
          <button type="submit" id="saveRelacionamento" class="btn btn-success"><span class="fa fa-floppy-o"></span> SALVAR</button>
            
        </div>
      </div>
    </div>
    
    
  </form><!-- FIM FORMULÀRIO -->
</div>
<br>
<table class="display" id="tableRelacionamento" style="width:100%;">
  <thead>
    <tr>
      <th class="col-sm-2">MATRÍCULA GESTOK</th>
      <th class="col-sm-3">NOME</th>
      <th class="col-sm-2">Nº DO LACRE</th>
      <th class="col-sm-2" id="tableAction">Ações</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<div class="modal fade" id="modalUsuarios" tabindex="-1" role="dialog" aria-labelledby="modalUsuariosLabel" aria-hidden="true" style="overflow:scroll;">
  <div class="modal-dialog" role="document" style="max-height:90%;overflow-y:scroll;width:700px;">
    <div class="modal-content">
      <button style="padding-right:5px;" type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <div class="modal-header">
        <h5 class="modal-title" id="modalUsuariosLabel">Selecionar Funcionário</h5>
        
      </div>
      <div class="modal-body">
        <table class="display" id="tableUsuarios" style="width:100%;">
          <thead>
            <tr>
              <th class="col-sm-1">MATRÍCULA GESTOK</th>
              <th class="col-sm-3">NOME</th>
              <th class="col-sm-1">AÇÃO</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalHistorico" tabindex="-1" role="dialog" aria-labelledby="modalHistoricoLabel" aria-hidden="true" style="overflow:scroll;">
  <div class="modal-dialog" role="document" style="position:relative;top:0px;max-height:600px;max-width:600px;overflow-y:scroll;scroll-behavior:smooth;scrollbar-width: thin;;width:700px;">
    <div class="modal-content">
      <button type="button" style="padding-right:5px;" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <div class="modal-header">
        <h4 class="modal-title" id="modalHistoricoLabel"><strong>Histórico</strong></h4>
      </div>
      <div class="modal-body">

          <table class="table" id="tableHistorico"> 
            <thead>
                <tr>
                    <th scope="col">MATRÍCULA</th>
                    <th scope="col">NOME</th>
                    <th scope="col">Nº LACRE</th>
                    <th scope="col">DESCRIÇÃO</th>
                    <th scope="col">DATA</th>
                    <th scope="col">USUÁRIO</th>
                </tr>
            </thead>
            <tbody id="historico">
            </tbody>
        </table>
        </div>
      
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
        </div>
    </div>
  </div>
</div>

<script>

$(document).on('click', '#btnModal', function(){
  $('#tableUsuarios').DataTable().ajax.reload();
    $('#modalUsuarios').modal({
        backdrop:'static'
    });
});

$(window).load(function () {
    $('#tableUsuarios').DataTable({
    	"pageLength":50,
    	"searching": true,
        "paging": true,
        "info": false,
        "order":[[1,"asc"]],
        "ajax": {
          data: { "_token": "{{ csrf_token() }}" },
          method: "get",
          url: '/load/operadores_ativos',
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
                  return row.login_dac;
                }
            },
            {
                mRender: function (data, type, row) {
                  return row.nome ;
                }
            },
            {
                mRender: function (data, type, row) {

                  return '<a title="Selecionar" onClick="valorCampo(\''+row.login_dac+'\', \''+row.nome+'\')" data-dismiss="modal"><span class="glyphicon glyphicon-ok" style="cursor:pointer;"></span></a>'
                }
            },
          ]
    });


    $('#tableRelacionamento').DataTable({
    	"pageLength":50,
    	"searching": true,
        "paging": true,
        "info": false,
        "scrollX": true,
        "order":[[1,"asc"]],
        "ajax": {
          data: { "_token": "{{ csrf_token() }}" },
          method: "get",
          url: '/load/operadores_lacres',
          dataType: "json"
	},
	"language":{
	   url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
	},
      "columnDefs":[
                { "orderable": false, "targets": 3}
               
             ],
      "columns": [
            {
                mRender: function (data, type, row) {
                  return row.ID_GESTOK;
                }
            },
            {
                mRender: function (data, type, row) {
                  return row.NOME;
                }
            },
            {
                mRender: function (data, type, row) {
                  
                  return row.NUM_LACRE;
                }
            },
            {
                mRender: function (data, type, row) {

                  return '<a><span class="fa fa-list" title="Visualizar histórico" onClick="exibeHist(\''+row.ID+'\')" style="cursor:pointer;"></span></a>&nbsp;&nbsp;'+
                  '<a title="Editar" onClick="valorCampo(\''+row.ID_GESTOK+'\', \''+row.NOME+'\', \''+row.NUM_LACRE+'\', \''+row.ID+'\')"><span class="glyphicon glyphicon-edit" style="cursor:pointer;"></span></a>&nbsp;&nbsp;<a class="table-check"><input type="checkbox" class="form-check-input" name="check" id="' + row.ID + '"></a>';
                  
                }
            }
          ]
    });
    
    $('#tableRelacionamento').on('click', '.table-check', function () {
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
            url: '/delete/relacionamento_lacre',
            success: function(result) {
                alert("EXCLUIDO COM SUCESSO!");
                $('#tableRelacionamento').DataTable().ajax.reload();
                $("#tableAction").html('Ações');
            },
            error: function(result){
              alert("ERRO!");
            }
          });
        }
    });        
        
});


function valorCampo(ID_GESTOK, nome, num_lacre, id){

  $("#gestok").val(ID_GESTOK);
  $("#nome").val(nome);

  if(id != null){
    $("#num_lacre").val(num_lacre);
    $("#check_id").val(id);

    $("#saveRelacionamento").html('');
    $("#saveRelacionamento").html('<span class="fa fa-floppy-o"></span>  ATUALIZAR');
  }

}

$(document).on("submit", "#frmRelAtivo", function(event) {
  
    event.preventDefault();
      
      $.ajax({
        data: {
          gestok: function(){return $('#gestok').val() },
          nome: function(){return $('#nome').val() },
          num_lacre: function(){return $('#num_lacre').val() },
          check_id: function(){return $('#check_id').val() },
          descricao: function(){return $('#descricao').val() },
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/salvar/relacionamento/op_lacre',
        success: function(result) {
            alert(JSON.parse(result));
            $('#tableRelacionamento').DataTable().ajax.reload();
            limpaSalvar();
        },
        error: function(result){
          str = result.responseJSON.message;
          
          if(str.includes('SQLSTATE[23000]')){
            alert('Lacre e/ou funcionário já cadastrado!');
          }
        }
      });
});   

/*function removerRelacionamento(id){
  
    if(confirm("Tem certeza que deseja Excluir?") === true){
      $.ajax({
        data: {
            "_token": "{{ csrf_token() }}"
        },
        method: "delete",
        url: "/delete/relacionamento_armario/"+id,
        success: function(result) {
            alert("EXCLUIDO COM SUCESSO!");
            $('#tableRelacionamento').DataTable().ajax.reload();
        },
        error: function(result){
          alert("ERRO!");
        }
      });
    }
    
}*/

function exibeHist(id){
  console.log(id);

$("#historico").empty();

$.ajax({
    method: "get",
    url: '/load/operadores_lacres_hist/'+id,
    success: function(result) {
      
       var hist = JSON.parse(result);
       var historico = hist.data;
       
       for(var j=0;j<historico.length;j++){
            if(historico[j].DESCRICAO == null){
              historico[j].DESCRICAO = "";
            }
         
            $("#historico").append(
            '<tr><td>'+historico[j].ID_GESTOK+'</td><td>'+historico[j].NOME+'</td><td>'+historico[j].NUM_LACRE+'</td><td>'+historico[j].DESCRICAO+'</td><td>'+
            historico[j].data+'</td><td>'+historico[j].usuario+'</td></tr>'
            );
        }
    },
    error: function(result){
    alert("ERRO!");
    }
});

$('#modalHistorico').show().scrollTop(0);
$('#modalHistorico').modal({
    backdrop:'static'
});

}
 function limpaSalvar(){
  document.getElementById("frmRelAtivo").reset();
  $("#saveRelacionamento").html('');          
  $("#saveRelacionamento").html('<span class="fa fa-floppy-o"></span>  SALVAR');
 }

</script>
@stop
