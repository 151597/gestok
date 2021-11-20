@extends('home')
@section('title', 'Ajuste de Estoque')
@section('title2', 'Ajuste de Estoque')
@section('content')

<script src="{{ asset('assets/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') }}"></script>

<!-- FORMULÀRIO -->

<form id="frmAjuste" class="form-group" action="">
  <input type="hidden" name="id" id="id">
  <div class="row">
    <div class="col-md-4">
    <label for="ajusteProduto">Produto</label>&nbsp;<span style="color: #f00;">*</span>
    <select class="form-control" name="ajusteProduto" id="ajusteProduto" style="border: 1px solid #ced4da;">
        <option value="">
        -- SELECIONE --
        </option>
        @if(!empty($loadProduto))
        @foreach($loadProduto as $key => $value)
            <option value="{{$value['id_produto']}}">
                {{$value['descricao']}}
            </option>
        @endforeach
        @endif
    </select>
    </div>
    <div class="col-md-4">
    <label for="ajusteQuantidade">Quantidade</label>&nbsp;<span style="color: #f00;">*</span>
    <input type="text" class="form-control" name="ajusteQuantidade" id="ajusteQuantidade" placeholder="Quantidade" required>
    </div>
    <div class="col-md-4">
    <label for="ajusteMovimento">Tipo de Movimento:</label><span style="color: #f00;">*</span>
    <select class="form-control" name="ajusteMovimento" id="ajusteMovimento" required>
        <option value="">
        -- SELECIONE --
        </option>
            <option value="E">
                Entrada
            </option>
            <option value="S">
                Saída
            </option>
    </select>
    </div>
  </div>
  <br>
  <div style="height: 100%;display: flex;justify-content: center;align-items: center;">
    <button type="submit" id="saveAjuste" class="btn btn-success"><span class="fa fa-floppy-o"></span> SALVAR</button>
  </div>
</form><!-- FIM FORMULÀRIO -->

<script>

/*$(document).ready(function(){
    $('#cadastroTelefone').mask('(00) 0000-0000');
});*/

$(document).on("submit", "#frmAjuste", function(event) {
    event.preventDefault();

    var id = $('#id').val();
    var ajusteProduto = $('#ajusteProduto').val();
    var ajusteQuantidade = $('#ajusteQuantidade').val();
    var ajusteMovimento = $('#ajusteMovimento').val();

      $.ajax({
        data: {
          id:id,
          ajusteProduto: ajusteProduto,
          ajusteQuantidade: ajusteQuantidade,
          ajusteMovimento: ajusteMovimento,
            "_token": "{{ csrf_token() }}"
        },
        method: "post",
        url: '/' + 'salvar' + '/' + 'ajuste' + '/' + 'estoque',
        success: function(result) {
            alert("SALVO COM SUCESSO!");
            $("#saveAjuste").html('<span class="fa fa-floppy-o"></span>  SALVAR');
            clearFields();
        },
        error: function(result){
          alert("ERRO!");
        }
      });
});   


function clearFields(){
  $('#id').val('');
  $('#ajusteProduto').val(null);
  $('#ajusteQuantidade').val(null);
  $('#ajusteMovimento').val('');
}
</script>
@stop
