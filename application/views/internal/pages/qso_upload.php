<div class="container">
    <?=form_open_multipart('', array('method'=>'POST'))?>
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="alert alert-dark">
                Ha kézzel írtál terepen QSO logot, azt itt feltöltheted!<br/>
                Engedélyezett formátumok: <b>jpg, jpeg, png, pdf, xls, xlsx</b>
            </div>
            <?=form_error('file')?>
            <?=$this->Msg->get()?>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-upload"></i></span>
                <input type="file" class="form-control" name="file" id="file" placeholder="Tallózd be ide a file-t" aria-label="Tallózd be ide a file-t" aria-describedby="basic-addon1">
                <button type="submit" name="submit" value="1" class="btn btn-outline-success">Feltöltés</button>
            </div>
        </div>
    </div>
    <?=form_close()?>
</div>