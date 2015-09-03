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
		$this->form_validation->set_rules("description", "Description", "trim|min_length[100]|required");
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
		$query = "INSERT INTO meals (name, description,location, meal_date, price, quantity, created_at, updated_at) VALUES (?,?,?,?,?,?, NOW(), NOW())";
		$values = array($meal['name'],$meal['description'],$meal['location'],$meal['date_of_meal'],$meal['price'],$meal['quantity']);
		$this->db->query($query, $values);
		$meal_id = $this->db->insert_id();
		$query2 = "INSERT INTO categories(name) VALUES(?)";
		$values2 = array($meal['category']);
		$this->db->query($query2, $values2);
		return TRUE;

	}
	
	public function delete($id)
	{
		$this->db->where('meal_id', $id);
		$this->db->delete('meals');
	}
	public function find($id)
	{
		return $this->db->query("SELECT * FROM meals WHERE meal_id = ?", array($id))->row_array();
	}
	public function get_meals_by_user_id($id)
	{
		return $this->db->query("SELECT * FROM meals WHERE user_id = ?", array($id))->result_array();
	}
	public function get_latest_meal_by_user_id($id)
	{
		return $this->db->query("SELECT * FROM meals WHERE user_id = ? ORDER BY meal_id DESC LIMIT 1", array($id))->row_array();
	}
	public function get_past_meals_by_user_id($id)
	{
		return $this->db->query("SELECT * FROM meals WHERE user_id = ? AND date(meal_date) < CURRENT_DATE ORDER BY meal_id DESC LIMIT 1", array($id))->row_array();
	}
	public function all_categories()
	{
		return $this->db->query("SELECT * FROM categories")->result_array();
	}

}