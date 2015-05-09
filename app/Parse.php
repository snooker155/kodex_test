<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Parse extends Model{

	public static $unguarded = true;

	public static function add($data){

		try {

			$parse = Parse::create([
				'title' => $data['title'],
				'company' => $data['company'],
				'salary' => $data['salary'],
				'city' => $data['city'],
				'experience' => $data['experience'],
				'description' => $data['description'],
				'type_of_job' =>$data['type_of_job'],
				'address' => $data['address'],
				'date_of_publicity' => $data['date_of_publicity']
			]);
			
		} catch (Exception $e) {
			return $e;
		}

		return $parse; 
	}

}