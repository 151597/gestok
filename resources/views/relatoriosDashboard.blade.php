@extends('home')
@section('title', 'Dashboard')
@section('title2', 'Dashboard')
@section('content')

<script src="{{ asset('assets/plugins/select2/js/select2.js') }}"></script>
<script src="{{ asset('assets/plugins/chartsjs/Chart.min.js') }}"></script>
<script src="{{ asset('assets/plugins/chartsjs/chartjs-plugin-labels.js') }}"></script>


<form id="frmDash" class="form-group" action="">
  <div class="form-group">
    <div class="row">
      <div class="col-md-4">
        
        <label for="setor">Setor:</label>&nbsp;<span style="color: #f00;">*</span>
        <select class="form-control" name="idSetor" id="idSetor" multiple="multiple">
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
      
        <label for="tipoAtivo">Tipo de Ativo:</label>&nbsp;<span style="color: #f00;">*</span>
        <select class="form-control" name="idTipoAtivo" id="idTipoAtivo" multiple="multiple">
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
        <button type="submit" style="position:relative;top:30px;" 
        id="filtro" class="btn btn-success"><span class="fa fa-filter">
        </span> Filtrar</button>
      </div>
    </div>
</form>
<br>
<br>
<div class="row">
    <div class="col-md-5">
      <div class="panel-white" style="padding:10px;">
        <h4 id="totalAt"></h4>
        <h4  id="totalMa">Total em Manutenção: </h4>
      </div>

    <br>
      <div class="panel panel-white">
      
          <canvas id="situacaoAtivos" width="100" height="100"></canvas>
        
      </div>

    </div>

    <div class="col-md-7">
    
      <div class="panel panel-white">
        
          <canvas id="localAtivos" width="100" height="85"></canvas>
        
      </div>
  
  </div>

 
</div>


<br>
<div class="row chart-container" >
  
</div>


<script>

var graficoPizza = null;
var graficoBarra = null;

var labelAtivos = [];
var totalAtivos = [];

var labelLocal = [];
var totalLocal = [];

$(document).on("submit", "#frmDash", function(event) {
    labelAtivos.length = 0;
    totalAtivos.length = 0;

    labelLocal.length = 0;
    totalLocal.length = 0;

    painel();
    
}); 


$(document).ready(function(){
    painel();

    $("#idTipoAtivo").select2({
      placeholder: "-- SELECIONE --",   
      allowClear: true,
      scrollAfterselect:true
    });

    $("#idSetor").select2({
      placeholder: "-- SELECIONE --",   
      allowClear: true,
      scrollAfterselect:true
    });

    graficoPizza = new Chart(document.getElementById("situacaoAtivos"), {
    type: 'pie',
    data: {
      labels: labelAtivos,
      datasets: [{
        backgroundColor: ["#87CEFA", "#DEB887","#FFB6C1","#7FFFD4"],
        data: totalAtivos
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true, 
      legend: {
        display:true,
        position: "bottom",
      },
      layout: {
        padding: {
          right: 10,
          left: 10,
          top: 10,
          bottom: 10
        }
      },
      title: {
        display: true,
        text: 'SITUAÇÃO DOS ATIVOS',
        fontSize: 15
      },
      plugins: {
        labels: {
          render: 'percentage',
          precision: 1,
          position: 'border'
        }
      }
    }
  });

  graficoBarra = new Chart(document.getElementById("localAtivos"), {
    type: 'bar',
    data: {
      labels: labelLocal,
      datasets: [{
        backgroundColor: ["#003f5c", "#2f4b7c","#665191","#a05195", "#d45087", "#f95d6a", "#ff7c43", "#ffa600",
        "#003f5c", "#2f4b7c","#665191","#a05195", "#d45087", "#f95d6a", "#ff7c43", "#ffa600"],
        data: totalLocal
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true, 
      legend: {
        display:false,
        position: "bottom",
      },
      layout: {
        padding: {
          right: 10,
          left: 10,
          top: 10,
          bottom: 10
        }
      },
      title: {
        display: true,
        text: 'LOCAL DOS ATIVOS',
        fontSize: 15
      },
      plugins: {
        labels: {
          render: 'value'
        }
      },
      scales: {
          xAxes: [{
              stacked: false,
              beginAtZero: true,
              scaleLabel: {
                  labelString: 'Month'
              },
              ticks: {
                  stepSize: 1,
                  min: 0,
                  autoSkip: false
              }
          }]
      }
    }
  });

});

function painel(){

  
  event.preventDefault();

  var setor = $('#idSetor').val();
  var tipoAtivo = $('#idTipoAtivo').val();

  $.ajax({
    data: {
      setor:setor,
      tipoAtivo: tipoAtivo,
        "_token": "{{ csrf_token() }}"
    },
    method: "post",
    url: 'dashboard/dashTotal',
    success: function(result) {
        var data = JSON.parse(result);

        
        $.each(data.pizza, function(i){
          
          labelAtivos[i] = data.pizza[i].label;
          totalAtivos[i] = data.pizza[i].total;
          
        })

        $.each(data.barra, function(i){
          
          labelLocal[i] = data.barra[i].label;
          totalLocal[i] = data.barra[i].total;
          
        })
        
        $('#totalAt').html("Total de Ativos: "+data.objAtivo.ativo);
        $('#totalMa').html("Total em Manutenção: "+data.objManutencao.manutencao); 
        graficoPizza.update();
        graficoBarra.update();
    },
    error: function(result){
      alert("ERRO!");
    }
  });
}

</script>

@stop