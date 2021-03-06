<?php 
	defined('BASEPATH') or exit('No se permite acceso directo'); 
	require ROOT . FOLDER_PATH . "/app/assets/header.php";
?>

<header>
	<?php require ROOT . FOLDER_PATH . "/app/assets/menu.php"; ?>
</header>
<div class="container-fluid">
	<div class="contenido">
			<h1>Catálogos</h1>
				<div class="row" style="margin-bottom: 10px !important;">
					<div class="col-md-6"></div>
					<div class="col-12 col-md-6 text-right">
					<button id="nuevaCategoria" type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#CategoriaModal">
                    <i class="fas fa-bookmark marginR"> </i>  Nueva Categoria
					</button>
					</div>
				</div>

				<div class="row">
					<div class="col-12 col-md-12">		
                      <table id="CategoriasTable" class="display  display compact nowrap" style="width:100%">         
                            <thead>
                                <tr>
                                   <th style="width: 30px"></th>
                                    <th style="width: 20px">Id</th>
                                    <th style="width: 300px">Nombre</th>
                                </tr>
                            </thead>
                            <tbody id="tablaCategoriasRow">
                            </tbody>
                        </table>
                    </div>
				</div>
		
           
   </div>
</div>
<!-- Modal Agregar Almacen -->
<div class="modal fade" id="CategoriaModal" tabindex="-1" aria-labelledby="CategoriaModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header" style="padding: 10px !important;">
            <button type="button" class="close" style="padding: .6rem 1rem !important;" data-bs-dismiss="modal" aria-label="Close">
            <span  aria-hidden="true">&times;</span>
            </button>  
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-12 text-center">
                  <span class="" id="PerfilModalLabel" style="font-weight: 600; font-size: 1.2rem;"><i class="fas fa-bookmark"></i> Nueva Categoria:</span>
               </div>
            </div>
            <form id="formCategorias" class="row g-3 needs-validation" novalidate>
               <div class="row" style="width:  100% !important;">
                  <input hidden type="text" class="form-control" id="IdCategoria" aria-describedby="basic-addon3" autocomplete="off">

                  <div class="col-12 col-espace">
                     <input name="nem" type="text" class="form-control" id="NomCategoria"  placeholder="Nombre Categoria..." autocomplete="off" required>
                     <div class="invalid-feedback">
                        Escriba un Nombre.
                     </div>
                  </div>

               </div>
            </form>
            <div>
               <div class="modal-footer">
                  <div class="col-12" style="padding: 0px 70px 0px 70px !important;">
                     <button type="button"  class="btn btn-primary btn-lg btn-block" style="font-size: 1rem !important;" id="GuardarCategoria">Guardar Categorias</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Modal Borrar -->
<div class="modal fade" id="BorrarCategoriaModal" tabindex="-1" aria-labelledby="BorrarPerfilLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                <div class="modal-header ">
                </div>
                <div class="modal-body" style="padding: 0px !important;">


                <div class="row">
                    <input hidden type="text" class="form-control" id="IdCategoriaBorrar" aria-describedby="basic-addon3">
                    <div class="col-12 text-center">
                        <span class="modal-title text-center" style="font-size: 1.2rem;" id="BorrarPerfilLabel">¿Seguro que desea borrarlo?</span>
                    </div>
                </div>

                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger" id="BorrarProveedor">Borrar</button>
                    </div>
                </div>
            </div>
		</div>

 <!-- <script src="<?=  PATH_ASSETS . 'lib/Datatable_J/Js/bootstrap.bundle.min.js' ?>"></script> -->

 <!--  librerias para boostrap-->	
 <!-- <script src="<?=  PATH_ASSETS . 'lib/Datatable_J/Js/jquery-3.5.1.js' ?>"></script> -->
 <!-- <script src="<?=  PATH_ASSETS . 'lib/Datatable_J/Js/jquery.dataTables.min.js' ?>"></script> -->
 <!-- <script src="<?=  PATH_ASSETS . 'lib/Datatable_J/Js/dataTables.bootstrap4.min.js' ?>"></script> -->
 <!-- <script src="<?=  PATH_ASSETS . 'lib/Datatable_J/Js/dataTables.responsive.min.js' ?>"></script> -->
 <!-- <script src="<?=  PATH_ASSETS . 'lib/Datatable_J/Js/responsive.bootstrap4.min.js' ?>"></script> -->
 <!-- <script src="<?=  PATH_ASSETS . 'lib/Datatable_J/Js/dataTables.select.min.js' ?>"></script> -->

 <!-- <script src="<?=  PATH_ASSETS . 'lib/Datatable_J/Js/dataTables.buttons.min.js' ?>"></script> -->
 <!-- <script src="<?=  PATH_ASSETS . 'lib/Datatable_J/Js/jszip.min.js' ?>"></script> -->
 <!-- <script src="<?=  PATH_ASSETS . 'lib/Datatable_J/Js/pdfmake.min.js' ?>"></script> -->
 <!-- <script src="<?=  PATH_ASSETS . 'lib/Datatable_J/Js/vfs_fonts.js' ?>"></script> -->
 <!-- <script src="<?=  PATH_ASSETS . 'lib/Datatable_J/Js/buttons.html5.min.js' ?>"></script> -->
 <!-- <script src="<?=  PATH_ASSETS . 'lib/Datatable_J/Js/buttons.print.min.js' ?>"></script> -->


 
<script src="<?=  PATH_ASSETS . 'lib/functions.js' ?>"></script>
<script src="<?=  PATH_VIEWS . 'Categorias/Categorias.js' ?>"></script>


<?php require ROOT . FOLDER_PATH . "/app/assets/footer.php"; ?>