<nav class = "nav">
	<ul class = "nav_list">
		<li><a href="index.php"><i class="fas fa-home"></i>Inicio</a></li>
	
		<?php

		if($_SESSION['perfil'] != 5){

		?>
		<li class="principal">
			<a href="#"><i class="fas fa-list"></i>Listados</a>
			<ul>
				<li><a href="lista_empleados.php"><i class="fas fa-user"></i>Lista de Empleados</a></li>
				<li><a href="lista_proveedores.php"><i class="fas fa-store"></i>Lista de Proveedores</a></li>
				<li><a href="lista_clientes.php"><i class="fas fa-users"></i>Lista de Clientes</a></li>
				<li><a href="lista_productos.php"><i class="fas fa-shopping-cart"></i>Lista de Productos</a></li>
			</ul>
		</li>
		<?php } ?>
		<li class="principal">
			<a href="#"><i class="fas fa-plus"></i>Nuevos Registros</a>
			<ul>

			<?php

			if($_SESSION['perfil'] == 1){

			?>
				<li><a href="lineas.php"><i class="fas fa-plus-square"></i>Nuevo Linea</a></li>

			<?php } ?>	
			<?php

			if($_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 2 || $_SESSION['perfil'] == 3 || $_SESSION['perfil'] == 6 || $_SESSION['perfil'] == 7){

			?>
				<li><a href="nuevo_empleado.php"><i class="fas fa-user"></i>Nuevo Empleado</a></li>
				<li><a href="nuevo_proveedor.php"><i class="fas fa-store"></i>Nuevo Proveedor</a></li>
				<li><a href="nuevo_producto.php"><i class="fas fa-shopping-cart"></i>Nuevo Producto</a></li>

			<?php } ?>

			<?php

			if($_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 2 || $_SESSION['perfil'] == 3 || $_SESSION['perfil'] == 4){

			?>
			
				<li><a href="nuevo_cliente.php"><i class="fas fa-users"></i>Nuevo Cliente</a></li>
			<?php } ?>	

			</ul>
		</li>
		<li class="principal">
			<a href="#"><i class="fas fa-cart-plus"></i>Cotizaciones</a>
			<ul>
				<li><a href="Cotizaciones.php">Nueva Cotizacion</a></li>
				<li><a href="lista_cotizaciones.php">Lista de cotizaciones</a></li>
			</ul>
		</li>
		<li class="principal">
			<a href="#"><i class="fas fa-project-diagram"></i>LAO</a>
			<ul>
				<li><a href="lista_ordenes.php">Ordenes de Servicio</a></li>
			</ul>
		</li>

		<?php

		if($_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 2 || $_SESSION['perfil'] == 3 || $_SESSION['perfil'] == 6){

		?>
		<li class="principal">
			<a href="#">Caja menor</a>
			<ul>
				<li><a href="caja_menor.php">Caja menor</a></li>
				<li><a href="legalizaciones.php">Legalizaciones</a></li>
			</ul>
		</li>
		<?php } ?>
	</ul>
</nav>