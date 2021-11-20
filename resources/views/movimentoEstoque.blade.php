@extends('home')
@section('title', 'Movimento de Ativo')
@section('title2', 'Movimento de Ativo')
@section('content')
<!-- FORMULÀRIO -->

<script src="{{ asset('assets/plugins/select2/js/select2.js') }}"></script>


<div style="justify-content: center;">
<form id="frmMovimento" class="form-group" action="">
  <div class="row">
    <div class="col-md-12">
    <label for="MovimentoAtivo">Buscar Ativo:</label>&nbsp;<span style="color: #f00;">*</span>
        <div class="row">
            <div class="col-md-6">
                <select class="form-control" name="MovimentoAtivo" id="MovimentoAtivo" required multiple="multiple">
                    @if(!empty($loadAtivo))
                        @foreach($loadAtivo as $key => $value)
                            <option value="{{$value['id_objeto']}}">
                                {{$value['descricao']}} - {{$value['numero_serie']}}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-6">
                <a id="btnModal" class="btn btn-info"><i class="fa fa-search"></i> Pesquisar</a>
            </div>
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
        <label for="MovimentoTipo">Tipo de Movimento:</label>&nbsp;<span style="color: #f00;">*</span>
        <select class="form-control" name="MovimentoTipo" id="MovimentoTipo" required>
        <option value="">
        -- SELECIONE --
        </option>
            <option value="empre">
                Empréstimo
            </option>
            <option value="novaS">
                Nova Solicitação
            </option>
            <option value="baixa">
                Baixa de Ativo
            </option>
            <option value="iNovo">
                Item Novo
            </option>
            <option value="troca">
                Troca de Posição
            </option>
        </select>
    </div>
    <div class="col-md-6">
    <label for="MovimentoSetor">Setor de Destino:</label>&nbsp;<span style="color: #f00;">*</span>
    <select class="form-control" name="MovimentoSetor" id="MovimentoSetor" required>
        <option value="">
        -- SELECIONE --
        </option>
        @if(!empty($loadSetores))
            @foreach($loadSetores as $key => $value)
                <option value="{{$value['id_setor']}}">
                    {{$value['nome']}}
                </option>
            @endforeach
        @endif
    </select>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
    <label for="MovimentoSituacao">Situação:</label>&nbsp;<span style="color: #f00;">*</span>
    <select class="form-control" name="MovimentoSituacao" id="MovimentoSituacao" required>
        <option value="">
        -- SELECIONE --
        <option value="1">Bom</option>
        <option value="2">Ruim - gera conserto</option>
        <option value="3">Estragado - sem conserto</option>
        <option value="4">N&atilde;o avaliado</option>
    </select>
    </div>
    <div class="col-md-6">
    <label for="Movimentogps">GPS/BAIA:</label>
    <input type="number" class="form-control" name="Movimentogps" id="Movimentogps" style="border: 1px solid #ced4da;" placeholder="GPS/BAIXA">
    </div>
  </div>
  <div class="row">
     <div class="col-md-6">
    <label for="MovimentoSolicitante">Solicitante:</label>&nbsp;<span style="color: #f00;">*</span>
    <select class="form-control" name="MovimentoSolicitante" id="MovimentoSolicitante" required>
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
    <div class="col-md-6">
        <label for="MovimentoResponsavel">Responsável</label>&nbsp;<span style="color: #f00;">*</span>
        <select class="form-control" name="MovimentoResponsavel" id="MovimentoResponsavel" required>
        <option value="">
        -- SELECIONE --
        </option>
        @if(!empty($loadRegister))
            @foreach($loadRegister as $key => $value)
                <option value="{{$value['id']}}">
                    {{$value['name']}}
                </option>
            @endforeach
        @endif
        </select>
    </div>
  </div>
  <br>
   <div class="row">
    <div class="col-md-12">
    <label for="MovimentoMotivo" style="height: 100%;display: flex;justify-content: center;align-items: center;">Motivo:</label>
        <div class="row">
            <div class="col-md-12" style="height: 100%;display: flex;justify-content: center;align-items: center;">
            <textarea name="MovimentoMotivo" id="MovimentoMotivo" style="width: 660px;height: 96px;"></textarea>
            </div>
        </div>
    </div>
  </div>
  
  <br>
  <div style="height: 100%;display: flex;justify-content: center;align-items: center;">
    <button type="submit" id="saveMovimento" class="btn btn-success"><span class="fa fa-floppy-o"></span> SALVAR</button>
  </div>
</form><!-- FIM FORMULÀRIO -->
</div>
<form id="frmEnvioManutencao" class="form-group" action="">
<!-- MODAL -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="overflow:scroll;">
  <div class="modal-dialog" role="document" style="max-height:600px;overflow:scroll;width:700px;position:relative;top:0px;">
    <div class="modal-content">
        <button style="padding-right:5px;" type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Lista de Ativos</h5>
      </div>
      <div class="modal-body">
        <table class="display" id="tableList" style="width:100%;"> <!-- TABELA -->
            <thead>
                <tr>
                <th scope="col" class="col-sm-1">#</th>
                <th scope="col" class="col-sm-2">Tipo</th>
                <th scope="col" class="col-sm-2">Descrição</th>
                <th scope="col" class="col-sm-2">N° Série</th>
                <th scope="col" class="col-sm-3">Estoque</th>
                <th scope="col" class="col-sm-1">Patrimônio</th>
                <th scope="col" class="col-sm-1">Ações</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table><!-- FIM TABELA -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
        <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
<script>

var check = [];

$(document).on('click', '#btnModal', function(){
       $('#exampleModal').modal({
           backdrop:'static'
       });
   });

$(document).ready(function(){
    $("#MovimentoAtivo").select2({
        placeholder: "-- SELECIONE --",   
        allowClear: false,
        scrollAfterselect:false,
        disabled: true
    });

    /*$("#MovimentoTipo").click(function(){
        var tipo = $("#MovimentoTipo option:selected").val();
        if(tipo == 'E' || tipo == ''){
        document.getElementById("MovimentoSetor").disabled = false;


      }else{
        document.getElementById("MovimentoSetor").disabled = true;
      }
    });*/
});


$(document).on("submit", "#frmMovimento", function(event) {
    event.preventDefault();

    var MovimentoAtivo = $('#MovimentoAtivo').val();
    var MovimentoSolicitante = $('#MovimentoSolicitante').val();
    var MovimentoSetor = $('#MovimentoSetor').val();
    var MovimentoTipo = $('#MovimentoTipo').val();
    var MovimentoResponsavel = $('#MovimentoResponsavel').val();
    var MovimentoMotivo = $('#MovimentoMotivo').val();
    var MovimentoSituacao = $('#MovimentoSituacao').val();
    var Movimentogps = $('#Movimentogps').val();


      $.ajax({
        data: {
            MovimentoAtivo: MovimentoAtivo,
            MovimentoSolicitante: MovimentoSolicitante,
            MovimentoSetor: MovimentoSetor,
            MovimentoTipo: MovimentoTipo,
            MovimentoResponsavel: MovimentoResponsavel,
            MovimentoMotivo: MovimentoMotivo,
            MovimentoSituacao: MovimentoSituacao,
            Movimentogps: Movimentogps,
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/' + 'salvar' + '/' + 'movimento' + '/' + 'estoque',
        success: function(result) {
            alert("SALVO COM SUCESSO!");
            $('input:checkbox').prop('checked', false).uniform();
            $('#MovimentoAtivo').val(null).trigger('change');
            document.getElementById('frmMovimento').reset();
            check.length = 0;
        },
        error: function(result){
          alert("ERRO!");
        }
      });
});  


$(window).load(function () {
    check.length = 0;
    $('#tableList').DataTable({
    	
        "searching": true,
        "paging": true,
        "info": false,
        "ajax": {
          data: { "_token": "{{ csrf_token() }}"},
          method: "get",
          url: '/' + 'load' + '/' + 'ativo',
          dataType: "json"
      },
      "language":{
	   url: "/assets/plugins/datatables/js/Portuguese-Brasil.json"
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
                    return '<div style="width:100%;letter-spacing:10;word-break: break-all;">'+row.numero_serie+'</div>';
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

function okList(id){
    
    const arrayCheck = check.indexOf(id);
    if(arrayCheck == -1){
        check.push(id);
    }else{
        check.splice(arrayCheck, 1);
    }

    $('#MovimentoAtivo').val(check);
    $('#MovimentoAtivo').trigger('change');

}
</script>
@stop
