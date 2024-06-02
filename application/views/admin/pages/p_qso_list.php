<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Összeköttetések</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?=$pagi?>
                <?=$this->Msg->get(); ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>DÁTUM<br/>IDŐ</th>
                            <th>FREKVENCIA</th>
                            <th>LOGGOLTA</th>
                            <th>RÁDIÓSTÁRS</th>
                            <th>ÁLLAPOT</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>                    
                    <?php 
                    foreach($data as $key=>$item){ 
                        $css = "";
                        if($item['type'] == "external"){
                            $css = ($item['status'] == "pending") ? 'class="bg-warning text-black"' : '';
                        };
                        
                    ?>
                        <tr>
                            <td <?=$css?>><?=$item['date']?> <?=$item['time']?></td>
                            <td <?=$css?>><?=$item['freq']?></td>
                            <td <?=$css?>><?=$item['my_callsign']?></td>
                            <td <?=$css?>><?=$item['rem_callsign']?></td>
                            <td <?=$css?>>
                                <?=$this->Qso->formatStatus($item['status'])?>
                                <?=($item['type'] == "external") ? '<i class="fa-solid fa-person-circle-question" title="nem regisztrált tag"></i>' : ""?>
                            </td>
                            <td <?=$css?>>
                                <?php if($item['status'] == "pending"){ ?>
                                <a href="admin/qso/approve/<?=$item['id']?>"><i class="fa fa-fw fa-check"></i></a>
                                <a href="admin/qso/deny/<?=$item['id']?>"><i class="fa fa-fw fa-times"></i></a>
                                <?php }; ?>
                            </td>
                        </tr>
                    <?php }; ?>
                    </tbody>
                </table>
                <?=$pagi?>
            </div>
        </div>
    </div>
      
</main>