@extends('home')
@section('title', 'QR Code')
@section('title2', 'QR Code')
@section('content')

<script src="{{ asset('assets/plugins/select2/js/select2.js') }}"></script>

<div style="justify-content: center;">
<form id="frmQrCode" class="form-group" action="{{route('qrcode.pdf')}}">
  @csrf
  <div class="row">   
    <div class="col-md-2">
      <label for="tamQr">Tamanho do QR Code</label>&nbsp;<span style="color: #f00;">*</span>
      <input type="number" class="form-control" name="tamQr" id="tamQr" style="border: 1px solid #ced4da;" placeholder="mm" required>
    </div>
    <div class="col-md-2">
      <label for="qntPorLinha">Quantidade por linha</label>&nbsp;<span style="color: #f00;">*</span>
      <input type="number" class="form-control" name="qntPorLinha" id="qntPorLinha" style="border: 1px solid #ced4da;" required>
    </div>
    <div class="col-md-2">
      <label for="tamFonte">Tamanho da fonte</label>&nbsp;<span style="color: #f00;">*</span>
      <input type="number" class="form-control" name="tamFonte" id="qntPorLintamFonteha" style="border: 1px solid #ced4da;" required>
    </div>
    <div class="col-md-3">
      <label for="info">Informação a ser inserida</label>&nbsp;<span style="color: #f00;">*</span>
      <select class="form-control select2" name="info[]" id="info" multiple="multiple">
        <option value="descricao">Descrição</option>
        <option value="numero_serie">Número de Série</option>
        <option value="patrimonio">Patrimônio</option>
      </select>
    </div>
    <div class="col-md-3">
      <label for="textoQr">Texto</label></span>
      <input type="text" class="form-control" name="textoQr" id="textoQr" style="border: 1px solid #ced4da;" maxlength="25">
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-md-12">
    <label for="qrAtivo">Buscar Ativo:</label>&nbsp;<span style="color: #f00;">*</span>
        <div class="row">
            <div class="col-md-10">
                <select class="form-control select2" name="qrAtivo[]" id="qrAtivo" required multiple="multiple">
                    @if(!empty($loadAtivo))
                        @foreach($loadAtivo as $key => $value)
                            <option value="{{$value->id_objeto}}">
                                {{$value->descricao}} - {{$value->numero_serie}} - {{$value->patrimonio}}
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
  <div style="height: 100%;display: flex;justify-content: center;align-items: center;">
    <button type="submit" class="btn btn-success"><span class="fa fa-floppy-o"></span> Gerar QR Codes</button>
  </div>
</form><!-- FIM FORMULÀRIO -->
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-height:600px;overflow-y:scroll;width:700px;position:relative;top:0px;">
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
                <th scope="col" class="col-sm-3">Empresa</th>
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
    $(".select2").select2({
        placeholder: "-- SELECIONE --",   
        allowClear: true,
        scrollAfterselect:false
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


$(window).load(function () {
    check.length = 0;
    $('#tableList').DataTable({
        lengthMenu: [[10, 25, 50, 100, 250, 500, -1], [10, 25, 50, 100, 250, 500, 'Todos']],
        "searching": true,
        "paging": true,
        "info": false,
        "ajax": {
          data: { "_token": "{{ csrf_token() }}"},
          method: "get",
          url: '/load/ativo',
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

    $('#qrAtivo').val(check);
    $('#qrAtivo').trigger('change');

}
</script>
@stop
