<!DOCTYPE html>
<html>
<head> 
	<title>Hotel Hunter</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">		
	<link rel="stylesheet" href="fonts/fontawesome/css/fontawesome-all.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="container-fluid">
		<header class="row header">
			<div class="col-md-6">
				<h1 class="logo"><span class="white">Hotel</span><span class="yellow">Hunter</span> </h1>
			</div>
			<div class="col-md-6">
				
					<?php 
/*** Siplet the json file data to multiple arrays to use it on my app ***/

					$url = "https://offersvc.expedia.com/offers/v2/getOffers?scenario=deal-finder&page=foo&uid=foo&productType=Hotel";
					$json = file_get_contents($url);
					$data = json_decode($json,true);
					$offerInfo = $data["offerInfo"];
					$userInfo = $data["userInfo"];

					$items = $data["offers"]["Hotel"];
				
					?>
				<ul class="list">
					<li class='list-item'><i class="fas fa-dollar-sign"></i><?php echo $offerInfo["currency"];?></li>
					<li class='list-item'><i class="fas fa-user-circle"></i><?php echo $userInfo["persona"]["personaType"];?></li>
				</ul>
			</div>
		</header>
	</div>
	<div class="section serch-section">
		<div class="container">
			<!-- Search form -->
			<form action="" method="GET">
					<div class="row justify-content-center align-items-center test">
						<div class="col-md-6">
							<div class="custom form-row">
								<div class="col-md-12">
									<input type="text" name="destinationName" class="form-control" placeholder="Search for a hotel">
								</div>
								<div class="col-md-12">
									<input type="text" name="destinationCity" class="form-control" placeholder="destination">
								</div>
								<div class="form-row hidden-control">
									<div class="col-md-12">
										<input type="text" name="lengthOfStay" class="form-control" placeholder="Length of stay">
									</div>
									<div class="col-md-6">
										<input type="Date" name="minTripStartDate" class="form-control" placeholder="Trip start date">
									</div>
									<div class="col-md-6">
										<input type="Date" name="maxTripStartDate" class="form-control" placeholder="Trip end date">
									</div>
									<div class="col-md-6">
										<input type="number" step="any" name="minStarRating" min="1" max="5" class="form-control" placeholder="Min star rate">
									</div>

									<div class="col-md-6">
										<input type="number" step="any" name="maxStarRating" min="1" max="5" class="form-control" placeholder="Max star rate">
									</div>
								</div>
								<div class="col-md-12">
									<input type="submit" value="Search" class="btn btn-primary">
									<span class="advance-search">Advance Search</span>
								</div>
							</div>
						</div>
					</div>
				</form>
				<?php
/*** Initialize search form variable ***/
				$scenario = "deal-finder";
				$page = "foo";
				$uid = "foo";
				$productType = "Hotel";
				$destinationName = '';
				$destinationCity = '';
				$lengthOfStay = '';
				$minTripStartDate = '';
				$maxTripStartDate = '';
				$minStarRating = '';
				$maxStarRating = '';
/*** Initialize search search_data array ***/
				$search_data  = array(
					'scenario' => urlencode($scenario),
					'page' => urlencode($page),
					'uid' => urlencode($uid),
					'productType' => urlencode($productType)
				);
/*** API url to send query parameters ***/		
				$search_url = 'https://offersvc.expedia.com/offers/v2/getOffers';
/*** check the variables not empty ***/		
				if(!empty($_GET['destinationName'])){
					$destinationName = $_GET['destinationName'];
					$search_data['destinationName'] = urlencode($destinationName);					
				}
				if(!empty($_GET['destinationCity'])){
					$destinationCity = $_GET['destinationCity'];
					$search_data['destinationCity'] = urlencode($destinationCity);					
				}
				if(!empty($_GET['lengthOfStay'])){
					$lengthOfStay = $_GET['lengthOfStay'];
					$search_data['lengthOfStay'] = urlencode($lengthOfStay);					
				}
				if(!empty($_GET['minTripStartDate'])){
					$minTripStartDate = $_GET['minTripStartDate'];
					$search_data['minTripStartDate'] = urlencode($minTripStartDate);					
				}
				if(!empty($_GET['maxTripStartDate'])){
					$maxTripStartDate = $_GET['maxTripStartDate'];	
					$search_data['maxTripStartDate'] = urlencode($maxTripStartDate);					
				}
				if(!empty($_GET['minStarRating'])){
					$minStarRating = $_GET['minStarRating'];
					$search_data['minStarRating'] = urlencode($minStarRating);					
				}
				if(!empty($_GET['maxStarRating'])){
					$maxStarRating = $_GET['maxStarRating'];
					$search_data['maxStarRating'] = urlencode($maxStarRating);					
				}
/*** Preparation query string ***/			
				$params = '';
				foreach($search_data as $key=>$value)
					$params .= $key.'='.$value.'&';

				$params = trim($params, '&');

				$ch = curl_init();
/*** Retrive search array ***/
				curl_setopt($ch, CURLOPT_URL, $search_url.'?'.$params ); //Url together with parameters
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 7); //Timeout after 7 seconds
				curl_setopt($ch, CURLOPT_HEADER, 0);

				$result = curl_exec($ch);
/*** Decode jason array and check it not empty ***/				
				$data = json_decode($result,true);
				if (isset($data["offers"]["Hotel"])) {
					$items = $data["offers"]["Hotel"];
				}
				

				?>
			</div>
		</div>




		<div class="section bottom-section">
			<section class="grid-view">
				<div class="container-fluid">
					<div class="row">
						<?php 
/*** Display the following datat in html tags, hotelInfositeUrl, hotelImageUrl, hotelName, longName, travelStartDate, travelEndDate, lengthOfStay, hotelStarRating, hotelReviewTotal, currency, originalPricePerNight and percentSavings. ***/
						foreach ($items as $item) {
							echo "<div class='col-md-6'><div class='item'><div class='item-img'><a href='".$item['hotelUrls']['hotelInfositeUrl']."'><img src=" . $item['hotelInfo']['hotelImageUrl']."></a>"."</div><div class='item-description'><h2>".$item['hotelInfo']['hotelName']."</h2>"."<span class='fas fa-location-arrow'>". $item['destination']['longName'] . "</span>" . "<span class='far fa-calendar-alt'>".$item['offerDateRange']['travelStartDate'][0]."-".$item['offerDateRange']['travelStartDate'][1]."-".$item['offerDateRange']['travelStartDate'][2]." To ".$item['offerDateRange']['travelEndDate'][0]."-".$item['offerDateRange']['travelEndDate'][1]."-".$item['offerDateRange']['travelEndDate'][2]."</span>"."<span class='far fa-clock'>length Of Stay : ".$item['offerDateRange']['lengthOfStay']." Days </span>"."<span class='fas fa-star'>".$item['hotelInfo']['hotelStarRating']."</span>"."<span class='far fa-comments'>".$item['hotelInfo']['hotelReviewTotal']." reviews</span>"."<span class='fas fa-dollar-sign'> ".$item['hotelPricingInfo']['averagePriceValue']." ".$item['hotelPricingInfo']['currency']." - ".$item['hotelPricingInfo']['originalPricePerNight']." ".$item['hotelPricingInfo']['currency']." - ".$item['hotelPricingInfo']['percentSavings']." ".$item['hotelPricingInfo']['currency']."</span></div></div></div>";
						}
						?>
					</div>
				</div>
			</section>
		</div>
		<footer> ©2018 Hotel Hunter. All rights reserved.</footer>
<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>		
<script type="text/javascript">
	/*** Show advance search field ***/
	$( document ).ready(function() {
		$( ".advance-search" ).click(function() {
			$(".hidden-control").css("display","flex");
	    	console.log( "ready!" );
		});
	});
</script>
	</body>
	</html>
