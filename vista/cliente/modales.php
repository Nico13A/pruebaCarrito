<!-- Modal para cambiar contraseña -->
<div class="modal fade" id="modalContrasenia" tabindex="-1" aria-labelledby="modalContraseniaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalContraseniaLabel">Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formContrasenia">
                    <input type="hidden" name="idusuario" value="<?php echo $objUsuario->getIdUsuario(); ?>">
                    <input type="hidden" name="usnombre" value="<?php echo $objUsuario->getUsNombre(); ?>">
                    <input type="hidden" name="usmail" value="<?php echo $objUsuario->getUsMail(); ?>">
                    <input type="hidden" name="usdeshabilitado" value="<?php echo $objUsuario->getUsDeshabilitado(); ?>"> 
                    <div class="mb-3">
                        <label for="uspass" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="uspass" name="uspass" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar email -->
<div class="modal fade" id="modalEmail" tabindex="-1" aria-labelledby="modalEmailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEmailLabel">Editar Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEmail">
                    <input type="hidden" name="idusuario" value="<?php echo $objUsuario->getIdUsuario(); ?>">
                    <input type="hidden" name="usnombre" value="<?php echo $objUsuario->getUsNombre(); ?>">
                    <input type="hidden" name="uspass" value="<?php echo $objUsuario->getUsPass(); ?>">
                    <input type="hidden" name="usdeshabilitado" value="<?php echo $objUsuario->getUsDeshabilitado(); ?>"> 
                    <div class="mb-3">
                        <label for="usmail" class="form-label">Nuevo Email</label>
                        <input type="email" class="form-control" id="usmail" name="usmail" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Email</button>
                </form>
            </div>
        </div>
    </div>
</div>
