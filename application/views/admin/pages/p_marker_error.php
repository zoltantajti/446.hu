<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Térképhibák</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?=$this->Msg->get(); ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>HÍVÓJEL</th>
                            <th>DÁTUM</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data as $item) { $css = ($item['resolved'] == 0) ? 'class="bg-danger"' : ''?>
                        <tr>
                            <td <?=$css?>><?=$item['id']?></td>
                            <td <?=$css?>><?=$item['callsign']?></td>
                            <td <?=$css?>><?=$item['createdAt']?></td>
                            <td <?=$css?>>
                                <?php if($item['resolved'] == 0) { ?>
                                <a href="admin/users/location/<?=md5($item['callsign'])?>" class="btn btn-success">
                                    <i class="fa-solid fa-circle-exclamation-check"></i> Megoldás
                                </a>
                                <?php }else{ ?>
                                    <i class="fa fa-fw fa-check"></i> Megoldva
                                <?php }; ?>
                            </td>
                        </tr>
                    <?php }; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
      
</main>