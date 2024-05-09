<section id="ismerteto" class="mt-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="title">Ismertető</h2>
                <p class="text jsfy">
                    Jelenleg egy olyan weboldalra tévedtél, ami a PMR <i>(Public Mobile Radio)</i>, azaz Személyes használatú rádiózással foglalkozik, 
                    ennek ellenére szívesen látunk <b>"Hívójeles"</b> rádiósokat is.<br/>
                    <br/>
                    Lehetőséget szeretnénk adni egy kiterjedt rádiós közösségnek, ahol <b>nem számít az NMHH vizsga</b>, ahol <b>Mindenki barát</b>. Ezen 
                    felül reméljük, hogy minél több új tag csatlakozik a közösségünkhöz és népszerűsíteni tudjuk a rádiózást, kortól és nemtől függetlenül, 
                    légy itthon, vagy hazánk határain túl!<br/>
                    <br/>
                    Az oldalra történő regisztrációkor a webhely segít neked a hívójel kiosztásában és elmagyarázza, milyen adatokra, miért van szükség. <b>Hidd el, 
                    mindennek oka van!</b>. Emellett az oldalon rögzítheted a kapcsolatfelvételt <i>QSO</i> és a kitelepüléseid is. Az oldalon egy komplex térkép 
                    alkalmazás is fut, <i>(Vendégként is bepillantást nyerhetsz)</i>, ahol láthatod az eseményeket, kitelepüléseket, átjátszókat, stb.<br/>
                    <br/>
                    Nem titkolt célunk, hogy érkezzen <b>Android</b>ra és <b>iOS</b>ra egy app, amivel elérheted a rendszerünket telefonról, és könnyedén rögzítheted 
                    a QSO-t.
                </p>
            </div>
        </div>
    </div>
    <div class="container-fluid counter-container">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center"> 
                    <h2 class="title">Legfrissebb híreink</h2>
                    <?php foreach($this->db->select('title,alias,short,image,createdAt')->from('news')->where('isPublic',1)->order_by('createdAt','desc')->limit(5,0)->get()->result_array() as $item){ ?>
                    <div class="card mb-3">
                        <div class="row align-items-center g-0">
                            <div class="col-md-4">
                                <img src="<?=$this->Misc->parseImage($item['image'])?>" class="img-fluid rounded-start"  alt='<?=$item['title']?>'/>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?=$item['title']?></h5>
                                    <p class="card-text"><small class="text-body-secondary"><?=str_replace("-",".",$item['createdAt'])?></small></p>
                                    <p class="card-text"><?=$item['short']?></p>                                    
                                    <a href="public/hir/<?=$item['alias']?>" class="stretched-link">Elolvasom</a>
                                </div>
                            </div>
                        </div> 
                    </div>                   
                    <?php }; ?>
                </div>
                <div class="col-md-6 text-center"> 
                    <h2 class="title">Közelgő események</h2>
                    <?php foreach($this->db->select('title,seoLink,image,shortDesc,eventStart')->from('events')->where('isPublic',1)->order_by('eventStart','asc')->limit(5,0)->get()->result_array() as $item){ ?>
                    <div class="card mb-3">
                        <div class="row align-items-center g-0">
                            <div class="col-md-4">
                                <img src="<?=$this->Misc->parseImage($item['image'])?>" class="img-fluid rounded-start" alt='<?=$item['title']?>'/>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?=$item['title']?></h5>
                                    <p class="card-text"><small class="text-body-secondary"><?=str_replace("-",".",$item['eventStart'])?></small></p>
                                    <p class="card-text"><?=$item['shortDesc']?></p>                                    
                                    <a href="public/esemeny/<?=$item['seoLink']?>" class="stretched-link">Érdekel</a>
                                </div>
                            </div>
                        </div> 
                    </div>                   
                    <?php }; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="title">Céljaink</h2>
                <p class="text jsfy">
                   Ha már ide tévedtél, és tudod, mi az a 446, akkor azt is tudod, mi lehet a célkitűzésünk, de azért itt van néhány mondatba szedve.<br/>
                   <br/>
                   Legfontosabb, hogy a már megszerzett közösséget összefogjuk és egy IT hátteret biztosítsunk, azoknak, akik szeretik logolni, hogy mikor, 
                   és kivel sikerült kapcsolatot felvenniük és összefogjuk a "Hívójeles" és nem hívójeles rádiósokat egymáshoz.<br/>
                   <br/>
                   Az biztos, hogy nincs és nem is lesz könnyű dolgunk, hiszen egyes szokásokat megváltoztatni igen nehéz, de törekszünk arra, hogy ez a közösség 
                   is fejlődjön, ahogy minden más.<br/>
                   <br/>
                   Szeretném ha nem úgy kéne vadászni az állomásokat, mintha mamutra akarnék lőni. Szerintem ezzel mindenki így van. Célunk az is, hogy elérjük, 
                   hogy ne csak üljünk a rádió mellett és várjuk a bejelentkezéseket, hanem, hogy hívjunk is. Szóval törjük meg a csendet, ragadjuk meg a mikrofont, 
                   és szóljunk bele.<br/>
                   <br/>
                   Rádiót a kézbe, hangokat az éterbe; szóljon a Rock'n'Roll!
                </p>
            </div>
        </div>
    </div>
    <!--<div class="container-fluid counter-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center"> 
                    <h2 class="title">Partnereink</h2>
                    
                </div>
            </div>
        </div>
    </div>-->
</section>

