<script>hidden = false</script>
<?php
include 'include/connection.php'; // e catching plus connection
function JobCheck($job) {
    if ($job == false) {
        echo "Unemployed";
    } elseif ($job != false) {
        echo ucfirst($job);
    }
}
function Alive($i) {
    switch($i) {
        case 0:
            echo "Yes";
        break;
        case 1:
            echo "Dead";
        break;
    }
}
function Jail($i) {
    switch($i) {
        case 0:
            echo "No";
        break;
        case 1:
            echo "Yes";
        break;
    }
}
function Money($cash, $bank) {
    $balance = $cash+$bank;
    echo "$".$balance;
}
?>
<script>
var auto_refresh = setInterval(
    (function () {
    $(".tbl-content").load(location.href+" .tbl-content>*");
    $("#player-count").load(location.href+" #player-count>*");
    $("#stats").load(location.href+" #stats>*");
}), 1000);
</script> <?php

//default value for when server is offline
$pl_count = 0;

//get initial info
if (isset($server_settings)) {
    $content = json_decode(@file_get_contents("http://".$server_settings['ip'].":".$server_settings['port']."/info.json"), true);
$player_content = $gta5_players = @file_get_contents("http://".$server_settings['ip'].":".$server_settings['port']."/players.json");
$decoded = @json_decode($player_content, true);
}

// get players count
if (isset($content)){
    if($content):
        $server_info = json_decode(@file_get_contents("http://".$server_settings['ip'].":".$server_settings['port']."/info.json"), true);
        $gta5_players = @file_get_contents("http://".$server_settings['ip'].":".$server_settings['port']."/players.json");
        $content = @json_decode($gta5_players, true);
        $pl_count = @count($content);
    endif;
}


$players = [];
// get players data
if (isset($decoded)) {  
    foreach($decoded as $result) { //loop for each decoded json results as $result 
        
     if (isset($result)) {  // if variables arent empty then
        
        $player_data = $link -> prepare('SELECT money, job, bank, is_dead, phone_number, jail from users where identifier = ?'); // the query
        $player_data->bind_param('s', $result['identifiers'][0]); // bind missing variables
        $player_data->execute();
        $player_data->bind_result($money, $job, $bank, $is_dead, $phone_number, $jail); // binded results in same order added to the query.
        $player_data->store_result();
        $player_data->fetch();
        array_push($players, [$result['id'], $result['name'], $money, $job, $bank, $is_dead, $phone_number, $jail, $result['ping']]);
    } // ending isset
    
} //ending loop
}
?>
            <section>
<span id="pl-list-content">
<div id="player-count"> 
<h1>There are currently <b class="badge badge-danger"><?php if (isset($pl_count)) { echo $pl_count; }?>
</b> out of <b class="badge badge-danger"><?php if (isset($server_settings['max_slots'])) {
    
    echo $server_settings['max_slots']; }?></b> players online</h1>
</div>
    <?php if ($show_table) { // if ($show_table || $pl_count != 0) {?>
            <div class="tbl-header">
                <table cellpadding="0" cellspacing="0" border="0">
                <thead>
                    <tr>
                    <th scope="col" ><i class="fas fa-key"></i> ID</th>
                        <th scope="col" ><i class="fas fa-id-badge"></i> Name</th>
                        <th scope="col" ><i class="fas fa-suitcase"></i> Job</th>
                        <th scope="col" ><i class="fas fa-money-bill-alt"></i> Money</th>
                        <th scope="col" ><i class="fas fa-phone"></i> Phone Number</th>
                        <th scope="col" ><i class="fas fa-briefcase-medical"></i> Alive</th>
                        <th scope="col" ><i class="fas fa-border-all"></i> In Jail</th>
                        <th scope="col" ><i class="fas fa-wifi"></i> Ping</th>
                    </tr>
                    </thead>
                    </table>
                </div>
                <div class="tbl-content">
                <table cellpadding="0" cellspacing="0" border="0">
                <tbody>

                    <?php foreach($players as $player) { ?>
                        <tr>
                        <td><?php echo $player[0]; ?></td>
                        <td><?php echo $player[1]; ?></td>
                        <td><?php echo JobCheck($player[3]); ?></td>
                        <td><?php echo number_format(Money($player[2], $player[4]), 2, ".", ","); ?></td>
                        <td><?php echo $player[6]; ?></td>
                        <td><?php echo Alive($player[5]); ?></td>
                        <td><?php echo Jail($player[7]); ?></td>
                        <td><?php echo $player[8]." ms"; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody> 
              </table>
            </div>
            </span>
        <?php }   ?>    
        </br>  
            <div class="row"><button  class="server-stats" onclick="hidethis()">Toggle server info</button></div>
            </form><div>      
            <div id="stats" class="stats">
            <?php if (isset($server_info["vars"]["banner_connecting"])) { ?><img src="<?php echo $server_info["vars"]["banner_connecting"]; ?>" height="100px" width="1000px"><?php } ?>
            <?php if (isset($server_info["vars"]["Discord"])) { ?><h4>Discord: <a href="https://<?php echo $server_info["vars"]["Discord"];?>"><?php echo $server_info["vars"]["Discord"];?></a></h4><?php } ?>
     <?php if (isset($server_info["vars"]["Website"])) { ?> <h4>Website: <a href="https://<?php echo $server_info["vars"]["Website"]; ?>"><?php echo $server_info["vars"]["Website"]; ?></a></h4> <?php } ?>
            <h4 title="<?php foreach ($server_info["resources"] as $resources) { echo $resources.", ";}; ?>">Resources: <?php echo count($server_info["resources"]);?></h4>
            <h4>Region: <?php echo $server_settings['ip'];?> (<?php echo $server_info["vars"]["locale"];?>)</h4>
            <h4><?php echo $server_info["server"];?> (<?php echo $server_info["version"];?>)</h4>
            <a href="fivem://connect/<?php echo $server_settings['ip'];?>:<?php echo $server_settings['port'];?>"><button  class="server-stats">Join our server!</button></a> 
            </div>
            </section>               
<script>
function hidethis() {
    if (hidden == false) {
        $( ".stats" ).hide("slow");
        hidden = true;
    } else if (hidden == true) {
        $( ".stats" ).show("slow");
        hidden = false;
    }
};
function hideonload() {
    if (hidden == false) {
        $( ".stats" ).hide();
        hidden = true;
    }
};
</script>
