<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Admin kezdőlap</h1>
    </div>
    <div class="container">
        <div class="row">    
            <div class="col-md-12">
                <div class="alert alert-warning ">
                    Szia <?=$this->User->getName()?>!<br/>
                    Ha a főoldalt szeretnéd módosítani, kérlek jelezz a fejlesztőnek! Köszönöm!<br/>
                    Minden más esetben használd a bal oldali menüsort!<br/>
                    <br/>
                    Köszi :)
                </div>
            </div>
        </div>
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
        </div>
    </div>
      
</main>