<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Látogatók</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?=$this->Msg->get(); ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>IP cím</th>
                            <th>Látogatások</th>
                            <th>Utolsó látogatás</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data as $key=>$item){ ?>
                        <tr class="<?=($this->Banns->check($item['ipaddr'])) ? "table-danger" : ""?>">
                            <td>
                                <?=$this->Visitor->getFlag($item['ipaddr']) . " " . $item['ipaddr']?>
                                <?=($this->Banns->check($item['ipaddr'])) ? "KITILTVA" : ""?>
                            </td>
                            <td><?=$item['visits']?></td>
                            <td><?=$item['lastVisit']?></td>
                            <td>
                                <a href="admin/visitors/open/<?=$item['ipaddr']?>"><i class="fa-solid fa-folder-open"></i></a>&nbsp;&nbsp;
                                <?php if($this->User->hasPerm(3)){ 
                                    if($this->Banns->check($item['ipaddr'])){
                                ?>
                                <a href="admin/visitors/unbann/<?=$item['ipaddr']?>"><i class="fa-solid fa-check"></i></a>&nbsp;&nbsp;
                                <?php
                                    }else{
                                ?>
                                <a href="admin/visitors/bann/<?=$item['ipaddr']?>"><i class="fa-solid fa-ban"></i></a>&nbsp;&nbsp;
                                <?php 
                                    }
                                }; ?>
                            </td>
                        </tr>
                    <?php }; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
      
</main>