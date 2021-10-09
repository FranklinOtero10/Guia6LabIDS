<div class="container-fluid mt-2">
    <div class="ml-md-4 mr-md-4">
        <div class="title">
            <div class="col-4">
                <h3><?php echo isset($carrera) ? "Modificar" : "Agregar"; ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?php if ($this->session->flashdata('success')) : ?>
        <p class="success"><strong><?php echo $this->session->flashdata('success'); ?></strong></p>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')) : ?>
        <p class="error"><strong><?php echo $this->session->flashdata('error'); ?></strong></p>
    <?php endif; ?>


    <div class="ml-md-4 mr-md-4">
        <div class="row">
            <div class="offset-md-2 col-md-4 col-sm-12">
                <form action="<?= site_url("carrerasController"); ?>/<?= isset($carrera) ? "update" : 'add'; ?>" method="POST" class="form-ajax">
                    <div class="form">
                        <?php if (!isset($carrera->idcarrera)) { ?>
                            <p>id Carrera:</p>
                            <input type="text" name="idcarrera" value="<?= isset($carrera) ? $carrera->idcarrera : ""; ?>">
                        <?php } else { ?>
                            <p>id Carrera:</p>
                            <input readonly type="text" name="idcarrera" value="<?= isset($carrera) ? $carrera->idcarrera : ""; ?>">
                        <?php } ?>

                    </div>
                    <div>
                        <p>Carrera:</p>
                        <input type="text" name="carrera" value="<?= isset($carrera) ? $carrera->carrera : ""; ?>">
                    </div>
                    <br>
                    <div class="form-group">
                        <input class="btn btn-success" type="submit" value="Guardar"> <a class='btn btn-secondary' href="<?= site_url('carrerasController') ?>">Volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function func_exito(data, textStatus, xhr, form) {
        alert(data.msg);
        window.location.href = "<?= site_url('carrerasController') ?>";
    }
</script>