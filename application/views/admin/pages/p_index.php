<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Admin kezdőlap</h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="alert alert-info text-center">
                    <i class="fa-solid fa-eye fa-4x"></i>
                    <hr />
                    Egyedi látogatók: <b><?=@number_format($this->Visitor->countUnique(),0,'',' ')?></b>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-info text-center">
                    <i class="fa-solid fa-globe fa-4x"></i>
                    <hr />
                    Összes látogatások: <b><?=@number_format($this->Visitor->countAll(),0,'',' ')?></b>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert <?=($this->Markers->countNew() == 0) ? 'alert-info' : 'alert-warning'?> text-center">
                    <i class="fa-solid fa-map-marker-alt fa-4x"></i>
                    <hr />
                    <?=($this->Markers->countNew() > 0) ? '<a href="admin/markers/list" class="stretched-link" style="color:#ffda6a; text-decoration: none;">' : ''?>
                        Markerek: <b><?=@number_format($this->Markers->countAll(),0,'',' ')?></b> / <?=@number_format($this->Markers->countNew(),0,'',' ')?> új
                    <?=($this->Markers->countNew() > 0) ? '</a>' : ''?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-info text-center">
                    <i class="fa-solid fa-users fa-4x"></i>
                    <hr />
                    Felhasználók: <b><?=@number_format($this->User->countAll(),0,'',' ')?></b>
                </div>
            </div>
            <?php
                $qso = $this->db->select("id")->from('qso')->count_all_results();
                $qsoP = $this->db->select("id")->from('qso')->where('status','pending')->count_all_results();
            ?>
            <div class="col-md-4">
                <div class="alert <?=($qsoP > 0) ? 'alert-danger' : 'alert-success' ?> text-center">
                    <i class="fa-solid fa-share-from-square fa-4x"></i>
                    <hr />
                    <?=($qsoP > 0) ? '<a href="admin/qso/list" class="stretched-link" style="color:#ea868f; text-decoration:none;">' : ''?>
                    Összeköttetések: <b><?=@number_format($qso,0,'',' ')?></b> / 
                    <?=@number_format($qsoP,0,'',' ')?> Függőben
                    <?=($qsoP > 0) ? '</a>' : ''?>
                </div>
            </div>
            <?php
                $markerErrors = $this->db->select("id")->from('markers_errors')->where('resolved',0)->count_all_results();
            ?>
            <div class="col-md-4">
                <div class="alert <?=($markerErrors > 0) ? 'alert-danger' : 'alert-success' ?> text-center">
                    <i class="fa-solid fa-location-xmark fa-4x"></i>
                    <hr />
                    <?=($markerErrors > 0) ? '<a href="admin/markerErrors" class="stretched-link" style="color:#ea868f; text-decoration:none;">' : '' ?>
                        Térképhibák: <b><?=@number_format($this->db->select("id")->from('markers_errors')->where('resolved',0)->count_all_results(),0,'',' ')?></b>
                    <?=($markerErrors > 0) ? '</a>' : '' ?>
                </div>
            </div>
        </div>
    </div>
      
</main>