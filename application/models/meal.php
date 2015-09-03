<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Meal extends CI_Model {

	public function all()
	{
		return $this->db->query("SELECT * FROM meals")->result_array();
	}
	public function validate($meal)
	{
		$this->load->library('form_validation');		
		$this->form_validation->set_rules("name", "Name", "trim|min_length[3]|required");
		// Have changed min length of description to be 10 for ease of testing
		$this->form_validation->set_rules("description", "Description", "trim|min_length[10]|required");
		$this->form_validation->set_rules("location", "Location", "trim|required");
		$this->form_validation->set_rules("date_of_meal", "Date of Meal", "required");
		$this->form_validation->set_rules("quantity", "Quantity/Serving", "required|greater_than[1]");
		$result = array();
		if($this->form_validation->run() === FALSE)
		{
			$result[] = FALSE;
			$result[] = validation_errors();
			return $result;

		}
		else 
		{
			$result[] = TRUE;
			$result[] = "Successfully created new meal!";
			return $result;
		}
	}

	public function create($meal)
	{
		$query = "INSERT INTO meals (name, description,location, price, quantity, date_of_meal,meal_images,created_at, updated_at) VALUES (?,?,?,?,?,?,?, NOW(), NOW())";
		$values = array($meal['name'],$meal['description'],$meal['location'],$meal['price'],$meal['quantity'],$meal['date_of_meal'],$meal['meal_images']);
		return $this->db->query($query, $values);
	}
	
	public function delete($id)
	{
		$this->db->where('meal_id', $id);
		$this->db->delete('meals');
	}

}