@extends('home')
@section('title', 'Relatórios de Ativos')
@section('title2', 'Relatórios de Ativos')
@section('content')
<script src="{{ asset('assets/plugins/select2/js/select2.js') }}"></script>

<div class="form-group">
    <div class="row">
      <div class="col-md-4">
        
        <label for="idSetor">Setor:</label>
        <select class="form-control select2" name="idSetor" id="idSetor" multiple="multiple">
            @if(!empty($setor))
            @foreach($setor as $key => $value)
                <option value="{{$value['id_setor']}}">
                    {{$value['nome']}}
                </option>
            @endforeach
            @endif
        </select>
    
      </div>

      <div class="col-md-4">
      
        <label for="tipoAtivo">Tipo de Ativo:</label>
        <select class="form-control select2" name="idTipoAtivo" id="idTipoAtivo" multiple="multiple">
            @if(!empty($tipoAtivo))
            @foreach($tipoAtivo as $key => $value)
                <option value="{{$value['id_ativo']}}">
                    {{$value['nome']}}
                </option>
            @endforeach
            @endif
        </select>
    
      </div>

      <div class="col-md-2">
        <button type="button" onClick="atualizaTabela()" style="position:relative;top:30px;" 
        id="filtro" class="btn btn-success"><span class="fa fa-filter">
        </span> Filtrar</button>
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
      <th scope="col" class="col-sm-2">Descrição</th>
      <th scope="col" class="col-sm-1">N° Série</th>
      <th scope="col" class="col-sm-1">Baia</th>
      <th scope="col" class="col-sm-2">Empresa</th>
      <th scope="col" class="col-sm-1">Situação</th>
      <th scope="col" class="col-sm-1">Patrimônio</th>
      <th scope="col" class="col-sm-2">Setor</th>
      <th scope="col" class="col-sm-1">Nº de Consertos</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </tbody>
</table><!-- FIM TABELA -->
<script>
$(window).load(function () {
    $(".select2").select2({
      placeholder: "-- SELECIONE --",   
      allowClear: true,
      scrollAfterselect:true
    });

    $('#tableRelatorios').DataTable({
        dom: 'Bfrtip',
            buttons: [
                'csvHtml5',
                'excelHtml5'
	],
	"pageLength": 50, 
        "searching": true,
        "paging": true,
        "info": false,
        "scrollX": true,
        "ajax": {
          data: {
                    idTipoAtivo: function(){ if($("#idTipoAtivo").val() != null){ return $("#idTipoAtivo").val()}else{ return 0;}; },
                    idSetor: function(){ if($("#idSetor").val() != null){ return $("#idSetor").val()}else{ return 0;}; },
                    "_token": "{{ csrf_token() }}" 
                },
          method: "get",
          url: '/load/relatorios/ativos',
          dataType: "json"
    	},
        "drawCallback": function (settings) {
            $("#total").html("Total: "+settings.aoData.length);
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
                    if(row.situacao == 1){
                        return 'Bom' ;
                    }else if(row.situacao == 2){
                        return 'Ruim - gera conserto' ;
                    }else if(row.situacao == 3){
                        return 'Estragado - sem conserto' ;
                    }else if(row.situacao == 4){
                        return 'N\u00e3o avaliado' ;
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
                    return row.setor ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.conserto ;
                }
            },
          ]
    });       
});

function atualizaTabela(){
    $('#tableRelatorios').DataTable().ajax.reload();
}

</script>
@stop
