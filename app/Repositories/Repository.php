<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Repositories\Data;
use App\Repositories\Ranking;

class Repository
{
    function createDatabase(): void 
    {
        DB::unprepared(file_get_contents('database/build.sql'));//pas encore fini
    }
//--------------------------------------------------------------------------------------------------------------
    function insertTeam(array $team): int
    {   
        //throw new Exception("bonjour bonjour");
       // DB::table('teams')->insert($team);
       //yfcygfgfygtfytf
        $id = DB::table('teams')->insertGetId($team);
 
        return $id;
    }
   function insertMatch(array $match): int
    {
        
       // DB::table('matches')->insert($match);
        $id = DB::table('matches')->insertGetId($match);
        
            
        
        return $id;
    }
    function teams(): array
    {
        $team =  DB::table('teams')->orderBy('id', 'asc')->get()->toArray();

        return $team;
    }

    function matches(): array
    {
        return ($matches = DB::table('matches')->orderBy('id','asc')->get()->toArray());
    }
    
    function fillDatabase(): void {
        $data = new Data();
        $teams =$data->teams();
        $matches = $data->matches();
        
       foreach ($teams as $value){

            $this->insertTeam($value);
            
        }
        $this->teams();
        foreach ($matches as $valuem){

            $this->insertMatch($valuem);
            
        }
        $this->matches();
        
    }
    function team($teamId) : array
    {
        //$data = new Data();
        //$teams =$data->teams();
        //$this->fillDatabase();
        /*foreach ($teams as $value)
        {
            if($value['id']== $teamId)
                return $value;
        }*/

        
       //$teams = DB::table('teams')->where('id', 11)->get()->toArray();
        

        $teams = DB::table('teams')->where('id',$teamId)->get()->toArray();
            if(count($teams) == 0)
            {    
                throw new Exception('Équipe inconnue');
            }
            return $teams[0];  
    }
    function updateRanking(): void{

        DB::table('ranking')->delete();
        $teams = $this->teams();
        $matches = $this->matches();
        
        

        $ranking = new Ranking();
        $ranking = $ranking->sortedRanking($teams,$matches);
        if(count($ranking) == 0)
            {    
                throw new Exception('Équipe inconnue');
            }
        DB::table('ranking')->insert($ranking);
        
    }
    function sortedRanking(): array
    {
        $rows = DB::table('ranking')->join('teams','ranking.team_id', '=', 'teams.id')
        ->orderBy('rank')
        ->get(['rank','name','team_id','match_played_count','won_match_count','lost_match_count',
        'draw_match_count','goal_for_count','goal_against_count','goal_difference','points']);
        
        
        
        return $rows->toArray();

    }
    function teamMatches($teamId): array
    {
    //teams AS t0 ON matches.team0 = t0.id   
        
        $rows = DB::table('matches')
        ->join('teams AS t0','matches.team0', '=', 't0.id')
        ->join('teams AS t1','matches.team1', '=', 't1.id')
        ->where('matches.team0', $teamId)->orWhere('matches.team1', $teamId)
        ->orderBy('date')
        ->get(['matches.*','t0.name AS name0','t1.name AS name1']);
        return $rows->toArray(); 
    }
    function rankingRow($teamId): array
    {
        $classement = $this->sortedRanking();
        $classementEquipe = [];
        for($i = 0; $i<count($classement);$i++){
            if($classement[$i]['team_id']==$teamId)
            {
                $classementEquipe[0] = $classement[$i];
            }
        }
        if(count($classementEquipe) == 0) 
        {
            throw new Exception('Équipe inconnue');
        }
       
        return $classementEquipe[0];

        }
        function addUser(string $email, string $password): int
        {
            // TODO
            $passwordHash =  Hash::make($password);
            $tab = ['email'=> $email , 'password_hash'=>$passwordHash];
            $id = DB::table('users')->insertGetId($tab);
            return $id;
        }
        
        function getUser(string $email, string $password): array
        {

            $tabUser = $this->getTableUser($email);
            if(count($tabUser) === 0 ) 
            {
                throw new Exception('Utilisateur inconnu');
            }
           
            if(! (Hash::check($password, $tabUser[0]['password_hash'])))
            {
                throw new Exception('Utilisateur inconnu');
            }
            dump($email);

            $res = DB::table('users')
            ->where('email', $email)
            ->get(['id','email'])->toArray();
      
            return $res[0];
        }
        function getTableUser($email): array{
            return DB::table('users')->where('email', $email)->get()->toArray();
        }
        
        function changePassword(string $email, string $oldPassword, string $newPassword): void 
        {
            $tabUser = $this->getTableUser($email);
            

            $tablePasseWordHash = $tabUser[0]['password_hash'];
            
            $userNewPasseWordHash1 = Hash::make($oldPassword);
            $userNewPasseWordHash2 = Hash::make($oldPassword);

            //dump($userNewPasseWordHash1);
           // dd($userNewPasseWordHash2);

            //dd(count($tabUser));
            
            if(count($tabUser[0])===0) 
            {
                throw new Exception('Utilisateur inconnu');
            }



            if(! (Hash::check($oldPassword, $tabUser[0]['password_hash']))) 
            {
                throw new Exception('Utilisateur inconnu');
            }
            

            //dump($ok);

           // dd('mot de pass ok et email existant');
            //dd(Hash::make($oldPassword));
           
            

            

            DB::table('users')
            ->where('email', $tabUser[0]['email'])
            ->update([ 'password_hash'=> Hash::make($newPassword)]);

        }
}
