<?php if ($this->session->flashdata('success')) : ?>
    <p class="success"><strong><?php echo $this->session->flashdata('success'); ?></strong></p>
<?php endif; ?>
<?php if ($this->session->flashdata('error')) : ?>
    <p class="error"><strong><?php echo $this->session->flashdata('error'); ?></strong></p>
<?php endif; ?>


<div class="container-fluid mt-2">
    <div class="ml-md-4 mr-md-4">
        <div class="title">
            <div class="col-4">
                <h3><?php echo isset($profesor) ? "Modificar" : "Agregar"; ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="ml-md-4 mr-md-4">
        <div class="row">
            <div class="offset-md-2 col-md-4 col-sm-12">
                <form action="<?= site_url("ProfesoresController"); ?>/<?= isset($profesor) ? "update" : 'add'; ?>" method="POST" class="form-ajax">

                    <div class="form">
                        <div class="form-group">
                            <input class="form-control" type="hidden" name="PK_profesor" value="<?= isset($profesor) ? $profesor->idprofesor : ""; ?>">
                        </div>

                        <div class="form-group">
                            <label>Id Profesor:</label>
                            <input class="form-control" type="text" name="idprofesor" value="<?= isset($profesor) ? $profesor->idprofesor : ""; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nombre:</label>
                            <input class="form-control" type="text" name="nombre" value="<?= isset($profesor) ? $profesor->nombre : ""; ?>">
                        </div>

                        <div class="form-group">
                            <label>Apellido:</label>
                            <input class="form-control" type="text" name="apellido" value="<?= isset($profesor) ? $profesor->apellido : ""; ?>">
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input class="form-control" type="text" name="email" value="<?= isset($profesor) ? $profesor->email : ""; ?>">
                        </div>

                        <div class="form-group">
                            <label>Profesion:</label>
                            <input class="form-control" type="text" name="profesion" value="<?= isset($profesor) ? $profesor->profesion : ""; ?>">
                        </div>
                        <div class="form-group">
                            <label>Fecha de nacimiento:</label>
                            <input class="form-control" type="date" name="fecha_nacimiento" value="<?= isset($profesor) ? $profesor->fecha_nacimiento : ""; ?>">
                        </div>

                        <div class="form-group">
                            <label>Genero:</label>
                            <label class="radio-inline"><input type="radio" name="genero" value="M" <?= isset($profesor) && $profesor->genero == "M" ? "checked" : ""; ?> />Hombre</label>
                            <label class="radio-inline"><input type="radio" name="genero" value="F" <?= isset($profesor) && $profesor->genero == "F" ? "checked" : ""; ?> />Mujer</label>
                        </div>

                        <div class="form-group mt-2">
                            <input class="btn btn-success" type="submit" value="Guardar"> <a class='btn btn-secondary' href="<?= site_url('profesoresController') ?>">Volver</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>