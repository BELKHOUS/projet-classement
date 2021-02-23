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

use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\ConvertEmptyStringsToNull;

use Symfony\Component\HttpFoundation\Cookie;



class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function showRanking(Request $request)
    {
        
        $ranking = $this->repository->sortedRanking();
        $cookie = $request->cookie('followed_team');
        //dump("id equipe = ".$cookie);
        //dd($cookie);
        //Cookie::get('followed_team');
        //dump($request->session()->get('user'));
        return view('ranking', ['ranking' => $ranking , 'cookie' => $cookie]);
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
    public function createTeam(Request $request)
    {
        //dump($request->session()->get('user'));
        if($request->session()->get('user')==null){
            //return redirect()->route('login')->withErrors("Vous devez vous authentifier d'abord");
            return view ('login');
        }
        return view('team_create');
    }
//----------------------------------------------------------------------------------------------------------------------------------------------------
    public function storeTeam(Request $request)
     
    

    {
        if($request->session()->get('user')==null){
            //return view ('login');
            return redirect()->route('login')->withErrors("Vous devez vous authentifier d'abord");
        }


       $messages = [
            'team_name.required' => "Vous devez saisir un nom d'équipe.",
            'team_name.min' => "Le nom doit contenir au moins :min caractères.",
            'team_name.max' => "Le nom doit contenir au plus :max caractères.",
            'team_name.unique' => "Le nom d'équipe existe déjà."
          ]; 
        
        $rules = ['team_name' => ['required','min:3', 'max:20', 'unique:teams,name']];
        $validatedData = $request->validate($rules,$messages);
        //dd($validatedData);
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
    public function createMatch(Request $request)
    {
        //dump($request->session()->get('user'));
         if($request->session()->get('user')==null){
            return view ('login');
        }

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
        $rules = [
            'team0' => ['required', 'exists:teams,id'],
            'team1' => ['required', 'exists:teams,id'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'score0' => ['required', 'integer', 'between:0,50'],
            'score1' => ['required', 'integer', 'between:0,50'],
            
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
           return view('match_create',['teams' => $teams])->withErrors("Impossible de crée le matche");
          
        }
       
    }
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request, Repository $repository)
    {
        $rules = [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required']
        ];
        $messages = [
            'email.required' => 'Vous devez saisir un e-mail.',
            'email.email' => 'Vous devez saisir un e-mail valide.',
            'email.exists' => "Cet utilisateur n'existe pas.",
            'password.required' => "Vous devez saisir un mot de passe.",
        ];
        $validatedData = $request->validate($rules, $messages);
        try {
        # TODO 1 : lever une exception si le mot de passe de l'utilisateur n'est pas correct
        $email = $validatedData['email'];
        $password = $validatedData['password'];
        $value = $repository->getUser($email, $password);
       

        # TODO 2 : se souvenir de l'authentification de l'utilisateur
        $request->session()->put('user', $value);
        
        //dd($request->session()->get('user'));
        
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors("Impossible de vous authentifier.");
        }
        return redirect()->route('ranking.show');
    }

    public function followTeam(int $teamId)
    {
        return redirect()->route('ranking.show')->cookie('followed_team', $teamId);
    }

    public function logout(Request $request) {
        
        $ranking = $this->repository->sortedRanking();
        $cookie = $request->cookie('followed_team');
        //dump("id equipe = ".$cookie);
        //dd($cookie);
        //Cookie::get('followed_team');
        //dump($request->session()->get('user'));
        $request->session()->forget('user');
        //dump("cookies = ".$cookie);
        //dump($request->session()->get('user'));
        return view('ranking', ['ranking' => $ranking , 'cookie' => $cookie]);
        
    }

    public function deleteTeam(Request $request)
    {
        //dump($request->session()->get('user'));
         if($request->session()->get('user')==null){
            return view ('login');
        }

        $teams = $this->repository->teams();
        return view('delete_team',['teams' => $teams]);
         
    }

    function StoreDelete_team(Request $request, Repository $repository){
        $rules = [
            'team_delete' => ['required']
        ];
        $messages = [
            'team_delete.required' => 'Vous devez choisir une equipe a supprimer',
        ];

        $validatedData = $request->validate($rules, $messages);
        $teamId=$validatedData['team_delete'];
        dump($teamId);
        $repository->deleteTeam($teamId);
        //dump("id equipe = ".$cookie);
        //dd($cookie);
        //Cookie::get('followed_team');
        //dump($request->session()->get('user'));
        return view('ranking', ['ranking' => $ranking , 'cookie' => $cookie]);
        //return "Bonjour lilyyyyyyyy";
    }
//------------------------------------------------------------------------------------------------------
    public function __construct(Repository $repository)
    {

        $this->repository = $repository;
    }

}
