<?php

namespace App\Http\Controllers;

use App\Models\Search;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{

    /**
     * Show the search window.
     * 
     * @return View
     */
    public function search(){
        //TODO: Handle adding tags for keyword search here.
        // $tags = new Search();
        // dd($tags->getTags());
        return view('search');
    }

    /**
     * Show the results of a search based on given terms.
     * 
     * @param array $searchTerms
     * @return View
     */
    public function results(Request $request){
        //Get the results.
        $results = (new Search($request->all()))->getResults();
        
        //Build the templates with their contents.
        $resultsView = "";
        $count = 0;
        foreach($results as $result){
            $resultsView .= view('/partials/searchResults/'.$result['type'], $result)->render(); //Render out the subtemplate HMTL into an array.
        }

        if($resultsView == ""){
            $resultsView = "No results found.";
        }

        // dd($resultsView);

        //Add the templates to the governing page and return.
        return view('results', ['searchResults' => $resultsView]);
    }

}