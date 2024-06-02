<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?=$geo['title']?> markerje</h1>
    </div>
    <div class="container">
        <?=form_open('', 'class="email" id="myform"')?>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-map-marker-alt"></i></span>
                    <input type="text" class="form-control" name="lat" value="<?=$geo['lat']?>" aria-describedby="basic-addon1">
                    <input type="text" class="form-control" name="lon" value="<?=$geo['lon']?>" aria-describedby="basic-addon1">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-input-text"></i></span>
                    <input type="text" class="form-control" name="title" value="<?=$geo['title']?>" aria-describedby="basic-addon1">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-fw fa-globe"></i></span>
                    <input type="text" class="form-control" name="country" value="<?=$geo['address']['country']?>" aria-describedby="basic-addon1">
                    <input type="text" class="form-control" name="county" value="<?=$geo['address']['county']?>" aria-describedby="basic-addon1">
                    <input type="text" class="form-control" name="city" value="<?=$geo['address']['city']?>" aria-describedby="basic-addon1">
                    <input type="text" class="form-control" name="addr" value="<?=$geo['address']['addr']?>" aria-describedby="basic-addon1">
                    <a href="https://www.google.com/maps/place/<?=$geo['address']['country']?>,<?=$geo['address']['county']?>,<?=$geo['address']['city']?>,<?=$geo['address']['addr']?>" target="_blank" class="btn btn-outline-secondary" type="button" role="button"><i class="fa fa-fw fa-search"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-walkie-talkie"></i></span>
                    <input type="text" class="form-control" name="type" value="<?=$geo['type']?>" aria-describedby="basic-addon1">
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <textarea name="description" class="form-control"><?=$geo['description']?></textarea>
                <script src="./assets/js/tinymce/tinymce.min.js"></script>
                <script src="./assets/js/tinymce/index.js"></script>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <button type="submit" class="btn d-block btn-success">Mentés</button>
            </div>
        </div>
        <?=form_close()?>
    </div>
      
</main>