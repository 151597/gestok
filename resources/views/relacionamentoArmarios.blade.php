@extends('home')
@section('title', 'Relacionamento de Armários')
@section('title2', 'Relacionamento de Armários')
@section('content')
<!-- FORMULÀRIO -->
<button onClick="atualizaHorarios()" class="btn btn-success"><span class="fa fa-refresh "></span> Atualizar Hor&aacute;rios</button>
<br><br>
<div style="display: flex;justify-content: center;">
  <form id="frmArmario" class="form-group" action="">
    <input type="hidden" id="id_relacionamento">
    <input type="hidden" id="nome">
    <input type="hidden" id="supervisor">
    <input type="hidden" id="entrada">
    <input type="hidden" id="saida">

    <div class="row">
      <div class="col-md-12">
        <label for="cadastroUsuario">Funcionário:</label>&nbsp;<span style="color: #f00;">*</span>
        <div class="row">
          <div class="col-md-10">
            <select class="form-control" name="cadastroUsuario" id="cadastroUsuario" required onChange="valorCampo();">
                <option value="">
                -- SELECIONE --
                </option>
                @if(!empty($usuarios))
                  @foreach($usuarios as $key => $value)
                      <option data-nome="{{$value['nome']}}" data-supervisor="{{$value['supervisor']}}" data-entrada="{{$value['entrada']}}" data-saida="{{$value['saida']}}" value="{{$value['login']}}">
                        {{$value['nome']}}
                      </option>
                  @endforeach
                @endif
            </select>
          </div>
          <div class="col-md-2">
            <a id="btnModal" class="btn btn-info"><i class="fa fa-search"></i> Pesquisar</a>
          </div>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-12">
        <label for="cadastroUsuario">Armário Nº:</label>&nbsp;<span style="color: #f00;">*</span>
        <div class="row">
          <div class="col-md-10">
            <select class="form-control" name="cadastroArmario" id="cadastroArmario" required>
                <option value="">
                -- SELECIONE --
                </option>
                @if(!empty($armarios))
                  @foreach($armarios as $key => $value)
                      <option value="{{$value['numeracao']}}">
                          {{$value['numeracao']}}
                      </option>
                  @endforeach
                @endif
            </select>
          </div>
          <div class="col-md-2">
            <button type="submit" id="saveRelacionamento" class="btn btn-success"><span class="fa fa-floppy-o"></span> SALVAR</button>
          </div>
        </div>
      </div>
    </div>
    
    
  </form><!-- FIM FORMULÀRIO -->
</div>
<br>
<table class="display" id="tableRelacionamento" style="width:100%;">
  <thead>
    <tr>
      <th class="col-sm-2">MATRÍCULA</th>
      <th class="col-sm-2">NOME</th>
      <th class="col-sm-2">SUPERVISOR(A)</th>
      <th class="col-sm-2">ENTRADA/SAÍDA</th>
      <th class="col-sm-2">Nº DO ÁRMARIO</th>
      <th class="col-sm-2" id="tableAction">Ações</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<div class="modal fade" id="modalUsuarios" tabindex="-1" role="dialog" aria-labelledby="modalUsuariosLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-height:90%;overflow-y:scroll;width:80%;">
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
              <th class="col-sm-1">MATRÍCULA</th>
              <th class="col-sm-3">NOME</th>
              <th class="col-sm-3">FUNÇÃO</th>
              <th class="col-sm-3">SUPERVISOR(A)</th>
              <th class="col-sm-1">ENTRADA/SAÍDA</th>
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
          url: '/load/usuarios',
          dataType: "json"
	},
	"language":{
	   url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
	},
      "columnDefs":[
                { "orderable": true, "targets": 5}
               
             ],
      "columns": [
            {
                mRender: function (data, type, row) {
                  return row.login ;
                }
            },
            {
                mRender: function (data, type, row) {
                  return row.nome ;
                }
            },
            {
                mRender: function (data, type, row) {
                  return row.funcao ;
                }
            },
            {
                mRender: function (data, type, row) {
                  return row.supervisor ;
                }
            },
            {
                mRender: function (data, type, row) {

                  var e = "";
                  var s = "";
                  
                  
                  if(row.entrada != null || row.entrada != ""){
                    var horarioE = new Date(row.entrada);
                    e = horarioE.getHours().toString().padStart(2, "0")+":"+horarioE.getMinutes().toString().padStart(2, "0");
                  }

                  if(row.saida != null || row.saida != ""){
                    var horarioS = new Date(row.saida);
                    s = horarioS.getHours().toString().padStart(2, "0")+":"+horarioS.getMinutes().toString().padStart(2, "0");
                  }
                  return e+" às "+s;
                }
            },
            {
                mRender: function (data, type, row) {

                  return '<a title="Selecionar" onClick="valorCampo(\''+row.login+'\', \''+row.nome+'\', \''+row.supervisor+'\', \''+row.entrada+'\', \''+row.saida+'\')" data-dismiss="modal"><span class="glyphicon glyphicon-ok" style="cursor:pointer;"></span></a>'
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
          url: '/load/relacionamento_armarios',
          dataType: "json"
	},
	"language":{
	   url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
	},
      "columnDefs":[
                { "orderable": true, "targets": 4}
               
             ],
      "columns": [
            {
                mRender: function (data, type, row) {
                  return row.id_usuario;
                }
            },
            {
                mRender: function (data, type, row) {
                  return row.nome;
                }
            },
            {
                mRender: function (data, type, row) {
                  return row.supervisor;
                }
            },
            {
                mRender: function (data, type, row) {
                  
                  return row.entrada+" às "+row.saida;
                }
            },
            {
                mRender: function (data, type, row) {
                  return row.id_armario;
                }
            },
            {
                mRender: function (data, type, row) {

                  return '<a onClick="edicao(\''+row.id_ops_armarios+'\',\''+row.id_armario+'\'), valorCampo(\''+row.id_usuario+'\', \''+row.nome+'\', \''+row.supervisor+'\', \''+row.entrada+'\', \''+row.saida+'\')"><span class="glyphicon glyphicon-edit" style="cursor:pointer;"></span></a>&nbsp&nbsp<a class="table-check"><input type="checkbox" class="form-check-input" name="check" id="' + row.id_ops_armarios + '"></a>'
                }
            },
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
            url: '/delete/relacionamento_armario/' + check,
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

function edicao(id_relacionamento, id_armario){
  $("#id_relacionamento").val(id_relacionamento);
  $("#cadastroArmario").val(id_armario);

  $("#saveRelacionamento").html('');
  $("#saveRelacionamento").html('<span class="fa fa-floppy-o"></span>  ATUALIZAR');
}

function valorCampo(id, nome, supervisor, entrada, saida){
  
  if(id == "" || id == null){
    var id = $( "#cadastroUsuario option:selected" ).val();
    var nome = $( "#cadastroUsuario option:selected" ).attr('data-nome');
    var supervisor = $( "#cadastroUsuario option:selected" ).attr('data-supervisor');
    var entrada = $( "#cadastroUsuario option:selected" ).attr('data-entrada');
    var saida = $( "#cadastroUsuario option:selected" ).attr('data-saida');
  }

  $("#cadastroUsuario").val(id);
  $("#nome").val(nome);
  $("#supervisor").val(supervisor);
  $("#entrada").val(entrada);
  $("#saida").val(saida);
}

$(document).on("submit", "#frmArmario", function(event) {
  
  console.log($('#cadastroUsuario').val());
  
    event.preventDefault();
      
      $.ajax({
        data: {
          id_relacionamento: $('#id_relacionamento').val(),
          cadastroUsuario: $('#cadastroUsuario').val(),
          cadastroArmario: $('#cadastroArmario').val(),
          nome: $('#nome').val(),
          supervisor: $('#supervisor').val(),
          entrada: $('#entrada').val(),
          saida: $('#saida').val(),
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/salvar/relacionamento/armario',
        success: function(result) {
            alert(JSON.parse(result));
            $('#tableRelacionamento').DataTable().ajax.reload();
            document.getElementById("frmArmario").reset();

            $("#saveRelacionamento").html('');
            $("#saveRelacionamento").html('<span class="fa fa-floppy-o"></span>  SALVAR');
        },
        error: function(result){
          alert("ERRO!");
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

function atualizaHorarios(){
  $.ajax({
    data: {
        "_token": "{{ csrf_token() }}"
    },
    method: "GET",
    url: "/atualiza_horarios",
    success: function(result) {
        alert("HORÁRIOS ATUALIZADOS COM SUCESSO!!");
        $('#tableRelacionamento').DataTable().ajax.reload();
    },
    error: function(result){
      alert("ERRO!");
    }
  });
}


</script>
@stop
