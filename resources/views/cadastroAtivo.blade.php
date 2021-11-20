@extends('home')
@section('title', 'Cadastro de Ativo')
@section('title2', 'Cadastro de Ativo')
@section('content')
<!-- FORMULÀRIO -->

<div style="justify-content: center;">
<form id="frmAtivo" class="form-group" action="">
  <input type="hidden" name="id" id="id">
  <div class="row">
    <div class="col-md-3">
    <label for="cadastroTipo">Tipo de Ativo:</label>&nbsp;<span style="color: #f00;">*</span>
    <select class="form-control" name="cadastroTipo" id="cadastroTipo" required>
        <option value="">
        -- SELECIONE --
        </option>
        @if(!empty($loadTipoAtivo))
        @foreach($loadTipoAtivo as $key => $value)
            <option value="{{$value['id_ativo']}}">
                {{$value['nome']}}
            </option>
        @endforeach
        @endif
    </select>
    </div>
    <div class="col-md-3">
    <label for="cadastroEstoque">Empresa:</label>&nbsp;<span style="color: #f00;">*</span>
    <select class="form-control" name="cadastroEstoque" id="cadastroEstoque" required>
        <option value="">
        -- SELECIONE --
        </option>
        @if(!empty($loadEstoque))
        @foreach($loadEstoque as $key => $value)
            <option value="{{$value['id_estoque']}}">
                {{$value['nome']}}
            </option>
        @endforeach
        @endif
    </select>
    </div>
    <div class="col-md-3">
    <label for="cadastroSetor">Setor:</label>&nbsp;<span style="color: #f00;">*</span>
    <select class="form-control" name="cadastroSetor" id="cadastroSetor" required>
        <option value="">
        -- SELECIONE --
        </option>
        @if(!empty($loadSetor))
        @foreach($loadSetor as $key => $value)
            <option value="{{$value['id_setor']}}">
                {{$value['nome']}}
            </option>
        @endforeach
        @endif
    </select>
    </div>
    <div class="col-md-3">
    <label for="cadastroGPS">GPS/BAIA:</label>
    <input type="number" class="form-control" name="cadastroGPS" id="cadastroGPS" style="border: 1px solid #ced4da;" placeholder="GPS/BAIXA">
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
    <label for="cadastroDescricao">Descri&ccedil;&atilde;o/Identifica&ccedil;&atilde;o:</label>&nbsp;<span style="color: #f00;">*</span>
    <input type="text" class="form-control" name="cadastroDescricao" id="cadastroDescricao" style="border: 1px solid #ced4da;" placeholder="Descrição" required>
    </div>
    <div class="col-md-2">
    <label for="cadastroSerie">N° de Série:</label>&nbsp;<span style="color: #f00;"></span>
    <input type="text" class="form-control" name="cadastroSerie" id="cadastroSerie" style="border: 1px solid #ced4da;" placeholder="N° de Série">
    </div>
    <div class="col-md-2">
    <label for="cadastroPatrimonio">Patrimônio:</label>
    <input type="text" class="form-control" name="cadastroPatrimonio" id="cadastroPatrimonio" style="border: 1px solid #ced4da;" placeholder="Patrimônio">
    </div>
	<div class="col-md-2">
    <label for="cadastroRamal">Ramal</label>&nbsp;<span style="color: #f00"></span>
    <input type="text" class="form-control" name="cadastroRamal" id="cadastroRamal" style="border: 1px solid #ced4da;" placeholder="Ramal">
    </div>
    <div class="col-md-3">
    <label for="cadastroSituacao">Situação:</label>&nbsp;<span style="color: #f00;">*</span>
    <select class="form-control" name="cadastroSituacao" id="cadastroSituacao" required>
        <option value="">
        -- SELECIONE --
        </option>
      <option value="1">Bom</option>
      <option value="2">Ruim - gera conserto</option>
      <option value="3">Estragado - sem conserto</option>
      <option value="4">N&atilde;o avaliado</option>
    </select>
    </div>
  </div>
  <br>
  <div style="height: 100%;display: flex;justify-content: center;align-items: center;">
	<button type="button" id="cancelAtivo" class="btn btn-danger" onclick="clearFields()"><span class="fa fa-ban"></span> Cancelar</button>&nbsp;
    <button type="submit" id="saveAtivo" class="btn btn-success"><span class="fa fa-floppy-o"></span> Salvar</button>
  </div>
</form><!-- FIM FORMULÀRIO -->
</div>

<table id="tableAtivo" class="display" style="width:100%;"> <!-- TABELA -->
  <thead>
    <tr >
      <th scope="col-sm-2">#</th>
      <th scope="col-sm-2">Tipo</th>
      <th scope="col-sm-2">Descri&ccedil;&atilde;o/Hostname</th>
      <th scope="col-sm-2">N&deg; S&eacute;rie</th>
      <th scope="col-sm-1">Baia</th>
      <th scope="col-sm-2">Empresa</th>
      <th scope="col-sm-2">Setor</th>
      <th scope="col-sm-2">Situação</th>
      <th scope="col-sm-2">Patrim&ocirc;nio</th>
      <th scope="col-sm-2" id="tableAction">A&ccedil;&otilde;es</th>
    </tr>
  </thead>
  <tbody>
    
  </tbody>
</table><!-- FIM TABELA -->

<script>

$(window).load(function () {
    $('#tableAtivo').DataTable({
    	"pageLength":50,
    	"searching": true,
        "paging": true,
        "info": false,
        "scrollX": true,
        "ajax": {
          data: { "_token": "{{ csrf_token() }}" },
          method: "get",
          url: '/' + 'load' + '/' + 'ativo',
          dataType: "json"
	},
	"language":{
	   url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
	},
      "columnDefs":[
                { "orderable": false, "targets": 9,
                  "width": "17%", "targets": 9}
               
             ],
      "columns": [
            {
                mRender: function (data, type, row) {
                    return row.id_objeto ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.tipo ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.descricao ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.numero_serie ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.gps ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.estoque ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.nome_setor ;
                }
            },
            {
                mRender: function (data, type, row) {
                  if(row.situacao == 1){
                    return 'Bom' ;
                  }else if(row.situacao == 2){
                    return 'Ruim - gera conserto';
                  }else if(row.situacao == 3){
                    return 'Estragado - sem conserto';
                  }else{
                    return 'N&atilde;o avaliado';
                  }
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.patrimonio ;
                }
            },
            {
                mRender: function (data, type, row) {

                    return '<a onClick="edicao(\''+row.id_objeto+'\', \''+row.id_ativo+'\', \''+row.id_estoque+'\', \''+row.id_setor+'\', \''+row.gps+'\', \''+row.descricao+'\', \''+row.numero_serie+'\', \''+row.patrimonio+'\', \''+row.situacao+'\',\''+row.ramal+'\')"><span class="glyphicon glyphicon-edit" style="cursor:pointer;"></span></a>&nbsp&nbsp<a class="table-check"><input type="checkbox" class="form-check-input" name="check" id="' + row.id_objeto + '"></a>'
                }
            },
          ]
    });

    $('#tableAtivo').on('click', '.table-check', function () {
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
            url: '/' + 'delete' + '/' + 'ativo' + '/' + check,
            success: function(result) {
                alert("EXCLUIDO COM SUCESSO!");
                $('#tableAtivo').DataTable().ajax.reload();
                $("#tableAction").html('Ações');
            },
            error: function(result){
              alert("ERRO!");
            }
          });
        }
    });          
});

$(document).on("submit", "#frmAtivo", function(event) {
    event.preventDefault();

    var id = $('#id').val();
    var cadastroTipo = $('#cadastroTipo').val();
    var cadastroEstoque = $('#cadastroEstoque').val();
    var cadastroSetor = $('#cadastroSetor').val();
    console.log(cadastroEstoque);
    var cadastroGPS = $('#cadastroGPS').val();
    var cadastroDescricao = $('#cadastroDescricao').val();
    var cadastroSerie = $('#cadastroSerie').val();
    var cadastroPatrimonio = $('#cadastroPatrimonio').val();
    var cadastroSituacao = $('#cadastroSituacao').val();
	var cadastroRamal    = $('#cadastroRamal').val();


      $.ajax({
        data: {
          id:id,
          cadastroTipo: cadastroTipo,
          cadastroEstoque: cadastroEstoque,
          cadastroSetor: cadastroSetor,
          cadastroGPS: cadastroGPS,
          cadastroDescricao: cadastroDescricao,
          cadastroSerie: cadastroSerie,
          cadastroPatrimonio: cadastroPatrimonio,
          cadastroSituacao: cadastroSituacao,
		  cadastroRamal: cadastroRamal,
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/' + 'salvar' + '/' + 'ativo',
        success: function(result) {
            alert("SALVO COM SUCESSO!");
            $('#tableAtivo').DataTable().ajax.reload();
            $("#saveAtivo").html('<span class="fa fa-floppy-o"></span>  SALVAR');
            clearFields();
        },
        error: function(result){
          alert("ERRO!");
        }
      });
});   

function edicao(id, tipo, estoque, setor, gps, descricao, serie, patrimonio, situacao, ramal){
  clearFields();
 
  $('#id').val(id);
  $('#cadastroTipo').val(tipo);
  $('#cadastroEstoque').val(estoque);
  $('#cadastroSetor').val(setor);
  if(gps !== 'null'){
    $('#cadastroGPS').val(gps);
  }
  $('#cadastroDescricao').val(descricao);
  $('#cadastroSerie').val(serie);
  if(patrimonio !== 'null'){
    $('#cadastroPatrimonio').val(patrimonio);
  }
  $('#cadastroSituacao').val(situacao);
  if(ramal!=='null'){
	$('#cadastroRamal').val(ramal);
  }
  

  $("#saveAtivo").html('');
  $("#saveAtivo").html('<span class="fa fa-floppy-o"></span>  ATUALIZAR');

  document.documentElement.scrollTop = 0;
  
}

function clearFields(){
  $('#id').val('');
  $('#cadastroTipo').val(null);
  $('#cadastroEstoque').val(null);
  $('#cadastroSetor').val(null);
  $('#cadastroGPS').val('');
  $('#cadastroDescricao').val('');
  $('#cadastroSerie').val('');
  $('#cadastroPatrimonio').val('');
  $('#cadastroSituacao').val(null);
  $('#cadastroRamal').val(null);
}
</script>
@stop
