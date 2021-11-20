@extends('home')
@section('title', 'Retorno da Manutenção')
@section('title2', 'Retorno da Manutenção')
@section('content')
<!-- FORMULÀRIO -->
<script src="{{ asset('assets/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') }}"></script>

<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js') }}"></script>
<link href="{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet" type="text/css"/>

<form id="frmRetornoManutencao" class="form-inline" action="">
    <div class="form-group">
        <label for="DataEnvio">Data de Envio:</label>&nbsp;<span style="color: #f00;">*</span>
        <input type="text" id="DataEnvio" name="DataEnvio" class="form-control">
    </div>
    &nbsp
    <span>
         <a type="submit" onClick="filtroRetorno()" id="filtrar" class="btn btn-success"><span class="fa fa-search"></span> FILTRAR</a>
    </span>
</form><!-- FIM FORMULÀRIO -->
<br>
<form action="{{route('relatorios.guia')}}" method="get" target="_blank">
    @csrf
    <table class="display" id="tableRetorno" style="width:100%;"> <!-- TABELA -->
        <thead>
            <tr>
            <th scope="col" class="col-sm-1">#</th>
            <th scope="col" class="col-sm-2">Tipo de Ativo</th>
            <th scope="col" class="col-sm-2">Ativo</th>
            <th scope="col" class="col-sm-2">Empresa</th>
            <th scope="col" class="col-sm-2">N° Série</th>
            <th scope="col" class="col-sm-1">Patrimônio</th>
            <th scope="col" class="col-sm-1">Envio</th>
            <th scope="col" class="col-sm-1">Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <th scope="row"></th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                </a>
            </td>
            </tr>
        </tbody>
    </table><!-- FIM TABELA -->
    <div class="row">
        <div style="display: flex;justify-content: center;align-items: center;">
            <div class="col-sm-4">
                <button type="submit" class="btn btn-info btn-block">Guia de Manutenção</button>
            </div>
        </div>
    </div>
</form>
<br>
<div class="row">
    <div style="display: flex;justify-content: center;align-items: center;">
        <div class="col-sm-2">
            <button onClick="manutencao(1)" type="button" class="btn btn-success btn-block"><span class="glyphicon glyphicon-ok-sign"></span>&nbsp;&nbsp;Teve Conserto</button>
        </div>
        <div class="col-sm-2">
            <button onClick="manutencao(0)" type="button" class="btn btn-danger btn-block"><span class="glyphicon glyphicon-warning-sign"></span>&nbsp;&nbsp;Não Teve Conserto</button>
        </div>
    </div>
</div>
<script>

$(document).ready(function (){
    $("#DataEnvio").mask("99/99/9999");
    $("#DataEnvio").datepicker({
    	format: 'dd/mm/yyyy',
	autoclose:true,
    });
});

$(window).load(function () {
    $('#tableRetorno').DataTable({
        "searching": true,
        "paging": true,
        "info": false,
        "scrollX": true,
        "ajax": {
          data: { "_token": "{{ csrf_token() }}", 
                retorno: function(){ return $("#DataEnvio").val()}
                },
          method: "get",
          url: '/' + 'load' + '/' + 'retorno' + '/' + 'manutencao',
          dataType: "json"
	},
	"language":{
	   url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
	},
      	"columnDefs":[
                { "orderable": false, "targets": 7}
             ],
      	"columns": [
            {
                mRender: function (data, type, row) {
                    return row.id_manutencao ;
                }
            },
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
                    return row.estoque ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.numero_serie ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.patrimonio ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.envio ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return '<input style="position:relative;left:10px;" type="checkbox" class="form-check-input" name="check[]" id="' + row.id_manutencao + '" value="' + row.id_manutencao + '">'
                }
            },
          ]
    });
        
});


function manutencao(tipo){

    var id_manutencao = [];

    $("input[name='check[]']").each(function() {
        if ($(this).prop("checked")) {
            id_manutencao.push($(this).prop("id"));
        }
    });
    
    $.ajax({
        data: {
            tipo:tipo,
            id_manutencao: id_manutencao,
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/salvar/retorno/manutencao',
        success: function(result) {
            alert("SITUAÇÃO ATUALIZADA!");
            $('#tableRetorno').DataTable().ajax.reload();
        },
        error: function(result){
        alert("ERRO!");
        }
    });

}


function filtroRetorno(){
    $('#tableRetorno').DataTable().ajax.reload();
}
</script>
@stop
