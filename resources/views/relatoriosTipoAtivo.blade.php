@extends('home')
@section('title', 'Relatório de Tipos de Ativos')
@section('title2', 'Relatório de Tipos de Ativos')
@section('content')
<div class="form-group">
    <div class="row">
      <div class="col-md-4">
        
        <label for="idSetor">Setor:</label>
        <select class="form-control" onChange="atualizaTabela()" name="idSetor" id="idSetor">
          <option value="-1">
            -- SELECIONE --
          </option>
          @if(!empty($setor))
            @foreach($setor as $key => $value)
                <option value="{{$value['id_setor']}}">
                    {{$value['nome']}}
                </option>
            @endforeach
          @endif
          <option value="21">
            Não Cadastrado
          </option>
        </select>
    
      </div>
      <div class="col-md-4">
        
        <label for="idEmpresa">Empresa:</label>
        <select class="form-control" onChange="atualizaTabela()" name="idEmpresa" id="idEmpresa">
          <option value="-1">
            -- SELECIONE --
          </option>
          @if(!empty($empresa))
            @foreach($empresa as $key => $value)
                <option value="{{$value['id_estoque']}}">
                    {{$value['nome']}}
                </option>
            @endforeach
          @endif
          <option value="0">
            Outros
          </option>
        </select>
    
      </div>
      <div class="col-md-4">
        
        <label for="situacao">Situação:</label>
        <select class="form-control" onChange="atualizaTabela()" name="situacao" id="situacao">
          <option value="-1">
            -- SELECIONE --
          </option> 
            <option value="1">Bom</option>
            <option value="2">Ruim, mas gera conserto</option>
            <option value="3">Estragado, sem conserto</option>
            <option value="4">Não Avaliado</option>
        </select>
    
      </div>
    </div>
</div>

<div class="panel-white col-md-2" >
    <h4  id="total">Total: </h4>
</div>

<br><br><br>
<table class="display" id="tableRelatorios" style="width:100%;"> <!-- TABELA -->
  <thead>
    <tr>
      <th scope="col" class="col-sm-2">Tipo de Ativo</th>
      <th scope="col" class="col-sm-2">Quantidade</th>
    </tr>
  </thead>
  <tbody>       
  </tbody>
</table><!-- FIM TABELA -->

<script>

$(window).load(function () {


  $('#tableRelatorios').DataTable({
        dom: 'Bfrtip',
            buttons: [
                'csvHtml5',
                'excelHtml5'
	],
	"pageLength": 50, 
        "searching": true,
        "paging": false,
        "info": false,
        "scrollX": true,
        "ajax": {
          data: {
                    idSetor: function(){ if($("#idSetor").val() != null){ return $("#idSetor").val()} },
                    idEmpresa: function(){ if($("#idEmpresa").val() != null){ return $("#idEmpresa").val()} },
                    situacao: function(){ if($("#situacao").val() != null){ return $("#situacao").val()} },
                    "_token": "{{ csrf_token() }}" 
                },
          method: "get",
          url: '/load/relatorios/tipo/ativos',
          dataType: "json"
    	},
        
	"language":{
    	   url:"/assets/plugins/datatables/js/Portuguese-Brasil.json"
    	},
      	"columns": [
            {
                mRender: function (data, type, row) {
                    return row.nome ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.quantidade ;
                }
            },
          ]
    });   
    quantidadeTotal();
});   

function atualizaTabela(){
  quantidadeTotal();
  $('#tableRelatorios').DataTable().ajax.reload();
    
}

function quantidadeTotal(){
  $.ajax({
      data: {
                idSetor: function(){ if($("#idSetor").val() != null){ return $("#idSetor").val()} },
                idEmpresa: function(){ if($("#idEmpresa").val() != null){ return $("#idEmpresa").val()} },
                situacao: function(){ if($("#situacao").val() != null){ return $("#situacao").val()} },
                "_token": "{{ csrf_token() }}" 
            },
      method: "get",
      url: '/load/relatorios/tipo/ativos',
      dataType: "json",
      success: function(result) {
        $("#total").html("Total: "+result.total);
      }
  });
}

</script>
@stop
