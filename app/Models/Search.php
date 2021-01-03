<?php

namespace App\Models;

use mysqli;
use PDO;

class Search
{
    private $db;
    private $results;

    public function __construct($searchArgs = null)
    {
        // $this->db = new PDO('mysql:host=localhost;dbname=my_clever_group', 'mcg', 'd6Ha37YirSRnMtV');
        $this->db = new PDO('mysql:host=localhost;dbname=my_clever_group', 'root', 'root');
        //I am aware that root login is extremely bad practice, however I was having permissions issues with the mcg user I had created which I believe stemmed from running MAMP for hosting.
        //Even when creating a new user as a duplicate of root and using that, the issue remained, which leads me to believe the problem lies with MAMP.
        //Were this not the case, I would have remained using the .env environment variables and Laravels' DB facade.

        if($searchArgs !== NULL){
            $this->search($searchArgs);
        }
    }

    /**
     * Gets all the unique tags for search purposes.
     */
    public function getTags(){
        //TODO: Redesign this, having to loop through JSON arrays and filter out unique tags seemed great at first but is alot of work and a large amount of overhead when this could be handled much more easily with two additional DB tables.
        //Wouldn't even need both tables for this particular function either, could just SELECT * FROM search_tags WHERE active IS NOT FALSE and then add any chosen tags to the search function below.
        $query = "
            SELECT tags FROM content
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        //Organise tags into a list of unique words.
        $fullTagArray = [];        
        foreach($tags as $tagArray){
            $fullTagArray += json_decode($tagArray['tags']);
        }
        dd($fullTagArray);

        return $tags;
    }

    /**
     * Searches the database for user input values and sets result variable with all applicable results.
     * 
     * @param array $searchArgs
     */
    public function search($searchArgs){
        $searchArgs['string'] = strtolower($searchArgs['string']);
        $searchArgs['type']   = strtolower($searchArgs['type']);

        $query = "
            SELECT *
            FROM content c
        ";
        
        switch($searchArgs['type']){
            case "images":
                $query .= "
                    INNER JOIN images i ON i.content_id = c.id
                ";
                break;
            case "videos":
                $query .= "
                    INNER JOIN videos v ON v.content_id = c.id
                ";
                break;
            case "text":
                $query .= "
                    INNER JOIN text t ON t.content_id = c.id
                ";
                break;
            default:
                $query .= "
                    LEFT JOIN images i ON i.content_id = c.id
                    LEFT JOIN videos v ON v.content_id = c.id
                    LEFT JOIN text t ON t.content_id = c.id
                ";
        }

        $query .= "
            WHERE c.name LIKE :searchString
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute([':searchString' => "%".$searchArgs['string'] ."%"]);
        $this->results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getResults(){
        return $this->results;
    }
}
