<html>
	<?php
		$head_topic = "เลือกตั้งผู้ว่ากรุงเทพฯ";
		$head_detail = "วันที่ 3 มีนาคม 2556";
		
		
		//Read feed
		function read_feed(){
			$directory = new DirectoryIterator('feed/');
			$result = array();
			
			//Each Cannidate
			foreach($directory as $file ){
				$cannidate = array();
				$campaign = array();
				$reference = array();
				$detail = array();
				if(strpos($file,".man")){
					//Read from XML file
					$xml = simplexml_load_file('feed/'.$file);
					
					foreach($xml->children() as $child)
					{
						switch ($child->getName()) {
							case 'name':
								$cannidate[] = $child;
								break;
							case 'campaign':
								foreach($child->children() as $sub_child){
									$campaign[] = $sub_child;
									$campaign[] = $sub_child->children();
								}
								break;
							case 'reference':
								foreach($child->children() as $sub_child){
									$reference[] = $sub_child;
								}
								break;
							case 'detail':
								foreach($child->children() as $sub_child){
									switch ($sub_child->getName()) {
										case 'image':
											$detail[0] = $sub_child;		
											break;
									}
								}
								break;
						}
					}
					$cannidate[] = $campaign;
					$cannidate[] = $reference;
					$cannidate[] = $detail;
					$result[] = $cannidate;
				}
			}
			return $result;
		}
	?>
	<head>
		<link type="text/css" rel="stylesheet" href="css/index.css">
		<link type="text/css" rel="stylesheet" href="plug-in/jqury.dropdown/jquery.dropdown.css" />
		<script type="text/javascript" src="plug-in/jqury.dropdown/jquery.min.js"></script>
		<script type="text/javascript" src="plug-in/jqury.dropdown/jquery.dropdown.js"></script>
		<title>Vote Fact!</title>
	</head>
	<body>
		<div id="main">
			<h1><?=$head_topic?></h1>
			<h2><?=$head_detail?></h2>
			<div id="detail">
				<?
					//Read feed
					$result = read_feed();
					shuffle($result);
					
					//Loop each cannidate
					for ($i=0; $i < count($result); $i++) {
						//Fetch data
						$cannidate = $result[$i];
						$name = $cannidate[0];
						$run_number = 1;
				?>
				<!-- Image for each cannidate -->
				<div id="img"><img width="200px" src="<?=$cannidate[3][0]?>" /></div>
				
				<!-- Detail on name, campaigns -->
				<div class="cannidate">
					<h3><?=$name?></h3>
					<div class="campaign">
				<?
						//Fetch campaigns
						for ($j=0; $j < count($cannidate[1]); $j++) {
							if($j%2==0){
								echo "<h4>".$run_number.". ".$cannidate[1][$j]."</h4></br>";
								$run_number++;
							}else
								echo $cannidate[1][$j]."</br>";
						}
				?>
					</div>
					<!-- Reference -->
					<div class="reference_cannidate">
						<a href="#" data-dropdown="<?="#ref_".$i?>">ข้อมูลอ้างอิง</a>
						<div id="<?="ref_".$i?>" class="dropdown-menu has-tip anchor-right has-scroll">
						    <ul>
						    	<?
						    		for ($k=0; $k < count($cannidate[2]); $k++) {
						    	?>
						        <li><a target="_blank" style="font-size:12px;" href="<?=$cannidate[2][$k]?>"><?=substr($cannidate[2][$k],7,(strpos(substr($cannidate[2][$k],7),"/")))?></a></li>
						        <?
									}
						        ?>
						    </ul>
						</div>
					</div>
					
				</div>
				<?
					}
				?>
			</div>
		</div>
	</body>
</html>