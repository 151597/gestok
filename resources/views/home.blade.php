<!DOCTYPE html>
<html>
    <head>
        
        <!-- Title -->
        <title>@yield('title')</title>
        
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta charset="UTF-8">
        <meta name="description" content="Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />
        
        <!-- Styles -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
        <link href="{{ asset('assets/plugins/pace-master/themes/blue/pace-theme-flash.css') }}" rel="stylesheet"/>
        <link href="{{ asset('assets/plugins/uniform/css/uniform.default.min.css') }}" rel="stylesheet"/>
        <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/plugins/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/plugins/fontawesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/plugins/line-icons/simple-line-icons.css') }}" rel="stylesheet" type="text/css"/>	
        <!-- <link href="{{ asset('assets/plugins/offcanvasmenueffects/css/menu_cornerbox.css') }}" rel="stylesheet" type="text/css"/>-->
        <link href="{{ asset('assets/plugins/waves/waves.min.css') }}" rel="stylesheet" type="text/css"/>	
        <link href="{{ asset('assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/plugins/3d-bold-navigation/css/style.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/plugins/slidepushmenus/css/component.css') }}" rel="stylesheet" type="text/css"/>	
        <link href="{{ asset('assets/plugins/weather-icons-master/css/weather-icons.min.css') }}" rel="stylesheet" type="text/css"/>	
        <link href="{{ asset('assets/plugins/metrojs/MetroJs.min.css') }}" rel="stylesheet" type="text/css"/>	
        <link href="{{ asset('assets/plugins/datatables/css/jquery.datatables.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/plugins/datatables/css/jquery.datatables.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/plugins/datatables/buttons/css/buttons.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
        
        
    
        	
        <!-- Theme Styles -->
        <link href="{{ asset('assets/css/modern.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/css/themes/gestok.css') }}" class="theme-color" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css"/>
        
        <script src="{{ asset('assets/plugins/3d-bold-navigation/js/modernizr.js') }}"></script>
        <!-- <script src="{{ asset('assets/plugins/offcanvasmenueffects/js/snap.svg-min.js') }}"></script> -->

                <!-- Javascripts -->
        <script src="{{ asset('assets/plugins/jquery/jquery-2.1.3.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/pace-master/pace.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/jquery-blockui/jquery.blockui.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/switchery/switchery.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/uniform/jquery.uniform.min.js') }}"></script>
        <!--<script src="{{ asset('assets/plugins/offcanvasmenueffects/js/classie.js') }}"></script> -->
        <!--<script src="{{ asset('assets/plugins/offcanvasmenueffects/js/main.js') }}"></script> -->
        <script src="{{ asset('assets/plugins/waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/3d-bold-navigation/js/main.js') }}"></script>
        <script src="{{ asset('assets/plugins/waypoints/jquery.waypoints.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/jquery-counterup/jquery.counterup.min.js') }}"></script>
        
        <script src="{{ asset('assets/plugins/flot/jquery.flot.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/flot/jquery.flot.time.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/flot/jquery.flot.symbol.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/flot/jquery.flot.resize.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/curvedlines/curvedLines.js') }}"></script>
        <script src="{{ asset('assets/plugins/metrojs/MetroJs.min.js') }}"></script>
        <script src="{{ asset('assets/js/modern.min.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>
        <!-- <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script> -->

        <script src="{{ asset('/assets/plugins/datatables/js/jquery.datatables.js') }}"></script>
        <!-- <script src="{{ asset('/assets/plugins/datatables/buttons/js/buttons.dataTables.js') }}"></script> -->
        <script src="{{ asset('/assets/plugins/datatables/buttons/js/dataTables.buttons.js') }}"></script> 
        
        <script src="{{ asset('/assets/plugins/datatables/buttons/js/buttons.bootstrap4.js') }}"></script>
        <script src="{{ asset('/assets/plugins/datatables/buttons/js/buttons.html5.js') }}"></script>
        <script src="{{ asset('/assets/plugins/datatables/buttons/js/buttons.flash.js') }}"></script>
        <script src="{{ asset('/assets/plugins/datatables/buttons/js/buttons.print.js') }}"></script>
        <script src="{{ asset('/assets/plugins/datatables/buttons/js/buttons.colVis.js') }}"></script>
        <script src="{{ asset('/assets/plugins/datatables/buttons/js/dataTables.tableTools.js') }}"></script>
        <script src="{{ asset('/assets/plugins/datatables/buttons/dist/jszip.min.js') }}"></script>
        
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <body class="page-header-fixed hover-menu"> 
        <main class="page-content content-wrap">
        @auth
            <div class="navbar">
                <div class="navbar-inner">
                    <div class="sidebar-pusher">
                        <a href="javascript:void(0);" class="waves-effect waves-button waves-classic push-sidebar">
                            <i class="fa fa-bars"></i>
                        </a>
                    </div>
                    <div class="logo-box">
                        <a href="/" class="logo-text"><span>Gestok</span></a>
                    </div><!-- Logo Box -->
                    
                    <div class="topmenu-outer">
                        <div class="top-menu">
                            <ul class="nav navbar-nav navbar-left">
                                <li>		
                                    <a href="javascript:void(0);" class="waves-effect waves-button waves-classic sidebar-toggle"><i class="fa fa-bars"></i></a>
                                </li>
                                
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                @guest
                                    <li>
                                        <a href="{{ route('login') }}">
                                            <span class="user-name">{{ __('Login') }}</span>
                                        </a>
                                    </li>
                                @else
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown">
                                            <span class="user-name">{{ Auth::user()->name }} <i class="fa fa-angle-down"></i></span>
                                        </a>

                                        <ul class="dropdown-menu dropdown-list" role="menu">
                                            <li role="presentation"><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();"><i class="fa fa-sign-out m-r-xs"></i>Sair</a></li>
                                             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </ul>

                                    </li>
                                @endguest
                                
                            </ul><!-- Nav -->
                        </div><!-- Top Menu -->
                    </div>
                </div>
            </div><!-- Navbar -->
            
            <div class="page-sidebar sidebar">
                <div class="page-sidebar-inner slimscroll">
                    
                    <ul class="menu accordion-menu">
                        
                        
                        <li class="droplink"><a href="#" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-list-alt"></span><p>Cadastros</p><span class="arrow"></span></a>
                            <ul class="sub-menu">
                                @if(Auth::user()->funcao == 1 || Auth::user()->funcao == 3)
                                    <li><a href="{{route('cadastro.fornecedor')}}">Fornecedores</a></li>
                                    <li><a href="{{route('cadastro.produtos')}}">Produtos/Servi&ccedil;o</a></li>
                                    <li class="divider"></li>
                                @endif
                                @if(Auth::user()->funcao == 1 || Auth::user()->funcao == 2)
                                
                                    <li><a href="{{route('cadastro.setores')}}">Setores</a></li>
                                    
                                    <li><a href="{{route('cadastro.tipoativo')}}">Tipos de Ativos</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{route('cadastro.pessoas')}}">Pessoas</a></li>
                                @endif
                                <li><a href="{{route('register')}}">Usu&aacute;rios</a></li>
                            </ul>
                        </li>

                        @if(Auth::user()->funcao == 1 || Auth::user()->funcao == 2)
                            <li class="droplink"><a href="#" class="waves-effect waves-button"><span class="menu-icon fa fa-desktop"></span><p>Ativos de TI</p><span class="arrow"></span></a>
                                <ul class="sub-menu">
                                    <li><a href="{{route('cadastro.ativo')}}">Cadastro</a></li>
                                    <li><a href="{{route('movimento.estoque')}}">Movimenta&ccedil;&atilde;o</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{route('envio.manutencao')}}">Envio para Manuten&ccedil;&atilde;o</a></li>
                                    <li><a href="{{route('retorno.manutencao')}}">Retorno da Manuten&ccedil;&atilde;o</a></li>
                                    <li class="divider"></li>
                                    <li class="droplink"><a href="#"><p>Funções</p><span class="arrow"></span></a>
                                        <ul class="sub-menu">
                                            <li><a href="{{route('importa.csv')}}">Importação de CSV</a></li>
                                            <li><a href="{{route('qrcode')}}">QR Code</a></li>
                                            <li><a href="{{route('cadastro.relacao.ativos')}}">Lacres</a></li>
                                            <li><a target="_blank" href="{{route('relatorios.guia')}}">Guia de Manuten&ccedil;&atilde;o</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->funcao == 1 || Auth::user()->funcao == 3)
                            <li class="droplink"><a href="#" class="waves-effect waves-button"><span class="menu-icon fa fa-cubes"></span><p>Compras</p><span class="arrow"></span></a>
                                <ul class="sub-menu">
                                    <li><a href="{{route('pedido.compra')}}">Pedido</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{route('ajuste.estoque')}}">Ajuste de estoque</a></li>
                                    <li><a href="{{route('importacao.nota')}}">Importar Nota de compra</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{route('orcamentos.compra')}}">Or&ccedil;amentos</a></li>
                                </ul>
                            </li>
                            <li class="droplink"><a href="#" class="waves-effect waves-button"><span class="menu-icon glyphicon glyphicon-inbox"></span><p>Armários</p><span class="arrow"></span></a>
                                <ul class="sub-menu">
                                    <li><a href="{{route('cadastro.armarios')}}">Armários</a></li>
                                    <li><a href="{{route('cadastro.relacao.armarios')}}">Relacionamento de Armários</a></li>
                                </ul>
                            </li>
                        @endif



                        <li class="droplink"><a href="#" class="waves-effect waves-button"><span class="menu-icon fa fa-file-text-o"></span><p>Relat&oacute;rios</p><span class="arrow"></span></a>
                            <ul class="sub-menu">
                                @if(Auth::user()->funcao == 1 || Auth::user()->funcao == 2)
                                    <li class="droplink"><a href="#"><p>Ativos de TI</p><span class="arrow"></span></a>
                                        <ul class="sub-menu">
                                            <li><a href="{{route('relatorios.ativos')}}">Listagem</a></li>
                                            <li><a href="{{route('relatorio.tipos.ativos')}}">Tipos de Ativo</a></li>
                                            <li><a href="{{route('relatorio.movimento')}}">Movimentação de Ativos</a></li>
                                            <li><a href="{{route('relatorios.dashboard')}}">Dashboard</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->funcao == 1 || Auth::user()->funcao == 3)
                                    <li class="droplink"><a href="#"><p>Compras</p><span class="arrow"></span></a>
                                        <ul class="sub-menu">
                                            <li><a href="{{route('relatorios.estoque')}}">Estoque</a></li>
                                            <li class="divider"></li>
                                            <li><a href="{{route('relatorios.notas')}}">Notas Fiscais</a></li>
                                            <li><a href="{{route('relatorios.orcamento')}}">Or&ccedil;amentos</a></li>
                                            <li><a href="{{route('relatorios.pedido')}}">Pedidos</a></li>
                                        </ul>
                                    </li>
                                @endif
                                @if(Auth::user()->funcao == 1 || Auth::user()->funcao == 2)
                                    <li class="droplink"><a href="#"><p>Armários</p><span class="arrow"></span></a>
                                        <ul class="sub-menu">
                                            <li><a href="{{route('relatorio.falhas')}}">Conflito de Horários</a></li>
                                        </ul>
                                    </li>
                                @endif
                                            <!--<li><a href="{{route('relatorios.ativos')}}">Estoque</a></li>
                                <li><a href="{{route('relatorios.dashboard')}}">Dashboard</a></li>
                                <li><a target='_blank' href="{{route('relatorios.guia')}}">Guia de Manuten&ccedil;&atilde;o</a></li>-->
                            </ul>
                        </li>


                        
                    </ul>
                </div><!-- Page Sidebar Inner -->
            </div><!-- Page Sidebar -->
            @endauth
            <div class="page-inner">
                @auth
                <div class="page-title">
                
                    <h3>@yield('title2')</h3>
                    <!--<div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="index.html">Home</a></li>
                            <li class="active">Dashboard</li>
                        </ol>
                    </div>-->
                </div>
                @endauth
                <div id="main-wrapper">
                    


                    @yield('content')



                </div><!-- Main Wrapper -->
                <div class="page-footer">
                    <p class="no-s"><?=date('Y')?> &copy; Gestok - Gest&atilde;o de Estoque</p>
                </div>
            </div><!-- Page Inner -->
        </main><!-- Page Content -->
        
        <div class="cd-overlay"></div>
	


        
    </body>
</html>
