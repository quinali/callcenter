<!DOCTYPE html>
<html lang="en">


	

<?php
	require ('validateAdminSession.php');
	require ('../config.php');
	require ("./model/encuestasModel.php");

	$surveyID= htmlspecialchars($_GET["idSurvey"]);
?>

<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Administracion Callcenter</title>

	<script src="../js/jquery.js"></script>
	
    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="encuestas.php">Encuestas</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $usuario;?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="encuestas.php"><i class="fa fa-fw fa-gear"></i> Volver</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Salir</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="administrarEncuesta.php?idSurvey=<?php echo $surveyID; ?>"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="asignarOperadores.php?idSurvey=<?php echo $surveyID;?>"><i class="fa fa-fw fa-users"></i> Operadores</a>
                    </li>
					<li class="active">
                        <a href="#"><i class="fa fa-fw fa-pencil-square-o"></i> Configuración</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-8">
                        <h1 class="page-header">
                            <small>Configuraci&oacute;n de</small>
							<br/><?php echo getTitle($dbhost,$dbuser,$dbpass,$dbname,$surveyID);?>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i><a href='administrarEncuesta.php?idSurvey=<?php echo "$surveyID"?>'>Dashboard</a>
						    </li>
                            <li class="active">
                                <i class="fa fa-pencil-square-o"></i> Configuraci&oacute;n
                            </li>
                        </ol>
                    </div>
				</div>
                <!-- /.row -->
             
			 <!-- Zona del mensaje -->
			<?php if(isset($_SESSION['message'])){ ?>
				<div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            
							<?php
									$message = $_SESSION['message'];
									unset( $_SESSION['message']);
							?>
							<i class="fa fa-info-circle"></i>  <?php echo $message;?>
                        </div>
                    </div>
                </div>
				<!-- /.row -->
			<?php } ?>
			
			
                <div class="row">
                    <div class="col-lg-8 col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-pencil-square-o fa-fw"></i> Configuraci&oacute;n de encuestas:</h3>
                            </div>
                            <div class="panel-body">
							
								<label for="url" >URL language</label>
								
								
								<div class="row">
									<div class="col-lg-12">
										<div class="col-lg-6">
											<form name="urlForm" action="updateSettings.php" method="post">
											<input type="hidden" 	name="idSurvey" value="<?php echo "$surveyID"?>"  >
											<input type="text" 		name="url" id="url" class="form-control" placeholder="Introduzca su URL" value="<?php echo getUrlSurvey($dbhost,$dbuser,$dbpass,$dbname,$surveyID); ?>" required autofocus>
										</div>	
										<div class="col-lg-2">
											<a href="javascript:getUrl();" class="btn btn-info"><span data-toggle='tooltip' data-placement='top' title='Recalcular'><i class="fa fa-refresh"></i></span></a>
											<a href="javascript: document.urlForm.submit();" class="btn btn-info"><span data-toggle='tooltip' data-placement='top' title='Guardar'><i class="fa fa-floppy-o"></i></span></a>
											</form>
										</div>	
									</div>	
								</div>
								<!-- /.row -->
									<script>
									function getUrl() {
										
										 if (window.XMLHttpRequest) {
											// code for IE7+, Firefox, Chrome, Opera, Safari
											xmlhttp = new XMLHttpRequest();
										} else {
											// code for IE6, IE5
											xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
										}
										xmlhttp.onreadystatechange = function() {
											if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
												$('input#url').val(xmlhttp.responseText);
											}
										}
										xmlhttp.open("GET", "encuestaUrl.php?accion=url&idSurvey=" + <?php echo $surveyID;?>, true);
										xmlhttp.send();
									}
									</script>
								
								
								<label for="urlTitle" >URL title</label>								
								<div class="row">
									<div class="col-lg-12">
										<div class="col-lg-6">
											<form name="titleForm" action="updateSettings.php" method="post">
												<input type="hidden" 	name="idSurvey" value="<?php echo "$surveyID"?>"  >										
												<input type="text" id="urlTitle" name="urlTitle" class="form-control" value="<?php echo getUrlTitle($dbhost,$dbuser,$dbpass,$dbname,$surveyID); ?>" required>
										</div>	
										<div class="col-lg-2">
											<script>
												function getUrlTitle() {
													
														 if (window.XMLHttpRequest) {
															// code for IE7+, Firefox, Chrome, Opera, Safari
															xmlhttp = new XMLHttpRequest();
														} else {
															// code for IE6, IE5
															xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
														}
														xmlhttp.onreadystatechange = function() {
															if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
																$('input#urlTitle').val(xmlhttp.responseText);
															}
														}
														xmlhttp.open("GET", "encuestaUrl.php?accion=title&idSurvey=" + <?php echo $surveyID;?>, true);
														xmlhttp.send();
											}
											</script>
										
											<a href="javascript:getUrlTitle()" class="btn btn-info"><span data-toggle='tooltip' data-placement='top' title='Recalcular'><i class="fa fa-refresh"></i></span></a>
											<a href="javascript: document.titleForm.submit();" class="btn btn-info"><span data-toggle='tooltip' data-placement='top' title='Guardar'><i class="fa fa-floppy-o"></i></span></a>
										</form>
									</div>	
								</div>
								</div>	
								<!-- /.row -->
								
								<label for="settings" >Pluggins settings</label>								
								<div class="row">	
									<div class="col-lg-12">
										<div class="col-lg-6">
											<form name="settingsForm"		action="updateSettings.php" method="post">
												<input type="hidden" 	name="idSurvey" value="<?php echo "$surveyID"?>"  >											
												<input type="text" name="settings" id="settings" class="form-control" placeholder="Introduzca sus paramtros" value="<?php echo getPlugginSetting($dbhost,$dbuser,$dbpass,$dbname,$surveyID); ?>" required autofocus>
										</div>
										<div class="col-lg-2">
											<a href="javascript:getPlugginSettings()" class="btn btn-info"><span data-toggle='tooltip' data-placement='top' title='Recalcular'><i class="fa fa-refresh"></i></span></a>
											<script>
												function getPlugginSettings() {
													
														if (window.XMLHttpRequest) {
															// code for IE7+, Firefox, Chrome, Opera, Safari
															xmlhttp = new XMLHttpRequest();
														} else {
															// code for IE6, IE5
															xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
														}
														xmlhttp.onreadystatechange = function() {
															if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
																$('input#settings').val(xmlhttp.responseText);
															}
														}
														xmlhttp.open("GET", "encuestaUrl.php?accion=value&idSurvey=" + <?php echo $surveyID;?>, true);
														xmlhttp.send();
											}
											
											
												</script>
												<a href="javascript: document.settingsForm.submit();" class="btn btn-info"><span data-toggle='tooltip' data-placement='top' title='Guardar'><i class="fa fa-floppy-o"></i></span></a>
											</form>
										</div>		
									</div>
									<!-- /.row -->
								</div>
							</div>
							
					    </div>
                    </div>
                </div>
                <!-- /.row -->

				
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

   

	<!-- jQuery -->
    

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

</body>

</html>


