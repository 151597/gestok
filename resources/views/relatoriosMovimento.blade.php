@extends('home')
@section('title', 'Relatório de Movimentação de Ativos')
@section('title2', 'Relatório de Movimentação de Ativos')
@section('content')

<div class="form-group">
    <div class="row">
        <div class="col-md-4">
            <label for="emprestimo">Situação:</label>
            <select class="form-control" onChange="$('#tableRelatorioEstoque').DataTable().ajax.reload();" name="emprestimo" id="emprestimo">
                <option value="-1">-- SELECIONE --</option>
                <option value="1">Emprestado</option>
                <option value="0">Regular</option>
            </select>
        </div>
    </div>
</div>
<table class="display" id="tableRelatorioEstoque" style="width:100%;"> 
  <thead>
    <tr>
      <th scope="col" class="col-sm-1 form-group">#</th>
      <th scope="col" class="col-sm-1 form-group">Tipo de Movimento</th>
      <th scope="col" class="col-sm-1 form-group"">Setor Solicitante</th>
      <th scope="col" class="col-sm-2 form-group"">Solicitante</th>
      <th scope="col" class="col-sm-2 form-group"">Responsável</th>
      <th scope="col" class="col-sm-3 form-group"">Ativo</th>
      <th scope="col" class="col-sm-3 form-group"">Motivo</th>
      <th scope="col" class="col-sm-1 form-group"">Situação</th>
      <th scope="col" class="col-sm-1 form-group"">Data</th>
  </thead>
  <tbody>
  </tbody>
</table>

<script>

$(window).load(function () {

    $('#tableRelatorioEstoque').DataTable({
        "searching": true,
        "paging": true,
        "info": false,
        "scrollX": true,
        order:[[0, 'ASC']],
        "ajax": {
            data: { emprestimo:function(){return $("#emprestimo").val();},
            "_token": "{{ csrf_token() }}" },
            method: "get",
            url: '/load/relatorio/movimento',
            dataType: "json"
        },
        "language":{
            url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
        },
        "columnDefs":[{
            targets:[0],
            visible:false
        }],
       
        "columns": [
            {
                mRender: function (data, type, row) {
                    return row.id ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.movimento ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.setor ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.solicitante ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.responsavel ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.descricao+" - "+row.numero_serie ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.motivo;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.emprestimo;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.data ;
                }
            }
        ]
    });

        
});



</script>

@stop
