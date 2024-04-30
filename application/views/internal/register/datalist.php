<datalist id="countryDataList">
    <?php foreach($this->db->select('code,name')->from('countries')->get()->result_array() as $item){ ?>
    <option value="<?=$item['name']?>">
    <?php }; ?>
</datalist>
<datalist id="countyDataList">
    <?php foreach($this->db->select('code,name')->from('counties')->get()->result_array() as $item){ ?>
    <option value="<?=$item['name']?>">
    <?php }; ?>
</datalist>