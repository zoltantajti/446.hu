<div class="container">
    <?=$this->Msg->get()?>
    <table class="table">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>Állapot</th>
                <th>Dátum</th>
                <th>Idő</th>
                <th>Hívójel</th>
                <th>Távolság</th>
                <th>Típus</th>
                <th>Mód</th>
                <th>Papagáj/átjátszó</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach($qso as $k=>$v){ 
                $direction = ($v['my_callsign'] == $this->Sess->getChain("callsign","user")) ? '<i class="fa-solid fa-arrow-turn-up"></i>' : '<i class="fa-solid fa-arrow-turn-down"></i>';    
                $dir = ($v['my_callsign'] == $this->Sess->getChain("callsign","user")) ? "out" : "in";
                $class = "";
                if($dir == "in" && $v['status'] == "pending"){ $class = "text-bg-warning"; };
				if($dir == "out"){
					if($this->db->select('id')->from('users')->where('callsign',$v['rem_callsign'])->count_all_results() == 1){
						$cs = $this->db->select('id,callsign')->from('users')->where('callsign',$v['rem_callsign'])->get()->result_array()[0];
						print_r($cs);
						$user = '<a href="internal/profile/'.$cs['id'].'" target="_blank">'.$cs['callsign'].' <i class="fa-solid fa-arrow-up-right-from-square"></i></a>';
					}else{
						$user = $v['rem_callsign'] . ' <i class="fa-solid fa-triangle-exclamation text-danger" data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="Az ellenállomás nincs regisztrálva!<br/><br/>Kérlek, írj e-mailt az info@446.hu e-mail címre az összeköttetés adataival!"></i>';
					};					
				}else{
					$cs = $this->db->select('id,callsign')->from('users')->where('callsign',$v['my_callsign'])->get()->result_array()[0];
					$user = '<a href="internal/profile/'.$cs['id'].'" target="_blank">'.$cs['callsign'].' <i class="fa-solid fa-arrow-up-right-from-square"></i></a>';
				};
            ?>
            <tr >
                <td class="<?=$class?>"><?=$direction?></td>
                <td class="<?=$class?>"><?=$this->Qso->formatStatus($v['status'])?></td>
                <td class="<?=$class?>"><?=str_replace('-','.',$v['date'])?></td>
                <td class="<?=$class?>"><?=$v['time']?></td>
                <td class="<?=$class?>"><?=$user?></td>
                <td class="<?=$class?>"><?=$v['distance']?></td>
                <td class="<?=$class?>"><?=$this->Qso->formatSuffix($v['suffix'])?></td>
                <td class="<?=$class?>"><?=$this->Qso->formatMode($v['mode'])?></td>
                <td class="<?=$class?>"><?=$v['parrot_name']?></td>
                <td class="<?=$class?>"><?php if($dir == "in" && $v['status'] == "pending"){ ?>
                    <a href="internal/qso/allow/<?=$v['id']?>"><b><i class="fa fa-fw fa-check text-success"></i></b></a>
                    <a href="internal/qso/deny/<?=$v['id']?>"><b><i class="fa fa-fw fa-times text-danger"></i></b></a>
                    <?php }; ?>
                </td>
            </tr>
            <?php }; ?>
        </tbody>
    </table>
</div>