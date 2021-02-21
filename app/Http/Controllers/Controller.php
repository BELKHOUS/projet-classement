<?php

namespace App\Http\Controllers;

use Exception;
use App\Repositories\Repository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Repositories\Data;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function showRanking()
    {
        $ranking = $this->repository->sortedRanking();
        return view('ranking', ['ranking' => $ranking],);
    }
//------------------------------------------------------------------------------------------------------------------------------------------------
    public function showTeam(int $teamId)
    {
        $matches = $this->repository->teamMatches($teamId);
        $row = $this->repository->rankingRow($teamId);
        //$ranking = $this->repository->sortedRanking();

        return view('team', ['matches' => $matches,'equipe'=> $row['name'],'row'=> $row]);
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------
    public function createTeam()
    {
       
        return view('team_create');
    }
//----------------------------------------------------------------------------------------------------------------------------------------------------
    public function storeTeam(Request $request)
    {
       $messages = [
            'team_name.required' => "Vous devez saisir un nom d'équipe.",
            'team_name.min' => "Le nom doit contenir au moins :min caractères.",
            'team_name.max' => "Le nom doit contenir au plus :max caractères.",
            'team_name.unique' => "Le nom d'équipe existe déjà."
          ]; 
        
        $rules = ['team_name' => ['required','min:3', 'max:20', 'unique:teams,name']];
        $validatedData = $request->validate($rules,$messages);
        
        try {
            // appels aux méthodes de l'objet de la classe Repository
            $teamId = $this->repository->insertTeam(['name' => $validatedData['team_name']]);
            $this->repository->updateRanking();
            return redirect()->route('teams.show', ['teamId' => $teamId]);
          
        } catch (Exception $exception) {
            return redirect()->route('teams.create')->withErrors("Impossible de créer l'équipe.");
          }
        
    
     
          
    }
//---------------------------------------------------------------------------------------------------------------------------------------------------
    public function createMatch()
    {
        $teams = $this->repository->teams();
        return view('match_create',['teams' => $teams]);
    }
    
    public function storeMatch(Request $request) {
        $messages = [
            'team0.required' => 'Vous devez choisir une équipe.',
            'team0.exists' => 'Vous devez choisir une équipe qui existe.',
            'team1.required' => 'Vous devez choisir une équipe.',
            'team1.exists' => 'Vous devez choisir une équipe qui existe.',
            'date.required' => 'Vous devez choisir une date.',
            'date.date' => 'Vous devez choisir une date valide.',
            'time.required' => 'Vous devez choisir une heure.',
            'time.date_format' => 'Vous devez choisir une heure valide.',
            'score0.required' => 'Vous devez choisir un nombre de buts.',
            'score0.integer' => 'Vous devez choisir un nombre de buts entier.',
            'score0.between' => 'Vous devez choisir un nombre de buts entre 0 et 50.',
            'score1.required' => 'Vous devez choisir un nombre de buts.',
            'score1.integer' => 'Vous devez choisir un nombre de buts entier.',
            'score1.between' => 'Vous devez choisir un nombre de buts entre 0 et 50.',

            'team_name.min' => "Le nom doit contenir au moins :min caractères.",
        ];
        $lyes = $request->input('team0');
        $rules = [
            'team0' => ['required', 'exists:teams,id'],
            'team1' => ['required', 'exists:teams,id'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'score0' => ['required', 'integer', 'between:0,50'],
            'score1' => ['required', 'integer', 'between:0,50'],
            'team_name'=>['min:5']
        ];
        $validatedData = $request->validate($rules,$messages);
        try{
            
            $matcheId = $this->repository->insertMatch(['team0' => $validatedData['team0'],
                                                    'team1' => $validatedData['team1'],
                                                    'score0' => $validatedData['score0'],
                                                    'score1' => $validatedData['score1'],
                                                    'date' => $validatedData['date'] . " " . $validatedData['time'].":00",
                                                 
                                                  ]); 
            $this->repository->updateRanking();
            return redirect()->route('ranking.show');
        } catch(Exception $exception){
          
            $teams = $this->repository->teams();
           // return view('match_create',['teams' => $teams])->withErrors("Impossible de crée le matche");
           return $lyes;
        }
       
    }
//------------------------------------------------------------------------------------------------------
    public function __construct(Repository $repository)
    {

        $this->repository = $repository;
    }

}
