@extends('home')
@section('title', 'Relatório de Orçamentos')
@section('title2', 'Relatório de Orçamentos')
@section('content')
<div class="row">
  <div class="col-md-9">
  <form id="frmData" class="form-inline" action="">
    <label for="idOrcamento">Orçamento:</label><span style="color: #f00;">*</span>
    <select class="form-control" onChange="$('#tableRelatorioOrcamento').DataTable().ajax.reload();loadFornecedorColumn();" name="idOrcamento" id="idOrcamento">
        <option value="">
        -- SELECIONE --
        </option>
        @if(!empty($loadIdOrcamento))
        @foreach($loadIdOrcamento as $key => $value)
            <option value="{{$value['id_orcamento']}}">
                {{$value['id_orcamento']}}
            </option>
        @endforeach
        @endif
    </select>
  </div>
  </form>
</div>
<br>
<table class="display" id="tableRelatorioOrcamento" style="width:100%;"> 
  <thead>
    <tr>
      <th id="0" class="col-sm-2">N° Orçamento</th>
      <th id="1" class="col-sm-2">Produto/Serviço</th>
      <th id="forn2" class="col-sm-2">Fornecedor</th>
      <th id="forn3" class="col-sm-2">Fornecedor</th>
      <th id="forn4" class="col-sm-2">Fornecedor</th>
      <th id="forn5" class="col-sm-2">Fornecedor</th>
    </tr>
  </thead>
  <tbody>
    <tr>
    </tr>
  </tbody>
</table>
<script>
$(window).load(function () {

    $('#tableRelatorioOrcamento').DataTable({
        dom: 'Bfrtip',
            buttons: [
                {   extend:'csvHtml5',
                    exportOptions:{
                        columns: ':visible'
                    }
                },
                {
                    extend:'excelHtml5',
                    exportOptions:{
                        columns: ':visible'
                    }
                }
        ],
        "searching": true,
        "paging": true,
        "info": false,
        "scrollX": true,
        "ajax": {
        data: { 
                id_orcamento:function(){ return $('#idOrcamento').val();},
                "_token": "{{ csrf_token() }}" 
            },
        method: "get",
        url: '/load/relatorios/orcamento',
        dataType: "json"
    },
    "language":{
        url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
    },
        "columnDefs":[
                { "visible": false, "targets": [2,3,4,5]}
               
             ],
        "columns": [
            {
                mRender: function (data, type, row) {

                        return row.id_orcamento;
                }
            },
            {
                mRender: function (data, type, row) {
                      
                        return row.descricao;
                }
            },
            {
                mRender: function (data, type, row) {

                    if(row.valor_0 != null){
                        return 'R$'+row.valor_0.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,").replace(/[,.]/g, m => (m === ',' ? '.' : ','));
                    }else{
                        return "";
                    }
                }
            },
            {
                mRender: function (data, type, row) {

                    if(row.valor_1 != null){
                        return 'R$'+row.valor_1.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,").replace(/[,.]/g, m => (m === ',' ? '.' : ','));
                    }else{
                        return "";
                    }
                }
            },
            {
                mRender: function (data, type, row) {
                    if(row.valor_2 != null){
                        return 'R$'+row.valor_2.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,").replace(/[,.]/g, m => (m === ',' ? '.' : ','));
                    }else{
                        return "";
                    }
                    
                }
            },
            {
                mRender: function (data, type, row) {
                    if(row.valor_3 != null){
                        return 'R$'+row.valor_3.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,").replace(/[,.]/g, m => (m === ',' ? '.' : ','));
                    }else{
                        return "";
                    }
                }
            }
            ]
    });
});

function loadFornecedorColumn(){
    
    $.ajax({
        data: {
            id_orcamento:function(){ return $('#idOrcamento').val();},
            "_token": "{{ csrf_token() }}"
        },
        method: "get",
        url: '/' + 'load' + '/' + 'relatorios' + '/' + 'orcamento',
        dataType:"json",
        success: function(result) {
            var table = $('#tableRelatorioOrcamento').DataTable();

            table.columns([2,3,4,5]).visible(false);

            if(result.fornecedor != 0){
                /*$.each(result.fornecedor, function(i){   
                });*/

                for(var j=0;j<result.fornecedor.length;j++){
                   
                    var soma = j + 2;

                    if(soma<6){
                        table.column(soma).visible(true);
                        
                    }

                    $('#forn'+soma).html(result.fornecedor[j].razao_social);
                    //alert(soma);
                }
            }
        },
        error: function(result){
                alert("ERRO!");
        }
      });
}

</script>

@stop
