<?php

function return_error(string $err)
{
	
	print(
		json_encode(
			array(
				"status"  => "Error",
				"message" => $err
			)
		)
	);

}	


function get_countries(string $name)
{
	try
	{
		$content = @file_get_contents("http://restcountries.eu/rest/v2/name/$name?fields=name;alpha2Code;alpha3Code;flag;region;subregion;population;languages" );
		if($content === false)
		{
			return array(
					"status"  => "Error",
					"message" => "Could not complete request."
				);
		}
		else
		{
			return json_decode($content);
		}
	}
	catch(Exception $e)
	{
		return array(
				"status"  => "Error",
				"message" => "Could not complete request."
			);	
	}
	

}

	if(isset($_GET["name"]))
	{
		$countries = get_countries($_GET["name"]);
		if($countries["status"] !== null)
		{
			print(json_encode(array(
				"status"    => "Success",
				"message"   => "Countries retrieved Successfully.",
				"countries" => []
			)));
		} 
		else 
		{
			
			$countries_sliced = array_slice($countries,0,50);
			
			usort($countries_sliced, function($a, $b) { //Sort the array using a user defined function
				if ($a->name == $b->name){
					return $a->population < $b->population ? -1 : 1; //Compare the scores
				}
			
				return $a->name < $b->name ? -1 : 1; //Compare the scores
			});
			
			print(json_encode(array(
				"status"    => "Success",
				"message"   => "Countries retrieved Successfully.",
				"countries" => $countries_sliced
			)));
		}
		
	}
	else
	{
		return_error("Name not provided.");
	}
	
?>