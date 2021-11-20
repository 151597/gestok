<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Authentication Routes...
/*
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
*/

Auth::routes();

Route::middleware(['auth'])->group(function(){

    Route::get('load/register', 'Auth\RegisterController@loadRegister')->name('load.register');
    Route::post('salvar/usuario', 'Auth\RegisterController@register')->name('salvar.usuario');

    
    Route::get('/', function () {
        return view('home');
    });


    //rotas exclusivas para administradores, demais rotas estarão em outros
    //grupos de middlewares ou circundadas apenas pela middleware de rota
    Route::middleware(['accessAdmin'])->group(function(){
        //Route::get('/cadastro/estoque', 'EstoqueController@cadastroEstoque')->name('cadastro.estoque');
        Route::delete('delete/register/{id}', 'Auth\RegisterController@deleteRegister')->name('delete.register');
    });



    Route::middleware(['accessCompras'])->group(function(){

        Route::get('/cadastro/fornecedor', 'FornecedorController@cadastroFornecedor')->name('cadastro.fornecedor');
        Route::get('/cadastro/produto', 'ProdutosController@cadastroProdutos')->name('cadastro.produtos');
        Route::get('/cadastro/importacao', 'ImportacaoController@importacaoNota')->name('importacao.nota');
        Route::get('/cadastro/armarios', 'ArmarioController@cadastroArmarios')->name('cadastro.armarios');
        Route::get('/relacionamento/armarios', 'ArmarioController@relacaoArmarios')->name('cadastro.relacao.armarios');
        Route::get('atualiza_horarios', 'ArmarioController@atualizaHorarios')->name('atualiza.horarios');


        Route::get('/relatorios/notas', 'ImportacaoController@relatorioNotas')->name('relatorios.notas');
        Route::get('/relatorios/estoque', 'AjusteEstoqueController@relatorioEstoque')->name('relatorios.estoque');
        Route::get('/relatorios/pedido', 'CompraController@relatorioPedido')->name('relatorios.pedido');
        Route::get('/relatorios/orcamento', 'CompraController@relatorioOrcamento')->name('relatorios.orcamento');
        Route::get('relatorio/falhas', 'ArmarioController@relatorioDeFalhas')->name('relatorio.falhas');


        Route::get('/ajuste/estoque', 'AjusteEstoqueController@ajusteEstoque')->name('ajuste.estoque');
        Route::get('/pedido/compra', 'CompraController@pedidoCompra')->name('pedido.compra');
        Route::get('/orcamentos/compra', 'CompraController@orcamentos')->name('orcamentos.compra');


        Route::post('salvar/fornecedor', 'FornecedorController@salvarFornecedor')->name('salvar.fornecedor');
        Route::post('salvar/produto', 'ProdutosController@salvarProduto')->name('salvar.produto');
        Route::post('salvar/ajuste/estoque', 'AjusteEstoqueController@salvarAjuste')->name('salvar.ajuste.estoque');
        Route::post('salvar/pedido/compra', 'CompraController@salvarPedido')->name('salvar.pedido.compra');
        Route::post('salvar/orcamento/compra', 'CompraController@salvarOrcamento')->name('salvar.orcamento.compra');
        Route::post('salvar/orcamento/pedido', 'CompraController@orcamentoPedido')->name('salvar.orcamento.pedido');


        Route::post('salvar/pagamento/compra', 'CompraController@salvarPagamento')->name('salvar.pagamento.compra');
        Route::post('salvar/XML', 'ImportacaoController@salvarXML')->name('salvar.xml');
        Route::post('salvar/relacionamento', 'ImportacaoController@salvarRelacionamento')->name('salvar.relacionamento');
        Route::post('salvar/relacionamento/armario', 'ArmarioController@salvarRelacionamentoArmario')->name('salvar.relacionamento.armario');
        Route::post('salvar/armario', 'ArmarioController@salvarArmario')->name('salvar.armario');


        Route::get('load/fornecedor', 'FornecedorController@loadFornecedor')->name('load.fornecedor');
        Route::get('load/produto', 'ProdutosController@loadProduto')->name('load.produtos');
        Route::get('load/pedido', 'CompraController@loadPedido')->name('load.pedido');
        Route::get('load/numero/pedido', 'CompraController@loadNPedido')->name('load.numero.pedido');
        Route::get('load/numero/pedido', 'CompraController@loadNPedido')->name('load.numero.pedido');

        Route::get('load/orcamento', 'CompraController@loadOrcamento')->name('load.orcamento');
        Route::get('load/relatorios/orcamento', 'CompraController@laodRelatorioOrcamento')->name('load.relatorios.orcamento');
        Route::get('load/importacao/temp', 'ImportacaoController@loadTemp')->name('load.temp');
        Route::get('load/fornecedor/table', 'CompraController@loadFornecedorTable')->name('load.fornecedor.table');
        Route::get('load/relatorio/notas', 'ImportacaoController@loadRelatorioNotas')->name('load.relatorio.notas');
        Route::get('load/notaxml/{id}', 'ImportacaoController@loadNotaXML')->name('load.relatorio.xml');
        Route::get('load/relatorios/estoque', 'AjusteEstoqueController@loadRelatorioEstoque')->name('load.relatorios.estoque');
        Route::get('load/relatorios/pedido', 'CompraController@loadRelatorioPedido')->name('load.relatorios.pedido');
        Route::get('load/relatorios/pedido/{id}', 'CompraController@loadRelatorioPedidoProduto')->name('load.relatorios.pedido.produto');
        Route::get('load/usuarios', 'ArmarioController@usuariosCP')->name('load.usuarios');
        Route::get('load/relacionamento_armarios', 'ArmarioController@loadRelacionamentoArmario')->name('load.relacionamento.armario');
        Route::get('load/armarios', 'ArmarioController@loadArmarios')->name('load.armarios');


        Route::delete('delete/produto/{id}', 'ProdutosController@deleteProduto')->name('delete.produto');
        Route::delete('delete/fornecedor/{id}', 'FornecedorController@deleteFornecedor')->name('delete.fornecedor');
        Route::delete('delete/pedido/produto/{id}', 'CompraController@deletePedidoProduto')->name('delete.pedido.produto');
        Route::delete('delete/relacionamento_armario/{id}', 'ArmarioController@deleteRelacionamento')->name('delete.relacionamento.armario');
        Route::delete('delete/armario/{id}', 'ArmarioController@deleteArmario')->name('delete.armario');


        Route::get('delete/orcamento/fornecedor', 'CompraController@deleteOrcamentoFornecedor')->name('delete.orcamento.fornecedor');
        Route::get('load/update/temp', 'ImportacaoController@update')->name('load.temp');

    });


    Route::middleware(['accessTI'])->group(function(){

        Route::get('/qrcode', 'QrCodeController@qrCode')->name('qrcode');
        Route::get('/qrcodepdf', 'QrCodeController@qrCodePDF')->name('qrcode.pdf');

        Route::get('/cadastro/ativo', 'AtivoController@cadastroAtivo')->name('cadastro.ativo');
        Route::get('/cadastro/tipo/ativo', 'TipoAtivoController@cadastroTipoAtivo')->name('cadastro.tipoativo');
        Route::get('/cadastro/setores', 'SetoresController@cadastroSetores')->name('cadastro.setores');
        Route::get('/cadastro/pessoas', 'PessoasController@cadastroPessoas')->name('cadastro.pessoas');
        Route::get('/importa/csv', 'ImportaCSVController@telaImportacao')->name('importa.csv');
        Route::get('/relacionamento/lacres', 'RelacionamentoAtivosController@relacaoAtivos')->name('cadastro.relacao.ativos');


        Route::get('/movimento/ativo', 'MovimentoEstoqueController@movimentoEstoque')->name('movimento.estoque');
        Route::get('/envio/manutencao', 'EnvioManutencaoController@envioManutencao')->name('envio.manutencao');
        Route::get('/retorno/manutencao', 'RetornoManutencaoController@retornoManutencao')->name('retorno.manutencao');
        Route::get('/relatorios/ativos', 'RelatoriosAtivosController@relatoriosAtivos')->name('relatorios.ativos');
        Route::get('/relatorios/guia', 'RelatoriosAtivosController@guiaManutencao')->name('relatorios.guia');
        Route::get('/relatorios/dashboard', 'RelatoriosAtivosController@dashboard')->name('relatorios.dashboard');


        
        Route::get('relatorio/tipos', 'RelatoriosAtivosController@relatorioTiposAtivos')->name('relatorio.tipos.ativos');
        Route::get('relatorio/movimento', 'MovimentoEstoqueController@relatorioMovimento')->name('relatorio.movimento');
        Route::post('/relatorios/dashboard/dashTotal', 'RelatoriosAtivosController@dashTotal')->name('relatorios.dashboard.dashTotal');


        Route::post('salvar/retorno/manutencao', 'RetornoManutencaoController@salvarRetorno')->name('salvar.retorno');
        Route::post('salvar/envio/manutencao', 'EnvioManutencaoController@salvarEnvio')->name('salvar.manutencao');
        Route::post('salvar/ativo', 'AtivoController@salvarAtivo')->name('salvar.ativo');
        Route::post('salvar/tipo/ativo', 'TipoAtivoController@salvarTipoAtivo')->name('salvar.tipoativo');
        Route::post('salvar/estoque', 'EstoqueController@salvarEstoque')->name('salvar.estoque');
        Route::post('salvar/setores', 'SetoresController@salvarSetores')->name('salvar.setores');
        Route::post('salvar/pessoas', 'PessoasController@salvarPessoas')->name('salvar.pessoas');
        Route::post('salvar/movimento/estoque', 'MovimentoEstoqueController@salvarMovimento')->name('salvar.movimento');
        Route::post('salvar/csv', 'ImportaCSVController@salvarCSV')->name('salvar.csv');
        Route::post('salvar/relacionamento/op_lacre', 'RelacionamentoAtivosController@salvarRelacionamentoAtivo')->name('salvar.op_lacre');
        

        Route::get('load/estoque', 'EstoqueController@loadEstoque')->name('load.estoque');
        Route::get('load/setores', 'SetoresController@loadSetores')->name('load.setores');
        Route::get('load/tipo/ativo', 'TipoAtivoController@loadTipoAtivo')->name('load.tipoativo');
        Route::get('load/pessoas', 'PessoasController@loadPessoas')->name('load.pessoas');
        Route::get('load/ativo', 'AtivoController@loadAtivo')->name('load.ativo');
        Route::get('load/ativo/manutencao', 'EnvioManutencaoController@loadAtivoManutencao')->name('load.manutencao');
        Route::get('load/retorno/manutencao', 'RetornoManutencaoController@loadRetornoManutencao')->name('retorno.loadmanutencao');
        Route::get('load/relatorios/ativos', 'RelatoriosAtivosController@loadRelatoriosAtivos')->name('load.relatoriosativos');
        Route::get('load/operadores_ativos', 'RelacionamentoAtivosController@usuariosCPAtivos')->name('load.operadores_ativos');
        Route::get('load/operadores_lacres', 'RelacionamentoAtivosController@loadOpsLacres')->name('load.operadores_lacres');
        Route::get('load/operadores_lacres_hist/{id}', 'RelacionamentoAtivosController@loadOpsLacresHist')->name('load.operadores_lacres_hist');
       

        Route::get('/load/relatorios/tipo/ativos', 'RelatoriosAtivosController@loadRelatorioTiposAtivo')->name('load.relatorio.tipos.ativo');
        Route::get('load/relatorio/movimento', 'MovimentoEstoqueController@loadRelatorioMovimento')->name('load.relatorio.movimento');


        Route::delete('delete/estoque/{id}', 'EstoqueController@deleteEstoque')->name('delete.estoque');
        Route::delete('delete/setores/{id}', 'SetoresController@deleteSetores')->name('delete.setores');
        Route::delete('delete/tipo/ativo/{id}', 'TipoAtivoController@deleteTipoAtivo')->name('delete.tipoativo');
        Route::delete('delete/pessoas/{id}', 'PessoasController@deletePessoas')->name('delete.pessoas');
        Route::delete('delete/ativo/{id}', 'AtivoController@deleteAtivo')->name('delete.ativo');
        Route::delete('delete/relacionamento_lacre', 'RelacionamentoAtivosController@deleteRelacionamentoLacre')->name('delete.lacre');

    });

 
});

/*
Route::get('/migrando', function(){
    return Artisan::call('migrate', ["--force"=>true]);
});


Route::post('logout', 'Auth\LoginController@logout')->name('logout');
// Registration Routes...
Route::get('/home', 'HomeController@index')->name('home');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');*/