@extends('home')
@section('title', 'Envio para Manutenção')
@section('title2', 'Envio para Manutenção')
@section('content')

<script src="{{ asset('assets/plugins/select2/js/select2.js') }}"></script>


<!-- FORMULÀRIO -->
<form id="frmFiltrar" class="form-group" action="">
  <div class="row">
            <div class="col-md-4">
                <label for="EnvioAtivos">Ativos:</label>&nbsp;<span style="color: #f00;">*</span>
                <select class="form-control" name="EnvioAtivos" id="EnvioAtivos" required multiple="multiple">
                        @if(!empty($loadTipoAtivo))
                        @foreach($loadTipoAtivo as $key => $value)
                            <option value="{{$value['id_objeto']}}">
                                {{$value['id_objeto']}} - {{$value['tipo']}}
                            </option>
                        @endforeach
                        @endif
                </select>
            </div>
  </div>
</form><!-- FIM FORMULÀRIO -->
<br><br>
<table class="display" id="tableManutencao" style="width:100%;"> <!-- TABELA -->
  <thead>
    <tr>
      <th scope="col" class="col-sm-2">#</th>
      <th scope="col" class="col-sm-2">Tipo</th>
      <th scope="col" class="col-sm-2">Descrição</th>
      <th scope="col" class="col-sm-2">N° Série</th>
      <th scope="col" class="col-sm-2">Empresa</th>
      <th scope="col" class="col-sm-2">Patrimônio</th>
      <th scope="col" class="col-sm-2">Ações</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table><!-- FIM TABELA -->
<br>
<form id="frmEnvioManutencao" class="form-group" action="">
    <div class="row">
        <div class="col-md-4">
                <label for="EnvioResponsavel">Responsável:</label>&nbsp;<span style="color: #f00;">*</span>
                <select class="js-states form-control" name="EnvioResponsavel" id="EnvioResponsavel" required>
                    <option value="">
                    -- SELECIONE --
                    </option>
                    @if(!empty($loadPessoa))
                    @foreach($loadPessoa as $key => $value)
                        <option value="{{$value['id_pessoa']}}">
                            {{$value['nome']}}
                        </option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-4" style="top:25px;">
                <button type="submit" id="saveMovimento" class="btn btn-success"><span class="fa fa-floppy-o"></span> SALVAR</button>
            </div>       
    </div>
</form> 
<script>
var check = [];

$(document).ready(function(){
    $("#EnvioAtivos").select2({
        placeholder: "-- SELECIONE --",   
        allowClear: false,
        scrollAfterselect:false,
        disabled: true
    });
});


$(window).load(function () {

    $('#tableManutencao').DataTable({
        "searching": true,
        "paging": true,
        "info": false,
        "scrollX": true,
        "ajax": {
          data: { "_token": "{{ csrf_token() }}"},
          method: "get",
          url: '/' + 'load' + '/' + 'ativo'+ '/' + 'manutencao',
          dataType: "json"
      	},
	"language":{
	  url:'/assets/plugins/datatables/js/Portuguese-Brasil.json'
	},
      "columnDefs":[
                { "orderable": false, "targets": 6}
               
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
                    return row.estoque ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return row.patrimonio ;
                }
            },
            {
                mRender: function (data, type, row) {
                    return '<input onClick="okList('+row.id_objeto+')" style="position:relative;left:10px;" type="checkbox" class="form-check-input" name="check" id="' + row.id_objeto + '">'
                }
            },
          ]
    });       
});

function filtroAtivos(){
    $('#tableManutencao').DataTable().ajax.reload();
}


$(document).on("submit", "#frmEnvioManutencao", function(event) {
    event.preventDefault();

        var check = [];

    $("input[name='check']").each(function() {
        if ($(this).prop("checked")) {
            check.push($(this).prop("id"));
        }
    });
    console.log(check);
    var EnvioResponsavel = $('#EnvioResponsavel').val();
    var EnvioAtivos = $('#EnvioAtivos').val();


      $.ajax({
        data: {
            EnvioResponsavel: EnvioResponsavel,
            EnvioAtivos: EnvioAtivos,
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/' + 'salvar' + '/' + 'envio' + '/' + 'manutencao',
        success: function(result) {
            alert("SALVO COM SUCESSO!");
            $('#tableManutencao').DataTable().ajax.reload();
            $('#EnvioResponsavel').val('');
            $('#EnvioAtivos').val('');
            $('#EnvioAtivos').trigger('change');
        },
        error: function(result){
          alert("ERRO!");
        }
      });
});   

function okList(id){
    const arrayCheck = check.indexOf(id);
    if(arrayCheck == -1){
        check.push(id);
    }else{
        check.splice(arrayCheck, 1);
    }

    $('#EnvioAtivos').val(check);
    $('#EnvioAtivos').trigger('change');

}

</script>
@stop
